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

    public function getReport($code)
    {
    	$Report = Report::where('code', $code)->get();
    	if( !empty( $Report ) ){
	    	$Report = $Report[0];
	    	$Report->content = json_decode($Report->content);
	    	return response()->json($Report);
    	}else{
    		abort(404);
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
