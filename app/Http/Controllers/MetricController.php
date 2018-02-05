<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 300);

use Illuminate\Http\Request;
use App\Http\Controllers\ProcessTrait;

class MetricController extends Controller
{
    use ProcessTrait;

    public function scan($id){
      $this->exeCommand('../vendor/bin/phpmetrics --report-json=/tmp/'.$id.'/report /tmp/'.$id);

      $result = (array) json_decode(file_get_contents('/tmp/'.$id.'/report'));
      unset($result['tree']);
      unset($result['Process']->methods);
      if( file_exists('/tmp/'.$id.'/composer.json') ){
        $composer = json_decode(file_get_contents('/tmp/'.$id.'/composer.json'));
        $result['composer'] = $composer;
      }

      return response()->json($result);
    }
}
