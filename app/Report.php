<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
	protected $table = 'reports';

	public function getReportByCode($code)
	{
		$report = $this->where('code', $code)->get()->toArray();
		$report = $report[0];
		$report['content'] = json_decode($report['content']);
		return $report;
	}

}
