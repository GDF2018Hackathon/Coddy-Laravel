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
use App\Report;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Log;

/**
 * @mixin \Eloquent
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProcessScanRepo implements ShouldQueue
{
    use ProcessTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $repo_name;
    private $path;
    private $branch;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($repo_name, $path = '/', $branch = 'master')
    {
        $this->repo_name = $repo_name;
        $this->path = $path;
        $this->branch = $branch;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      // $user_id = Auth::user()->id;
      $user_id = 1;
      // $user_email = Auth::user()->email;
      $user_email = 'dyner@hotmail.fr';

      $path = '/'. str_replace('-', '/', trim('/', $this->path));

      // Log::info($this->repo_name);

      $github = ApiGithubController::getRepo('traquall', $this->repo_name);

      if( !isset( $github->id ) || empty( $github->id ) ){
        Log::warning("ProcessScanRepo #64 - Github's api too request !");
        return response()->json(['code' => 403, 'message' => "Github's api too request !"], 403);
      }

      // Log::info(serialize($github));
      $repo_id = $github->id;
      $repo_clone = $github->clone_url;

      //if there is no folder for this repo, we create one and clone the repo
      if(!is_dir('/tmp/'.$repo_id)){
        // $this->exeCommand('mkdir /tmp/'.$repo_id);
        $this->exeCommand('git clone '. $repo_clone .' /tmp/'.$repo_id);
        $this->exeCommand('cd /tmp/'.$repo_id.' && git checkout '.$this->branch);
      }
      else{
        $this->exeCommand('cd /tmp/'.$repo_id.' && git pull');
        $this->exeCommand('cd /tmp/'.$repo_id.' && git checkout '.$this->branch);
      }

      $metricResult = \App::call('App\Http\Controllers\MetricController@scan', ['id' => $repo_id, 'path' => $path]);
      $snifResult = \App::call('App\Http\Controllers\SnifController@scan', ['id' => $repo_id, 'path' => $path]);

      //dd($snifResult);

      // $snifResult = str_replace('/tmp/'.$repo_id, '', $snifResult);

      $snifResult_finale = [];

      $snifResult = $snifResult->original;

      $snifResult_toparse = (array) $snifResult->files;


      foreach ($snifResult_toparse as $key => $value) {
        $snifResult_finale[str_replace('/tmp/'.$repo_id.'/', '', $key)] = $value;
      }

      $snifResult->files = $snifResult_finale;

      $result = [
        'metric' => $metricResult,
        'sniffer' => $snifResult
      ];


      $report = new Report;
      $report->code = strtoupper(uniqid());
      $report->repo_id = $repo_id;
      $report->project_name = $this->repo_name;
      $report->user_id = $user_id;
      $report->email = $user_email;
      $report->public = $github->private;
      $report->content = serialize($result);
      $report->content_url = 'https://github.com/'. $github->full_name .'/blob/'. $this->branch .'/';
      $report->created_at = Carbon::now('Europe/Paris');
      $report->save();

      return $report;
    }
}
