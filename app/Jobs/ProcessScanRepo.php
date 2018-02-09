<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\MetricController;
use App\Http\Controllers\SnifController;
use App\Http\Controllers\ProcessTrait;
use App\Http\Controllers\ApiGithubController;
use App\Http\Controllers\ReportController;
use App\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\User;
use Mail;
/**
 * @mixin \Eloquent
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProcessScanRepo implements ShouldQueue
{
    use ProcessTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_name;
    private $repo_name;
    private $path;
    private $branch;
    private $code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_name, $repo_name, $path = '/', $branch = 'master', $code)
    {
        $this->user_name = $user_name;
        $this->repo_name = $repo_name;
        $this->path = $path;
        $this->branch = $branch;
        $this->code = $code;

    }


    public function tags()
    {
      return ['ProcessScanRepo'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $user = User::where('nickname', $this->user_name)->first();
      if($user == null){
        Log::info("ProcessScanRepo #58 - Can't find user !");
        return response()->json(['code' => 403, 'message' => "Can't find user !"], 403);
      }

      $path = '/'. str_replace('-', '/', trim('/', $this->path));

      $github = ApiGithubController::getRepo($this->user_name, $this->repo_name);

      if( !isset( $github->id ) || empty( $github->id ) ){
        Log::info("ProcessScanRepo #69 - Github's api too request !");
        return response()->json(['code' => 403, 'message' => "Github's api too request !"], 403);
      }

      // Log::info(serialize($github));
      $repo_id = $github->id;
      $repo_clone = $github->clone_url;

      $pathStorage = ReportController::StoragePath() .'/'. $repo_id;
      if(!is_dir($pathStorage)){
        $command_gitclone = 'git clone '. $repo_clone .' '. ReportController::StoragePath() .'/'.$repo_id;
        $this->exeCommand($command_gitclone);
      }
      else{
        $this->exeCommand('cd '. $pathStorage.' && git pull');
        $this->exeCommand('cd '. $pathStorage.' && git checkout '.$this->branch);
      }

      $metricResult = \App::call('App\Http\Controllers\MetricController@scan', ['id' => $repo_id, 'path' => $path]);
      $snifResult = \App::call('App\Http\Controllers\SnifController@scan', ['id' => $repo_id, 'path' => $path]);

      $snifResult_finale = [];
      $snifResult = $snifResult->original;

      if( property_exists($snifResult, "files") ){
        $snifResult_toparse = (array) $snifResult->files;

        foreach ($snifResult_toparse as $key => $value) {
          $snifResult_finale[str_replace($pathStorage.'/', '', $key)] = $value;
        }

        $snifResult->files = $snifResult_finale;
      }


      $result = [
        'metric' => $metricResult,
        'sniffer' => $snifResult
      ];

      $report = new Report;
      $report->code = $this->code;
      $report->repo_id = $repo_id;
      $report->project_name = $this->repo_name;
      $report->user_id = $user->id;
      $report->email = $user->email;
      $report->public = $github->private;
      $report->content = json_encode($result);
      $report->content_url = 'https://github.com/'. $github->full_name .'/blob/'. $this->branch .'/';
      $report->created_at = Carbon::now('Europe/Paris');
      $report->save();

      if( !empty(Auth::user()->name) ){
        $NAME = Auth::user()->name;
      }else{
        $NAME = Auth::user()->nickname;
      }
      $mailParams = [
          'PROJECT' => $report->project_name,
          'ID_REPORT' => $report->repo_id,
          'NAME' => $NAME,
          'URL_REPORT' => env('APP_URL').'/report/'.$report->code
      ];
      Mail::send('mails.html.rapport', $mailParams, function ($m) use ($user) {
            $m->from('contact@coddy.me', 'Coddy Scan');
            $m->to($user->email)->subject('Coddy - Scan result');
      });
      return $this->code;
    }
}
