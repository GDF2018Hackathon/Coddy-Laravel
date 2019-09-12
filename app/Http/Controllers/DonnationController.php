<?php

namespace App\Http\Controllers;

use App\Donnation;
use Illuminate\Http\Request;
class DonnationController extends Controller
{

    const PAYPAL_ME_USER = 'CoddyMe';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return self::PAYPAL_ME_USER;
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
     * @param  \App\Donnation  $donnation
     * @return \Illuminate\Http\Response
     */
    public function show(Donnation $donnation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Donnation  $donnation
     * @return \Illuminate\Http\Response
     */
    public function edit(Donnation $donnation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Donnation  $donnation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Donnation $donnation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Donnation  $donnation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donnation $donnation)
    {
        //
    }
}
