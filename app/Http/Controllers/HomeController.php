<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('dashboard', ['modules' => Module::all()]);
    }

    public function selectModule($module_id)
    {
        $module = Module::find($module_id);
        if(!Auth::user()->hasAccess($module->sys_groupes_traitement_id))
          return redirect('dashboard');
        else {
          if(!$module->is_externe)
            session()->put('module', $module);
          return redirect($module->lien);
        }
    }
}
