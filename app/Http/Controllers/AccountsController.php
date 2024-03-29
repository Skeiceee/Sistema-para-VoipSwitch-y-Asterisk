<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests\AccountsStoreRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $currentUser = User::find(auth()->user()->id);
            $accounts = $currentUser->accounts()
                ->select(
                    'id', 
                    'title', 
                    'description', 
                    'username', 
                    'password'
                )
                ->get();
            
            return datatables()->of($accounts)
            ->addColumn('action', 'actions.accounts')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('accounts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountsStoreRequest $request)
    {
        $currentUser = User::find(auth()->user()->id);
        
        $account = new Account;

        $account->title = $request->title;
        $account->description = $request->description;
        $account->username = encrypt($request->username);
        $account->password = encrypt($request->password);

        $currentUser->accounts()->save($account);

        return redirect()->route('accounts.create')->with('status','Se ha agregado la cuenta exitosamente.');
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
        $account = Account::findOrFail($id);

        return view('accounts.edit', compact('account'));
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

        $account = Account::find($id);

        $account->title = $request->title;
        $account->description = $request->description;
        $account->username = encrypt($request->username);
        $account->password = encrypt($request->password);

        $account->save();

        return redirect()->route('accounts.create')->with('status','Se ha modificado la cuenta exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->route('accounts.index')->with('status','Se ha eliminado la cuenta exitosamente.');
    }
}
