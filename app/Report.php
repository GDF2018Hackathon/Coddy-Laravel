<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
	protected $table = 'reports';

	public function getReportByCode($code)
	{
		return $this->where('code', $code)->get();
	}

}
