<?php

namespace App\Http\Controllers;

use App\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  FAQ::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FAQ  $fAQ
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return FAQ::find($id)->toJson();
    }
    public function section($id)
    {
        return FAQ::where("category_id", $id)->get();
    }

}
