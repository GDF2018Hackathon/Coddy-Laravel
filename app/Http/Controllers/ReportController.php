<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;

class ReportController extends Controller
{
    public function index()
    {
    	$array = [];
    	for ($i=0; $i < 10; $i++) {
    		$id = 'X'. rand(10000, 99999) .'QSD0';
			$array[] = ['id' => $id, 'url' => '/api/report/'.$id, 'content' => json_decode(file_get_contents(storage_path('reports/generated.json')))];
    	}

		return response()->json($array);
    }

    public function testScan($repoName){
      $this->dispatch(new ProcessScanRepo($repoName));
    }

    public function getReport($code)
    {
    	$report = Report::where('code', $code)->get()->toArray();
    	if( !empty( $report ) ){
        $report = $report[0];
	    	$report['content'] = unserialize($report['content']);
	    	return response()->json($report);
    	}else{
    		return response()->json(['code' => 404, 'message' => 'Report Not Found'], 404);
    	}
    }

    public function sendMail($code, $template = 'mails.html.rapport')
    {
    	$Report = Report::where('code', $code)->get();
    	return view($template, [
    		'PROJECT' => 'Mon Project',
    		'ID_REPORT' => 'X12652QSD',
    		'NAME' => 'Klenzo',
    		'URL_REPORT' => 'https://www.coddy.me/report/X12652QSD'
    	]);
    }
}
