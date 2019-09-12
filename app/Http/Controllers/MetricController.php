<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 300);

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProcessTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ReportController;

class MetricController extends Controller
{
    use ProcessTrait;

    public function scan($id, $path = '/'){

      $scan_dir = ReportController::StoragePath() .'/'. $id . $path;
      $command_status = $this->exeCommand('phpmetrics --report-json="'.$scan_dir.'report.json" '.$scan_dir);

      if( !file_exists($scan_dir.'report.json') ){
        return response()->json(['code' => 520, 'message' => "Unknown Error"], 520);
      }

      $result = file_get_contents( $scan_dir.'report.json');
      $result = (array) json_decode($result);


      unset($result['tree']);
      unset($result['Process']->methods);
      if( file_exists($scan_dir.'composer.json') ){
        $composer = json_decode(file_get_contents($scan_dir.'composer.json'));
        $result['composer'] = $composer;
      }

      return response()->json($result);
    }

}
