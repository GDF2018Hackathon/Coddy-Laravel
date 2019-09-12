<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
	protected $table = 'reports';
	public $timestamps = false;

	public function getReportByCode($code)
	{
		$report = $this->where('code', $code)->get()->toArray();
		if( empty($report) || $report == null || !$report ){
			return null;
		}
		$report = $report[0];
		$report['content'] = json_decode($report['content']);
		return $report;
	}

}
