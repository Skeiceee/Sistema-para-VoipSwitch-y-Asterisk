<?php

namespace App\Http\Controllers;

use App\Client;
use App\RecurringCharge;
use App\Http\Requests\RecurringChargeStoreRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecurringChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $sigue = false;
            $month = intval(request()->get('month')); // Si es null el valor es igual a 0
            $year = intval(request()->get('year')); // Si es null el valor es igual a 0

            if($month != 0 and $year != 0){
                foreach (range(1, 12) as $number) {
                    if($month == $number){
                        $sigue = true;
                    }
                }
            }
            if($sigue){
                $date_start = Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-01')->startOfMonth();
                $date_end = Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-01')->endOfMonth();
            }else{
                $date_start = (new Carbon('first day of this month'))->startOfMonth();
                $date_end = (new Carbon('first day of this month'))->endOfMonth();
            }

            return datatables()->of(
                RecurringCharge::select(
                        'recurring_charges.id',
                        'clients.name',
                        'recurring_charges.date',
                        'recurring_charges.date_service_start',
                        'recurring_charges.description',
                        'recurring_charges.isPerMonth',
                        'recurring_charges.cost_unit',
                        'recurring_charges.quantity',
                        'recurring_charges.cost_total',
                        'recurring_charges.money_type'
                    )
                    ->join('clients', 'clients.id', 'recurring_charges.id_client')
                    ->whereBetween('date',
                    [
                        DB::raw('str_to_date("'.$date_start->format('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s")'),
                        DB::raw('str_to_date("'.$date_end->format('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s")')
                        ]
                        )
                    ->orWhere('date', '=', null)
            )
            ->addColumn('action', 'actions.recurringcharges')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
            
        }

        return view('recurringcharge.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('recurringcharge.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecurringChargeStoreRequest $request)
    {
        $recurring_charge = new  RecurringCharge();

        $recurring_charge->id_client = $request->id_client;
        $date_service_start = Carbon::createFromFormat('d/m/Y', $request->date_service_start, 'America/Santiago')
            ->hour(0)
            ->minute(0)
            ->second(0);
        $recurring_charge->date_service_start = $date_service_start;
        $recurring_charge->description = $request->description;

        $modality = $request->modality;
        if($modality == 1){
            //  Unique
            $recurring_charge->isPerMonth = 0;
            
            $date = Carbon::createFromFormat('d/m/Y', $request->date, 'America/Santiago')
                ->hour(0)
                ->minute(0)
                ->second(0);

            $recurring_charge->date = $date;
        }else if($modality == 2){
            //  Monthly
            $recurring_charge->isPerMonth = 1;
            $recurring_charge->date = null;
        }


        $recurring_charge->quantity = $request->quantity;
        $recurring_charge->cost_unit = $request->cost_unit;
        $recurring_charge->money_type = $request->money_type;
        $recurring_charge->cost_total = $request->cost_unit * $request->quantity;


        $recurring_charge->save();

        return redirect()->route('recurringcharge.index')->with('status', 'Se ha agregado correctamente el cargo recurrente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $recurringCharge = RecurringCharge::findOrFail($id);

        $clients = Client::all();

        $money_types = [
            'UF',
            'CLP',
            'USD'
        ];

        $modalities = [
            0 => 'Único',
            1 => 'Mensual'
        ];

        return view('recurringcharge.edit', compact('recurringCharge', 'clients', 'money_types', 'modalities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = RecurringCharge::findOrFail($id);
        $client->delete();
        return redirect()->route('recurringcharge.index', $client->id)->with('status', 'Se ha eliminado correctamente el cargo recurrente.');
    }
}