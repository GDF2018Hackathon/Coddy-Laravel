<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 300);

use Illuminate\Http\Request;
use App\Http\Controllers\ProcessTrait;

class MetricController extends Controller
{
    use ProcessTrait;

    public function scan($id){
      $result = $this->exeCommand('../vendor/bin/phpmetrics /tmp/'.$id);
      return $result;
    }
}
