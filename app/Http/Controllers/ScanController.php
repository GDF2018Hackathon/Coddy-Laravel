<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 300);

use Illuminate\Http\Request;
use App\Http\Controllers\MetricController;
use App\Http\Controllers\SnifController;
use App\Http\Controllers\ProcessTrait;

class ScanController extends Controller
{
    use ProcessTrait;

    public function scanAll($id){

      $result = '';
      $branch = 'master';

      //if there is no folder for this repo, we create one and clone the repo
    /*  if(!is_dir('/tmp/'.$id)){
        $this->exeCommand('mkdir /tmp/'.$id);
        // TODO put the git repo address from the ID
        $this->exeCommand('git clone https://github.com/traquall/wordpressGenerator /tmp/'.$id);
        $this->exeCommand('cd /tmp/'.$id.' && git checkout '.$branch);
      }
      else{
        $this->exeCommand('cd /tmp/'.$id.' && git pull');
        $this->exeCommand('cd /tmp/'.$id.' && git checkout '.$branch);
      }
      */

      $metricResult = \App::call('App\Http\Controllers\MetricController@scan', ['id' => $id]);
      //$snifResult = \App::call('App\Http\Controllers\SnifController@scan', ['id' => $id]);
      return $metricResult;
    }
}
