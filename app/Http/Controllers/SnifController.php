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
        '"*/vendor/*"',
        '"*/test/*"',
        '"*/bootstrap/*"',
        '"*/myreport/*"',
        '"*/node_modules/*"'
      ];

      $scan_dir = ReportController::StoragePath() .'/'. $id . $path;
      $phpcs = $this->exeCommand('phpcs --ignore='. implode(',', $ignore) .' --standard=PSR2 --report=json '.$scan_dir.' > '.$scan_dir.'coddy-phpcs.json');
      $result = json_decode($scan_dir.'coddy-phpcs.json');
      return response()->json($result);
    }

}
