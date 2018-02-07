<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 300);

use Illuminate\Http\Request;
use App\Http\Controllers\ProcessTrait;

class SnifController extends Controller
{
    use ProcessTrait;

    public function scan($id, $path){
      $ignore = [
        '*/vendor/*',
        '*/test/*',
        '*/bootstrap/*',
        '*/myreport/*'
      ];

      $scan_dir = $id . $path;

      $result = json_decode($this->exeCommand('phpcs --ignore='. implode(',', $ignore) .' --standard=PSR2 --report=json /tmp/'.$scan_dir));
      return response()->json($result);
    }

    public function scanAndSave($repo_name, $path = '/', $branch = 'master'){
      // $user_id = Auth::user()->id;
      $user_id = 1;
      // $user_email = Auth::user()->email;
      $user_email = 'dyner@hotmail.fr';

      $path = '/'. str_replace('-', '/', trim('/', $path));

      //$github = ApiGithubController::getRepo(Auth::user()->nickname, $repo_name);
      $github = ApiGithubController::getRepo(DinhoRyoh, $repo_name);

      $repo_id = $github->id;
      $repo_clone = $github->clone_url;

      //if there is no folder for this repo, we create one and clone the repo
      if(!is_dir('/tmp/'.$repo_id)){
        // $this->exeCommand('mkdir /tmp/'.$repo_id);
        $this->exeCommand('git clone '. $repo_clone .' /tmp/'.$repo_id);
        $this->exeCommand('cd /tmp/'.$repo_id.' && git checkout '.$branch);
      }
      else{
        $this->exeCommand('cd /tmp/'.$repo_id.' && git pull');
        $this->exeCommand('cd /tmp/'.$repo_id.' && git checkout '.$branch);
      }

      $ignore = [
        '*/vendor/*',
        '*/test/*',
        '*/bootstrap/*',
        '*/myreport/*'
      ];

      $scan_dir = $id . $path;

      $result = $this->exeCommand('phpcs --ignore='. implode(',', $ignore) .' --standard=PSR2 --report=json /tmp/'.$scan_dir);

      $report = new Report;
      $report->code = uniqid('SCAN');
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
