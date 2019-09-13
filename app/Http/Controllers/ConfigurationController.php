<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a list of settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('configuration.index');
    }

    /**
     * Save all configuration of the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image',
        ]);

        $user = User::find(auth()->user()->id);

        $path = $request->file('avatar')->store('avatar','public');

        if(!is_null($user->picture)){
            $path_remove = str_replace('storage/','',$user->picture);
            Storage::disk('public')->delete($path_remove);
        }

        $user->picture = 'storage/'.$path;
        $user->save();

        return redirect()->route('configuration.index');
    }
}
