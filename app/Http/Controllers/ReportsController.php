<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display of reports
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reports.index');
    }

    public function avgperhrcalls()
    {
        //Fomarto para que imprima bien el grafico
        return response()->json(
            [
                [
                    'label' => '22/09/2019',
                    'data' => [4,5,2,6,7,20,40,44,50,70,70,80,100,200,240,290,90,50,30,29,10,12,10,5],
                    'backgroundColor' => '#0941e5'
                ],
                [
                    'label' => '23/09/2019',
                    'data' => [4,5,2,6,7,20,40,44,50,70,70,80,90,220,230,270,90,50,30,29,10,12,10,5],
                    'backgroundColor' => '#4eb96e'
                ],
                [
                    'label' => '24/09/2019',
                    'data' => [4,5,2,6,7,20,40,44,50,70,70,80,90,220,230,270,90,50,30,29,10,12,10,5],
                    'backgroundColor' => '#000000'
                ]
            ]
        );
    }
}
