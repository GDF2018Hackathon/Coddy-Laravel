<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 300);

use Illuminate\Http\Request;
use App\Http\Controllers\ProcessTrait;

class SnifController extends Controller
{
    use ProcessTrait;

    public function scan($id){
      $ignore = [
        '*/vendor/*',
        '*/test/*',
        '*/bootstrap/*',
        '*/myreport/*'
      ];

      $result = $this->exeCommand('phpcs --ignore='. implode(',', $ignore) .' --standard=PSR2 --report=json /tmp/'.$id);
      // return response()->json(json_decode($result));
      return $result;
    }
}
