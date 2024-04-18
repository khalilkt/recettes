<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use Auth;
use App\Models\Annee;
use App\Models\RolesAnnee;
use App\Models\Payementmens;
use App\Models\DetailsPayementmens;
use App\Models\DegrevementContribuable;
use App\Models\RolesContribuable;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
      $query = request()->query();

      $year = $query['annee'] ?? null;
      $month = $query['mois'] ?? null;
      $day = $query['jour'] ?? null;
      $roleId = $query['role'] ?? null;

      $years=Annee::all();
      if ($year){
        $year=Annee::where('annee',$year)->get()->first();
      }else {
        $year=  Annee::where('etat',1)->get()->first();
      }

      if ($month < 0 || $month > 12)
        $month = null;
      
      if ($month == null){
        $day  = null;
      }
      $roles=RolesAnnee::where('annee',$year->annee)->where('etat','<>',2)->get();

      $totalPayment = $this->getTotalPayment($year->annee, $roleId, $month, $day);
      $totalDegrevement = $this->getTotalDegrevement($year->annee, $month, $day);
      if ( $roleId != null){
        $totalDegrevement = 0;
      }
      $totalRolesMontant = $this->getTotalRolesMontant($year->annee, $roleId, $month, $day);
      $rest = $totalRolesMontant - $totalPayment -  $totalDegrevement;
      if ($rest < 0){
        $rest = 0;
      }
       
      return view('dashboard', ['modules' => Module::all(), 'years' => $years, 'year' => $year, 'month' => $month, "day"=> $day, "roles" => $roles, "roleId" => $roleId, "total_payments" => $totalPayment, "total_degrevement" => $totalDegrevement, "total_roles_montant" => $totalRolesMontant , "rest" => $rest]);
    }


    public function getTotalRolesMontant($annee, $roleId = null, $month = null, $day = null){
      $ret = RolesContribuable::selectRaw('SUM(montant) as total_roles_montant')->where("annee", $annee);
      if ($roleId){
        $ret = $ret->where("role_id", $roleId);
      }
      if ($month){
        $month = "3";
        $ret = $ret->whereMonth('roles_contribuables.created_at', $month);

      }
      if ($day){
        $ret = $ret->whereDay('roles_contribuables.created_at', $day);
      }
      $ret = $ret->get()
      ->sum('total_roles_montant');
      
      return $ret?? 0;
    }
    public function getTotalDegrevement ($annee, $month = null, $day = null){
        $ret = DegrevementContribuable::selectRaw('SUM(montant) as total_degrevement')
        ->where("annee", $annee);
        if ($month){
          $ret = $ret->whereMonth('degrevement_contribuables.created_at', $month);
        }
        if ($day){
          $ret = $ret->whereDay('degrevement_contribuables.created_at', $day);
        }
        $ret = $ret->get()
        ->sum('total_degrevement');
        return $ret ?? 0;
    }
    public function getTotalPayment($annee, $roleId = null, $month = null, $day = null){
      
        // $ret =  Payementmens::selectRaw('SUM(montant) as total_payment')->where("annee", $annee)->value('total_payment');
        $ret = DetailsPayementmens::selectRaw('SUM(details_payementmens.montant) as total_payment')
        ->leftJoin('payementmens', function ($join) use ($annee, $roleId) {
          $join->on('details_payementmens.payement_id', '=', 'payementmens.id');
      })->where('payementmens.annee', $annee);

      if ($roleId){
        $ret = $ret->where("payementmens.role_id", $roleId);
      }
      if ($month){
        $ret = $ret->whereMonth('payementmens.date', $month);
      }
      if ($day){
        $ret = $ret->whereDay('payementmens.date', $day);
      }
        $ret = $ret->get()
      ->sum('total_payment');
        return $ret ?? 0;

        //   $totalAmountToPay = Contribuable::selectRaw('(SUM(roles_contribuables.montant) - SUM(degrevement_contribuables.montant) - SUM(details_payementmens.montant) ) AS total_amount_to_pay')
        // ->leftJoin('roles_contribuables', function ($join) use ($annee) {
        //     $join->on('contribuables.id', '=', 'roles_contribuables.contribuable_id')
        //          ->where('roles_contribuables.annee', $annee);
        // })
        // ->leftJoin('payementmens', function ($join) use ($annee) {
        //     $join->on('contribuables.id', '=', 'payementmens.contribuable_id')
        //          ->where('payementmens.annee', $annee);
        // })
        // ->leftJoin('details_payementmens', function ($join) use ($annee){
        //     $join->on('payementmens.id', '=', 'details_payementmens.payement_id')
        //          ->where('payementmens.annee', $annee);
        // })
        // ->leftJoin('degrevement_contribuables', function ($join) use ($annee) {
        //     $join->on('contribuables.id', '=', 'degrevement_contribuables.contribuable_id')
        //          ->where('degrevement_contribuables.annee', $annee);
        // })
        // ->value('total_amount_to_pay');
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
