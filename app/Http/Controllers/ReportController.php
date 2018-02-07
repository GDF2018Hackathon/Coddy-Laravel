<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Report;
use App\Jobs\ProcessScanRepo;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['code' => 400, 'code_text' => 'Bad Request', 'message' => 'Vous devez avoir un code de rapport.'], 400)
               ->header('Accept', 'application/json')
               ->header('X-Powered-By', 'Coddy')
               ->header('Server', 'Coddy');
    }

    public function testScan($repoName){
      $this->dispatch(new ProcessScanRepo('traquall', $repoName));
    }

    public function scanAll($user, $repoName , $branch = 'master',$path = '/'){
      $this->dispatch(new ProcessScanRepo($user, $repoName , $path, $branch ));
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
    	$Report = Report::where('code', $code)->get()->toArray();

        dd($Report);

    	return view($template, [
    		'PROJECT' => 'Mon Project',
    		'ID_REPORT' => 'X12652QSD',
    		'NAME' => 'Klenzo',
    		'URL_REPORT' => 'https://www.coddy.me/report/X12652QSD'
    	]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show($code, Report $report)
    {
        return response()->json($report->getReportByCode($code));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
    }
}
