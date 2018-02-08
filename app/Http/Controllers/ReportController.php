<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Auth;
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

    static function StoragePath() {
      return storage_path() . '/reports/repos';
    }

    public function scanAll($repoName, $branch = 'master', $path = '/', $user = null){

      if( $user == null ){ $user = Auth::user()->nickname; }
      $code = strtoupper(uniqid());
      if($this->dispatch(new ProcessScanRepo($user, $repoName , $path, $branch, $code))){
        return response()->json(['code' => 200, 'message' => 'Redirection', 'data' => ['code' => $code]]);
      }else{
        return redirect('/project');
      }

    }

    public function getReport($code)
    {
    	$report = Report::where('code', $code)->get()->toArray();
    	if( !empty( $report ) ){
        $report = $report[0];
	    	return response()->json($report);
    	}else{
    		return response()->json(['code' => 404, 'message' => 'Report Not Found'], 404);
    	}
    }

    public function sendMail($code, $template = 'mails.html.rapport')
    {
    	$report = Report::where([['code', $code], ['user_id', Auth::user()->id]])->get()->toArray();

      if( isset( $report[0] ) && !empty($report[0]) ){
        $report = $report[0];
      }else{
        return redirect()->route('Error404Bad');
      }

      if( !empty(Auth::user()->name) ){
        $NAME = Auth::user()->name;
      }else{
        $NAME = Auth::user()->nickname;
      }
    	return view($template, [
    		'PROJECT' => $report['project_name'],
    		'ID_REPORT' => $report['repo_id'],
    		'NAME' => Auth::user()->nickname,
    		'URL_REPORT' => env('APP_URL').'/report/'.$report['code']
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
        $result = $report->getReportByCode($code);
        if( $result == false ){
          return redirect()->route('Error404Bad');
        }else{
          return response()->json($result);
        }
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
