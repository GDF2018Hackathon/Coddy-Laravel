<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 300);

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProcessTrait;
use Illuminate\Support\Facades\Log;

class MetricController extends Controller
{
    use ProcessTrait;

    public function scan($id, $path = '/'){

      $scan_dir = $id . $path;

      $command_status = $this->exeCommand('./vendor/bin/phpmetrics --report-json=/tmp/'.$id.'/report.json /tmp/'.$scan_dir);


      $result = file_get_contents('/tmp/'.$id.'/report.json');
      $result = (array) json_decode($result);

      unset($result['tree']);
      unset($result['Process']->methods);
      if( file_exists('/tmp/'.$id.'/composer.json') ){
        $composer = json_decode(file_get_contents('/tmp/'.$id.'/composer.json'));
        $result['composer'] = $composer;
      }

      return response()->json($result);
    }

    public function scanAndSave($repo_name, $path = '/', $branch = 'master'){
      $user_id = Auth::user()->id;
      if(isset($_POST['scan_email'])){ $user_email = Auth::user()->email; }else{ $user_email = $email; }

      $path = '/'. str_replace('-', '/', trim('/', $path));

      $github = ApiGithubController::getRepo(Auth::user()->nickname, $repo_name);
      if( !isset($github->id) || empty($github->id)){
  			return response()->json($github, 400);
  		}
      $repo_id = $github->id;
      $repo_clone = $github->clone_url;

      //if there is no folder for this repo, we create one and clone the repo
      if(!is_dir('/tmp/'.$repo_id)){
        $this->exeCommand('git clone '. $repo_clone .' /tmp/'.$repo_id);
        $this->exeCommand('cd /tmp/'.$repo_id.' && git checkout '.$branch.' && git pull');
      }else{
        $this->exeCommand('cd /tmp/'.$repo_id.' && git pull');
        $this->exeCommand('cd /tmp/'.$repo_id.' && git checkout '.$branch.' && git pull');
      }

      // $scan_dir = $id . $path;
      // $this->exeCommand('./vendor/bin/phpmetrics --report-json=/tmp/'.$id.'/report /tmp/'.$scan_dir);
      //
      // $result = (array) json_decode(file_get_contents('/tmp/'.$id.'/report'));
      // unset($result['tree']);
      // unset($result['Process']->methods);
      // if( file_exists('/tmp/'.$id.'/composer.json') ){
      //   $composer = json_decode(file_get_contents('/tmp/'.$id.'/composer.json'));
      //   $result['composer'] = $composer;
      // }

      if( !$this->scan($repo_id, $path) ){
        $this->exeCommand('rm -rf /tmp/'.$repo_id);
        return response()->json(['code' => 418, "I do not know what happened, could you try again?"], 418);
      }

      $report = new Report;
      $report->code = uniqid();
      $report->repo_id = $repo_id;
      $report->project_name = $repo_name;
      $report->user_id = $user_id;
      $report->email = $user_email;
      $report->public = $github->private;
      $report->content = serialize($result);
      $report->created_at = Carbon::now('Europe/Paris');
      $report->save();

      return $report;
    }
}
