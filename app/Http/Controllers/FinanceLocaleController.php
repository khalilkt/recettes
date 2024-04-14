<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EmployeController;
use App\Exports\ExportBudgets;
use App\Exports\SuiviExecutionParniveau;
use Carbon\Carbon;
use App\Models\EnteteCommune;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Requests\BudgetRequest;
use App\Http\Requests\BudgetDetailRequest;
use App\Http\Requests\EcritureRequest;
use App\Models\Nomenclature;
use App\Models\Commune;
use App\Models\Budget;
use App\Models\RefTypeBudget;
use App\Models\BudgetDetail;
use App\Models\RelNomenclatureElement;
use App\Models\NomenclatureElement;
use App\Models\Recette;
use App\Models\Depense;
use App\Models\RefTypeNomenclature;
use App\Models\RefTypeDepense;
use App\Models\RefTypeRecette;
use App\Exports\SuiviSituationBgd;
use PDF;
use DB;
use Auth;
use App\Exports\ExportEquipement;
use Excel;

use DataTables;
use App\User;
use App;
use function Matrix\determinant;

//use Auth;

class FinanceLocaleController extends Controller
{
    private $module = 'finances';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function anne_budgetaire()
    {
        $ref_etat_budget_id = 0;
        $annee ='';
        if (Budget::orderBy('annee', 'DESC')->get()->first()){
            $annee = Budget::orderBy('annee', 'DESC')->get()->first()->annee;}
        else {
            $annee=date('Y');
        }
        if ($annee != '' and Budget::orderBy('annee', 'DESC')->get()->first()) {
            $budgets = Budget::where('annee', $annee)->orderBy('id', 'DESC')->get();
            $ref_etat_budget_id = $budgets->first()->ref_etat_budget_id;
            if ($ref_etat_budget_id == 3) {
                $annee = $annee + 1;
            }
        }
        else {
            $annee = date('Y');
        }
        return $annee;
    }

    public function index()
    {
        $nomenclatue=NomenclatureElement::find(1);
        $array=NomenclatureElement::all();
        $module = Module::find(4);
        //une variable pour identifier s il ya des budgets pour cette annee
        $etat_bdg = 'aucun';
        $annee ='';
        if (Budget::orderBy('annee', 'DESC')->get()->first()){
        $annee = Budget::orderBy('annee', 'DESC')->get()->first()->annee;
    }
        else {
            $annee=date('Y');
        }
        $html = '';
        $libelle_budget = '';
        $budget_id = 0;
        $ref_etat_budget_id = 0;
        $budgets = Budget::where('annee', $annee)->orderBy('id', 'DESC')->get();
        $anciens_bdg = Budget::where('ref_etat_budget_id', 3)->orderBy('annee', 'DESC')
            ->select('annee')->distinct()->get();

        if ($budgets->count() == 1 && Budget::where('annee', $annee)->where('ref_type_budget_id', '<>',3)->first()->ref_type_budget_id == 1) {
            $etat_bdg = 'bgd_initial';
            $bidgetinitial = Budget::where('annee', $annee)->first();
            $array = NomenclatureElement::where('budget_id', $bidgetinitial->id)
                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                ->select('nomenclature_elements.*', 'budget_details.montant')
                ->distinct()->get();
            $html = $this->showGroupsInPanel(0, 1, $array, $bidgetinitial->id);
            // $html = $this->showElementBudget($bidgetinitial->id);
            $libelle_budget = $budgets->first()->libelle;
            $budget_id = $budgets->first()->id;
            $ref_etat_budget_id = $budgets->first()->ref_etat_budget_id;
        } elseif ($budgets->count() > 1) {
            $etat_bdg = 'plus_budgets';
            $id = $budgets->first()->id;
            $libelle_budget = $budgets->first()->libelle;
            $budget_id = $budgets->first()->id;
            $ref_etat_budget_id = $budgets->first()->ref_etat_budget_id;
            $array = NomenclatureElement::where('budget_id', $id)
                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                ->select('nomenclature_elements.*', 'budget_details.montant')
                ->distinct()->get();
            $html = $this->showGroupsInPanel(0, 1, $array, $id);
        } else {
            $etat_bdg = 'aucun';
        }
        $id = env('APP_COMMUNE');
        $commune = Commune::find($id);
        $type_budgets = RefTypeBudget::all();
        return view($this->module . '.index', ['html' => $html, 'etat_bdg' => $etat_bdg, 'budgets' => $budgets, 'commune' => $commune, 'type_budgets' => $type_budgets, 'libelle_budget' => $libelle_budget, 'annee' => $annee, 'budget_id' => $budget_id, 'ref_etat_budget_id' => $ref_etat_budget_id, 'anciens_bdg' => $anciens_bdg, 'module' => $module]);
    }

    public function getDT($selected = 'all')
    {
        $secteurs = Secteur::all();
        if ($selected != 'all')
            $secteurs = Secteur::orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($secteurs)
            ->addColumn('actions', function (Secteur $secteurs) {
                $html = '<div class="btn-group">';
                $html .= ' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal(' . $secteurs->id . ',\'' . $this->module . '\')" data-toggle="tooltip" data-placement="top" title="' . trans('text.visualiser') . '"><i class="fa fa-fw fa-eye"></i></button> ';
                $html .= ' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\'' . url($this->module . '/delete/' . $secteurs->id) . '\',\'' . trans('text.confirm_suppression') . '\')" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></button> ';
                $html .= '</div>';
                return $html;
            })
            ->setRowClass(function ($secteurs) use ($selected) {
                return $secteurs->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id', 'actions'])
            ->make(true);
    }

    public function formAdd()
    {
        $annee = $this->anne_budgetaire();
        $budget=Budget::first();
        $id = env('APP_COMMUNE');
        $commune = Commune::find($id);
        $nomenclature = Nomenclature::first();
        $maxOrdre = Budget::where('annee', $annee)->max('ordre_complementaire') + 1 or 1;
        $type_budgets = RefTypeBudget::all();
        return view($this->module . '.add', ['commune' => $commune, 'nomenclature' => $nomenclature, 'maxOrdre' => $maxOrdre, 'type_budgets' => $type_budgets, 'annee' => $annee, 'budget' => $budget]);
    }

    public function editbudget(BudgetRequest $request)
    {
        $budget = Budget::find($request->id);
        $budget->libelle = $request->libelle;
        $budget->libelle_ar = $request->libelle_ar;
        $budget->save();
        return response()->json($request->bidget_id, 200);
    }

    public function add(BudgetRequest $request)
    {
        $annee = $request->annee;
        if ($request->ordre == 1 and $request->type_budget_id==1) {
            $budgetfn = new Budget();
            $budgetfn->libelle = trans('text_me.budget_fianl') . ' ' . $annee;
            $budgetfn->libelle_ar = trans('text_me.budget_fianl_ar') . ' ' . $annee;
            $budgetfn->annee = $annee;
            $budgetfn->commune_id = $request->commune_id;
            $budgetfn->nomenclature_id = $request->nomenclature_id;
            $budgetfn->ordre_complementaire = $request->ordre;
            $budgetfn->ref_type_budget_id = 3;
            $budgetfn->ref_etat_budget_id = 3;
            $budgetfn->save();
        }
        $identique = 0;
        if (isset($request->identique)) {
            $identique = 1;
        }

        $budget = new Budget();
        $budget->libelle = $request->libelle;
        $budget->libelle_ar = $request->libelle_ar;
        $budget->annee = $annee;
        $budget->commune_id = $request->commune_id;
        $budget->nomenclature_id = $request->nomenclature_id;
        $budget->ordre_complementaire = $request->ordre;
        $budget->ref_type_budget_id = $request->type_budget_id;
        $budget->ref_etat_budget_id = $request->etat;
        $budget->save();

        $data = collect();
        $data1 = collect();
        if ($budget->id) {
            // $elements = RelNomenclatureElement::all(); // a voir la table avec sidi
            if ($request->ordre == 1 and $request->type_budget_id==1) {
                if ($identique == 1) {
                    $anciens_bdg = Budget::where('ref_etat_budget_id', 3)->where('annee', '<>',$annee)->where('ref_type_budget_id', 3)->orderBy('annee', 'DESC')->get();
                    $annee_pre = $anciens_bdg->first()->annee;
                    //$budgets = Budget::where('annee', $annee_pre)->orderBy('id', 'DESC')->get();
                    $bdg_id = $anciens_bdg->first()->id;
                    $elements = BudgetDetail::where('budget_id', $bdg_id)->get();
                    foreach ($elements as $value) {
                        $data->push(["budget_id" => $budget->id, "nomenclature_element_id" => $value->nomenclature_element_id, "montant" => $value->montant]);
                        $data1->push(["budget_id" => $budgetfn->id, "nomenclature_element_id" => $value->nomenclature_element_id, "montant" => 0]);

                    }
                }
                else {
                    $elements = RelNomenclatureElement::all();
                    foreach ($elements as $value) {
                        $data->push(["budget_id" => $budget->id, "nomenclature_element_id" => $value->nomenclature_element_id, "montant" => 0]);
                        $data1->push(["budget_id" => $budgetfn->id, "nomenclature_element_id" => $value->nomenclature_element_id, "montant" => 0]);
                    }
                }
                BudgetDetail::insert($data1->toArray());
            }
            else {
                $last_id_budget = Budget::where('annee', $annee)->where('ref_etat_budget_id', 2)->max('id');
                $elements = BudgetDetail::where('budget_id', $last_id_budget)->get();
                foreach ($elements as $value) {
                    $data->push(["budget_id" => $budget->id, "nomenclature_element_id" => $value->nomenclature_element_id, "montant" => $value->montant]);
                }
            }
            BudgetDetail::insert($data->toArray());
        }
        return response()->json($budget->id, 200);
    }

    public function valideBg($id)
    {
        $budget = Budget::find($id);
        $budget->ref_etat_budget_id = 2;
        $budget->save();
        $data = collect();
        //recuperer & ecraser budget finale
        $budgetfn = Budget::where('annee', $budget->annee)->where('ref_type_budget_id', 3)->get();
        $id=$budgetfn->first()->id;
        $bdg_dt=BudgetDetail::where('budget_id', $id);
        $bdg_dt->forceDelete();
        $elements = BudgetDetail::where('budget_id', $budget->id)->get();
        foreach ($elements as $value) {
            $data->push(["budget_id" => $id, "nomenclature_element_id" => $value->nomenclature_element_id, "montant" => $value->montant]);
        }
        BudgetDetail::insert($data->toArray());
        return response()->json($id, 200);
    }

    public function devalideBg($id)
    {
        $budget = Budget::find($id);
        $budget->ref_etat_budget_id = 1;
        $budget->save();
        $budgetfn = Budget::where('annee', $budget->annee)->where('ref_type_budget_id', 3)->get();
        $id=$budgetfn->first()->id;
        $bdg_dt=BudgetDetail::where('budget_id', $id);
        $bdg_dt->forceDelete();
        $data = collect();
        $last_id_budget = Budget::where('annee', $budget->annee)->where('ref_etat_budget_id', 2)->max('id');
     if ($last_id_budget!=''){
            $elements = BudgetDetail::where('budget_id', $last_id_budget)->get();
            foreach ($elements as $value) {
              $data->push(["budget_id" => $id, "nomenclature_element_id" => $value->nomenclature_element_id, "montant" => $value->montant]);
            }
         }
        else{
            $elements = BudgetDetail::where('budget_id', $budget->id)->get();
            foreach ($elements as $value) {
                $data->push(["budget_id" => $id, "nomenclature_element_id" => $value->nomenclature_element_id, "montant" => 0]);
            }
        }
        BudgetDetail::insert($data->toArray());
        return response()->json($id, 200);
    }

    public function clotureBg($annee)
    {
        $budgets = Budget::where('annee', $annee)->get();
        $id = $budgets->first()->id;
        foreach ($budgets as $bud) {
            $budget = Budget::find($bud->id);
            $budget->ref_etat_budget_id = 3;
            $budget->save();
        }
        return response()->json($id, 200);
    }

    public function getbudget($id)
    {
        $budget = Budget::find($id);
        return view($this->module . '.tabs.getbudget', ['budget' => $budget]);
    }

    public function updateEcriture(EcritureRequest $request)
    {
        $annee = '2020';
        if ($request->sense=="R"){
            $ecriture = Recette::find($request->id);
            $ecriture->ref_type_recette_id = $request->typeEcriture;
            $ecriture->origine = $request->texte;
        }
        if ($request->sense=="D"){
            $ecriture =  Depense::find($request->id);
            $ecriture->ref_type_depenses = $request->typeEcriture;
            $ecriture->beneficiaire = $request->texte;
        }
         $ecriture->description = $request->description;
        $ecriture->annee = $annee;
        $ecriture->nomenclature_element_id = $request->nomenclature_element_id;
        $ecriture->date = $request->date;
        $montant=str_replace(' ', '', $request->montant);
        $value = (float)$montant;
        $ecriture->montant = $value;
        $ecriture->user_id = Auth::user()->id;
        $ecriture->ged = 1;
        $ecriture->save();
        return response()->json('Done',200);
    }
    public function saveEcriture(EcritureRequest $request)
    {
    $annee=$this->budgetFinalanne();
    $nomenclature = NomenclatureElement::find($request->id);
    $sens='';
    $types='';
    if ($nomenclature->ref_type_nomenclature_id==1){
        $ecriture = new Recette();
        $ecriture->ref_type_recette_id = $request->typeEcriture;
        $ecriture->origine = $request->texte;
    }
    if ($nomenclature->ref_type_nomenclature_id==2){
        $sens='D';
        $ecriture = new Depense();
        $ecriture->ref_type_depenses = $request->typeEcriture;
        $ecriture->beneficiaire = $request->texte;
    }
    $ecriture->description = $request->description;
    $ecriture->annee = $annee;
    $ecriture->nomenclature_element_id = $request->id;
    $ecriture->date = $request->date;
    $montant=str_replace(' ', '', $request->montant);
    $value = (float)$montant;
    $ecriture->montant = $value;
    $ecriture->user_id = Auth::user()->id;
    $ecriture->ged = 1;
    $ecriture->save();
    return response()->json($ecriture->id,200);
    }
/*$nomenclature = NomenclatureElement::find($id);
$sens='';
$annee='2020';
$ecritures='';
if ($nomenclature->ref_type_nomenclature_id==1){
$sens='R';
$ecritures= Recette::where('nomenclature_element_id',$id)->where('annee',$annee)->get();
}
if ($nomenclature->ref_type_nomenclature_id==2){
    $sens='D';
    $ecritures= Recette::where('nomenclature_element_id',$id)->where('annee',$annee)->get();
}*/

    public function getEcritures($id,$selected='all')
    {
        $nomenclature = NomenclatureElement::find($id);
        $sens='';
        $annee=$this->budgetFinalanne();
        $ecritures='';
        if ($nomenclature->ref_type_nomenclature_id==1){
            $sens='R';
            $ecritures= Recette::where('nomenclature_element_id',$id)->where('annee',$annee);
            if ($selected != 'all')
                $ecritures= Recette::where('nomenclature_element_id',$id)->where('annee',$annee)->orderByRaw('id = ? desc', [$selected]);
            return DataTables::of($ecritures)
                ->addColumn('actions', function(Recette $ecritures) {
                    $html = '<div class="btn-group">';
                    $html .= ' <button type="button" class="btn btn-sm btn-dark" onClick="showEditEcritureForm(' . $ecritures->id . ',1)" data-toggle="tooltip" data-placement="top" title="' . trans('text.visualiser') . '"><i class="fas fa-eye"></i></button>';
                    if(Auth::user()->hasAccess(4,5)) {
                        $html .= ' <button type="button" class="btn btn-sm btn-danger" onClick="deleteEcriture(' . $ecritures->id . ',1)" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></button>';
                    }
                    $html .='</div>';
                    return $html;
                })
                ->editColumn('date', function(Recette $ecritures) {
                    return Carbon::parse($ecritures->date)->format('d-m-Y');
                })
                ->setRowClass(function ($ecritures) use ($selected) {
                    return $ecritures->id == $selected ? 'alert-success' : '';
                })
                ->rawColumns(['id','actions'])
                ->make(true);
        }
        if ($nomenclature->ref_type_nomenclature_id==2){
            $sens='D';
            $ecritures= Depense::where('nomenclature_element_id',$id)->where('annee',$annee);
            if ($selected != 'all')
                $ecritures= Depense::where('nomenclature_element_id',$id)->where('annee',$annee)->orderByRaw('id = ? desc', [$selected]);
            return DataTables::of($ecritures)
                ->addColumn('actions', function(Depense $ecritures) {
                    $html = '<div class="btn-group">';
                    $html .= '<button type="button" class="btn btn-sm btn-dark" onClick="showEditEcritureForm(' . $ecritures->id . ',2)" data-toggle="tooltip" data-placement="top" title="' . trans('text.visualiser') . '"><i class="fas fa-eye"></i></button>';
                    $html .='<button type="button" class="btn btn-sm btn-danger" onClick="deleteEcriture('.$ecritures->id.',2)" data-toggle="tooltip" data-placement="top" title="'.trans('text.supprimer').'"><i class="fas fa-trash"></i></button>';
                    $html .='</div>';
                    return $html;
                })
                ->editColumn('date', function(Depense $ecritures) {
                    return Carbon::parse($ecritures->date)->format('d-m-Y');
                })
                ->setRowClass(function ($ecritures) use ($selected) {
                    return $ecritures->id == $selected ? 'alert-success' : '';
                })
                ->rawColumns(['id','actions'])
                ->make(true);
        }

    }
    public function historiqueEcriture($id)
    {
        $nomenclature = NomenclatureElement::find($id);
        $sens='';
        $types='';
        if ($nomenclature->ref_type_nomenclature_id==1){
            $sens='R';
            $types= RefTypeRecette::all();
        }
        if ($nomenclature->ref_type_nomenclature_id==2){
            $sens='D';
            $types= RefTypeDepense::all();
        }
        return view($this->module . '.tabs.ajax.ecritures', ['nomenclature' => $nomenclature,'types' => $types,'sens' => $sens,]);
    }

    public function getEditEcriture($id)
    {

        $ecriture=Recette::find($id);

        if ($ecriture == null)
        {
            $ecriture=Depense::find($id);
        }

        $tablink = $this->module.'/editEcritures/getTab/'.$id;
        $numbers=array(1=>1,2=>2);
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
            '<i class="fa fa-file-archive"></i> '.trans('text_me.documents') => $tablink.'/2',
        ];
        $modal_title = '<b>'.trans('text_me.items_plan_title').' :'.$ecriture->libelle.'</b>';
        return view(  'tabs',['tabs'=>$tabs,'modal_title'=>$modal_title,'numbre'=>$numbers]);
    }

    public function getTabEditEcriture($id,$tab)
    {
        $sens='R';
        $ecriture=Recette::find($id);
        if ($ecriture == null)
        {
            $ecriture=Depense::find($id);
            $sens='D';
            $types= RefTypeDepense::all();
            $libelleTexte=trans('text_me.beneficieur');
            $texte=$ecriture->beneficiaire;
        }
        else{
            $types= RefTypeRecette::all();
            $libelleTexte=trans('text_me.origine');
            $texte=$ecriture->origine;
        }

        switch ($tab) {
            case '1':
                $parametres = ['ecriture' => $ecriture,'types' => $types,'sens' => $sens,'texte'=>$texte,'libelleTexte'=>$libelleTexte];
                break;
            default :
                $parametres = ['ecriture' => $ecriture,'types' => $types,'sens' => $sens,'texte'=>$texte,'libelleTexte'=>$libelleTexte];
                break;
        }
        return view($this->module.'.editEcritures.tabs.tab'.$tab,$parametres);
    }
    public function EditEcriture($id,$sen)
    {
        $sens='';
        $types='';
        if ($sen==1){
            $ecriture=Recette::find($id);
            $sens='R';
            $types= RefTypeRecette::all();
            $libelleTexte=trans('text_me.origine');
            $texte=$ecriture->origine;
        }
        if ($sen==2){
            $ecriture=Depense::find($id);
            $sens='D';
            $types= RefTypeDepense::all();
            $libelleTexte=trans('text_me.beneficieur');
            $texte=$ecriture->beneficiaire;
        }
        return view($this->module . '.tabs.ajax.editEcriture', ['ecriture' => $ecriture,'types' => $types,'sens' => $sens,'texte'=>$texte,'libelleTexte'=>$libelleTexte]);
    }
    public function getEcriture($id)
    {

        $nomenclature = NomenclatureElement::find($id);
        $sens='';
        $types='';
        $titre='';
        if ($nomenclature->ref_type_nomenclature_id==1){
            $sens='R';
            $types= RefTypeRecette::all();
            $titre=trans('text_me.recette');
        }
        if ($nomenclature->ref_type_nomenclature_id==2){
            $sens='D';
            $types= RefTypeDepense::all();
            $titre=trans('text_me.depence');
        }
        $tablink = $this->module.'/ecritures/getTab/'.$id;
        $numbers=array(1=>1);
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
        ];
        $modal_title = '<b>'.trans('text_me.items_plan_title').' :'.trans('text_me.saisiEcriture').' '.$titre.'</b>';
        return view(  'tabs',['tabs'=>$tabs,'modal_title'=>$modal_title,'numbre'=>$numbers]);
     }

    public function getTabEcriture($id,$tab)
    {
        $nomenclature = NomenclatureElement::find($id);
        $sens='';
        $types='';
        if ($nomenclature->ref_type_nomenclature_id==1){
            $sens='R';
            $types= RefTypeRecette::all();
        }
        if ($nomenclature->ref_type_nomenclature_id==2){
            $sens='D';
            $types= RefTypeDepense::all();
        }
        switch ($tab) {
            case '1':
                $parametres = ['nomenclature' => $nomenclature,'types' => $types,'sens' => $sens];
                break;
            default :
                $parametres = ['nomenclature' => $nomenclature,'types' => $types,'sens' => $sens];
                break;
        }
        return view($this->module.'.ecritures.tabs.tab'.$tab,$parametres);
    }

    public function BudgetFiltre($id_bdg,$niveau,$classe)
    {
        $budget = Budget::find($id_bdg);
        $html='';
        if ($niveau != 'all') {
            $array = NomenclatureElement::where('budget_id', $id_bdg)->where('niveau', '<=', $niveau)
                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                ->select('nomenclature_elements.*', 'budget_details.montant')
                ->distinct()->get();
        } else {
            $array = NomenclatureElement::where('budget_id', $id_bdg)
                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                ->select('nomenclature_elements.*', 'budget_details.montant')
                ->distinct()->get();
        }
        if ($classe != 0 ) {
            $classes='';
            $exploded = explode(",", trim($classe));
            foreach ($exploded as $class) {
                $data = NomenclatureElement::find($class);
                $classes .=$data->code .',';
            }
            $classes  = rtrim( $classes , ",");
            $exploded = explode(",", trim($classe));
            foreach ($exploded as $clas) {
                $data = NomenclatureElement::find($clas);
                $classe_libelle = $data->libelle;
                $classe_libelle_ar = $data->libelle_ar;
                $montant = NomenclatureElement::where('budget_id', $id_bdg)->where('nomenclature_element_id', $clas)
                    ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                    ->select('nomenclature_elements.*', 'budget_details.montant')
                    ->distinct()->get()->first();

                $html .='<table width="100%">
                       <tr>
                            <td>
                                <b>
                                ' . trans('text_me.classe') . ' :
                                ' . $data->code . ' ' . $data->libelle . '
                                </b>
                            </td><td align="center">' . number_format($montant->montant, 2) . '</td>
                            <td align="right">
                                <b>
                                 ' . $data->libelle_ar . ' ' . $data->code . '
                                 </b>
                            </td>
                        </tr>
                        </table>';
                    $html .= $this->showGroupsInPanelvisualiser($clas, $niveau, $array, $id_bdg);
            }
        }
        else
        {
            $html = $this->showGroupsInPanelvisualiser(0, $niveau, $array, $id_bdg);
        }

        return view($this->module . '.tabs.ajax.budgets', ['html' => $html,'budget' =>$budget ]);
    }
    public function visualiserBudget($id){
        $module= Module::find(4);
        $budget = Budget::find($id);
        $classes = NomenclatureElement::where('niveau',1)->get();
        $array = NomenclatureElement::where('budget_id', $id)
            ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
            ->select('nomenclature_elements.*', 'budget_details.montant')
            ->distinct()->get();
           $html=$this->showGroupsInPanelvisualiser(0,1,$array,$id);
        return view($this->module . '.tabs.tab1', ['html' => $html ,'budget' => $budget,'classes' => $classes, 'module' => $module]);
    }

    public function situationBudget($id){
        $module= Module::find(4);
        $budget = Budget::find($id);
        $type=RefTypeNomenclature::all();
        return view($this->module . '.tabs.ajax.situationBudget', ['budget' => $budget,'type' => $type, 'module' => $module]);
    }

    public function suiviExecution($id){
        $module= Module::find(4);
        $nomenclature = NomenclatureElement::find($id);
        $html= '';
        return view($this->module . '.tabs.ajax.suiviExecution', ['nomenclature' => $nomenclature,'module' => $module,'html' => $html]);

    }

    public function getFillsObject($id_groupe,$sence,$date1,$date2,$texte,$res=0)
    {
        $annee=$this->budgetFinalanne();
        $array = $res;
        $elements = NomenclatureElement::where('parent',$id_groupe)->get();
        if($elements->count() > 0)
        {
           /* $array +='id->'.$id_groupe.'code-><br>';*/
            foreach ($elements as $elem)
            {
                $elements_fils = NomenclatureElement::where('parent',$elem->id)->get();
                if($elements_fils->count() > 0)
                    $array = $this->getFillsObject($elem->id,$sence,$date1,$date2,$texte,$array);
                else
                {
                    $montant=0;
                    if ($sence==1){
                        $montants=Recette::where('nomenclature_element_id',$elem->id)->where('date','>=', $date1)->where('date','<=', $date2)->where('description', 'like', '%'.$texte.'%')->get();
                    }
                    if ($sence==2){
                        $montants=Depense::where('nomenclature_element_id',$elem->id)->where('date','>=', $date1)->where('date','<=', $date2)->where('description', 'like', '%'.$texte.'%')->get();
                    }
                    foreach ($montants as $m)
                    {
                        $montant += $m->montant;
                    }
                    if($array != '')
                        $array +=$montant;
                    else
                        $array += $montant;
                }
            }
        }
        else
        {
            $montant=0;
            if ($sence==1){
                $montants=Recette::where('nomenclature_element_id',$id_groupe)->where('date','>=', $date1)->where('date','<=', $date2)->where('description', 'like', '%'.$texte.'%')->get();
            }
            if ($sence==2){
                $montants=Depense::where('nomenclature_element_id',$id_groupe)->where('date','>=', $date1)->where('date','<=', $date2)->where('description', 'like', '%'.$texte.'%')->get();
            }
            foreach ($montants as $m)
            {
                $montant += $m->montant;
            }
            if($array != '')
                $array +=$montant;
            else
                $array +=$montant;
        }
        return $array;
    }
    public function getFillsObjectDetails($id_groupe,$sence,$date1,$date2,$texte,$html1='')
    {
        $annee=$this->budgetFinalanne();
        $html = $html1;
        $elements = NomenclatureElement::where('parent',$id_groupe)->get();
        if($elements->count() > 0)
        {
           /* $array +='id->'.$id_groupe.'code-><br>';*/
            foreach ($elements as $elem)
            {
                $elements_fils = NomenclatureElement::where('parent',$elem->id)->get();
                if($elements_fils->count() > 0)
                    $html= $this->getFillsObjectDetails($elem->id,$sence,$date1,$date2,$texte,$html);
                else
                {
                    $montant=0;
                    if ($sence==1){
                        $montants=Recette::where('nomenclature_element_id',$elem->id)->where('date','>=', $date1)->where('date','<=', $date2)->where('description', 'like', '%'.$texte.'%')->get();
                        foreach ($montants as $m)
                        {
                            $html .='<tr><td>'.Carbon::parse($m->date)->format('d-m-Y').'</td><td>'.$m->description.'</td><td>'.$m->origine.'</td><td>'.$m->nomenclature_element->code.'</td><td>'.number_format((float)$m->montant,2).'</td></tr>';
                        }
                    }
                    if ($sence==2){
                        $montants=Depense::where('nomenclature_element_id',$elem->id)->where('date','>=', $date1)->where('date','<=', $date2)->where('description', 'like', '%'.$texte.'%')->get();
                        foreach ($montants as $m)
                        {
                            $html .='<tr><td>'.Carbon::parse($m->date)->format('d-m-Y').'</td><td>'.$m->description.'</td><td>'.$m->beneficiaire.'</td><td>'.$m->nomenclature_element->code.'</td><td>'.number_format((float)$m->montant,2).'</td></tr>';
                        }
                    }

                   /* if($html != '')
                        $html .=$montant;
                    else
                        $array += $montant;*/
                }
            }
        }
        else
        {
            if ($sence==1){
                $montants=Recette::where('nomenclature_element_id',$id_groupe)->where('date','>=', $date1)->where('date','<=', $date2)->where('description', 'like', '%'.$texte.'%')->get();
                foreach ($montants as $m)
                {
                    $html .='<tr><td>'.Carbon::parse($m->date)->format('d-m-Y').'</td><td>'.$m->description.'</td><td>'.$m->origine.'</td><td>'.$m->nomenclature_element->code.'</td><td>'.number_format((float)$m->montant,2).'</td></tr>';
                }
            }
            if ($sence==2){
                $montants=Depense::where('nomenclature_element_id',$id_groupe)->where('date','>=', $date1)->where('date','<=', $date2)->where('description', 'like', '%'.$texte.'%')->get();
                foreach ($montants as $m)
                {
                    $html .='<tr><td>'.Carbon::parse($m->date)->format('d-m-Y').'</td><td>'.$m->description.'</td><td>'.$m->beneficiaire.'</td><td>'.$m->nomenclature_element->code.'</td><td>'.number_format((float)$m->montant,2).'</td></tr>';
                }
            }

        }
        return $html;
    }

    public function showGroupsInPanel_pdf1($id_groupe)
    {


        /*if ($array != null) {
            if (!$niveau && !$niveau_precedent)
                $html .= "  ";
            foreach ($array as $group) {

                    $html .=";".$group->id;
                    $niveau_precedent = $niveau;
                   // dd($html);
                    $html .= $this->showGroupsInPanel_pdf1($group->id, $group->niveau, $array);


            }
            if (($niveau_precedent == $niveau) && ($niveau_precedent != 0))
               return $html;

        }
        return $html;*/
    }

    public function openOldbudget($annee){
        $module= Module::find(4);
        $budgets = Budget::where('annee', $annee)->orderBy('id', 'DESC')->get()->first();
        $id =$budgets->id;
        $budget = Budget::find($id);
        $classes = NomenclatureElement::where('niveau',1)->get();
        $array = NomenclatureElement::where('budget_id', $id)
            ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
            ->select('nomenclature_elements.*', 'budget_details.montant')
            ->distinct()->get();
           $html=$this->showGroupsInPanelvisualiser(0,1,$array,$id);
        return view($this->module . '.tabs.tab1', ['html' => $html ,'budget' => $budget,'classes' => $classes, 'module' => $module]);
    }

    public function editElementBudget($nomenclature_element_id, $montant, $bidget_id)
    {
        $BudgetDetails =BudgetDetail::where('nomenclature_element_id',$nomenclature_element_id)->where('budget_id',$bidget_id)->get();
        $id =$BudgetDetails->first()->id;
        $bdg = BudgetDetail::find($id);
        $bdg->montant = $montant;
        $bdg->save();
    }

    public function edit(BudgetDetailRequest $request)
    {
        $budget = Budget::find($request->bidget_id);
        $budget->ref_etat_budget_id=1;
        $budget->save();
        $elements = NomenclatureElement::where('budget_id', $request->bidget_id)
            ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
            ->select('nomenclature_elements.*', 'budget_details.montant')
            ->distinct()->get();
        foreach ($elements as $element)
        {
            $montant = $request->input('val_'.$request->bidget_id.''.$element->id.'');
            $montant=str_replace(' ', '', $montant);
            $value = (float)$montant;
            $this->editElementBudget($element->id, $value, $request->bidget_id);
        }
        return response()->json($request->bidget_id, 200);
    }

    public function get($id)
    {
        $budget = Budget::find($id);
        $tablink = $this->module . '/getTab/' . $id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> ' . trans('text.info') => $tablink . '/1',
        ];
        $modal_title = '<b>' . $budget->libelle . '</b>';
        return view('tabs', ['tabs' => $tabs, 'modal_title' => $modal_title]);
    }

    public function getTab($id, $tab)
    {
        $html='';

        switch ($tab) {
            case '1':
                $array = NomenclatureElement::where('budget_id', $id)
                    ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                    ->select('nomenclature_elements.*', 'budget_details.montant')
                    ->distinct()->get();
                $html=$this->showGroupsInPanelvisualiser(0,1,$array,$id);
                $parametres = ['html' => $html];
                break;
            default :
                $parametres = ['html' => $html];
                break;
        }
        return view($this->module . '.tabs.tab' . $tab, $parametres);
    }


    public function delete($id)
    {
        $secteur = Secteur::find($id);
        $secteur->delete();
        return response()->json(['success' => 'true', 'msg' => trans('text.element_well_deleted')], 200);
    }

    public function deleteEcriture($id,$sen)
    {
        if ($sen==1){
            $ecriture = Recette::find($id);
        }
        if ($sen==2){
            $ecriture = Depense::find($id);
        }

        $ecriture->delete();
        return response()->json(['success' => 'true', 'msg' => trans('text.element_well_deleted')], 200);

    }

    public function showElmtsBudgetOperation($parent, $niveau, $array,$id,$sence,$date1,$date2,$texte)
    {
        $lib1=trans('text_me.lib');
        $html = "";
        $niveau_precedent = $niveau;
        $lib = trans("text.libelle_base");
        $lib ='libelle';
        $text_right = trans("text.text_right");
        $pul = trans("text.pul");
        $title_export = trans("text.titre_export");
        $title_st = trans("text.title_st");
        if ($array != null) {
            $html .= '';
            if (!$niveau && !$niveau_precedent)
                $html .= "  ";
            foreach ($array as $group) {
                $style = $this->padding_niveau($group->niveau);
                if ($group->parent == $parent) {
                    if ($niveau_precedent < $niveau)
                        $html .= '';
                    if($this->is_parent($group->id)) {
                        $html .= '<tr>';
                        $html .= '<td colspan="2"'.$style.' > ' . $group->$lib1.'</td>';
                        $html .= '<td align="right">'. number_format((float)$this->getFillsObject($group->id,$sence,$date1,$date2,$texte),2) . '</td>';
                        $html .= '<td '.$style.' align="right">' . $group->montant.'</td>';
                        if($group->montant==0){
                            $html .= '<td colspan=""'.$style.' align="right">' . number_format((float)$this->getFillsObject($group->id,$sence,$date1,$date2,$texte),2).'</td>';
                        }
                        else{
                            $html .= '<td colspan=""'.$style.' align="right">' . number_format((($this->getFillsObject($group->id,$sence,$date1,$date2,$texte)/$group->montant)*100),2).'%</td>';
                        }
                        $html .= '</tr>';
                    }
                    else {
                        $html .= '<tr>';
                        $html .= '<td >';
                        $html .= ''.$group->code;
                        $html .= '</td> ';
                        $html .= '<td >';
                        $html .= '' . $group->$lib1;
                        $html .= '</td> ';
                        $html .= '<td align="right">';
                        $html .= ''.number_format((float)$this->getFillsObject($group->id,$sence,$date1,$date2,$texte),2).'';
                        $html .= '</td> ';
                        $html .= '<td align="right">';
                        $html .= ''.number_format((float)$group->montant,2);
                        $html .= '</td>';
                        if($group->montant==0){
                            $html .= '<td colspan=""'.$style.' align="right">' . number_format((float)$this->getFillsObject($group->id,$sence,$date1,$date2,$texte),2).'</td>';
                        }
                        else{
                            $html .= '<td colspan=""'.$style.' align="right">' . number_format((($this->getFillsObject($group->id,$sence,$date1,$date2,$texte)/$group->montant)*100),2).'%</td>';
                        }
                        $html .= '</tr>';
                    }
                    $niveau_precedent = $niveau;
                    $html .= $this->showElmtsBudgetOperation($group->id, $group->niveau, $array,$id,$sence,$date1,$date2,$texte);
                }
            }

            if (($niveau_precedent == $niveau) && ($niveau_precedent != 0))
                $html .= "";
            elseif ($niveau_precedent == $niveau)
                $html .= "";
            else // last level
                $html .= "";
        }

        return $html;
    }
    public function budgetFinalid(){
        $annee=Budget::where('ref_type_budget_id',3)->max('annee');
        $id=Budget::where('ref_type_budget_id',3)->where('annee',$annee)->get()->first()->id;
        return $id;
    }

    public function budgetFinalanne(){
        $annee=Budget::where('ref_type_budget_id',3)->max('annee');
        return $annee;
    }
    public function testpdf(){
        $conreoller = new EmployeController();
        $enetet = $conreoller->entete(env('APP_COMMUNE'),'titre');
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle('liste des employes');
        PDF::SetSubject('liste des employes');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('L', 'A4');
        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($enetet, true, false, true, false, '');
        // PDF::writeHTML($html_content, true, false, true, false, '');
        PDF::Output(uniqid().'liste_emp.pdf');
    }
    public function exportPDFBudgetFiltre($id_bdg,$niveau,$classe)
    {
        $lib1=trans('text_me.lib');
        $id = env('APP_COMMUNE');
        $commune = Commune::find($id);
        $entete_id = EnteteCommune::where('commune_id', $id)->get()->first()->id;
        $budgets = Budget::find($id_bdg);
        $entete = EnteteCommune::find($entete_id);
        $classe_libelle = $classe_libelle_ar = 'tous';
        $libelle_niveau = $this->libelle_niveau($niveau);
        $htmlg='';
        $colonnes ='<table border="1" width="100%">
                    <thead>
                    <tr bgcolor="#87ceeb">
                    
                        <th width="10%"><b>'.trans("text_me.compte").'</b></th>
                        <th width="30%"><b>'.trans("text_me.libelle").'</b></th>
                        <th align="center" width="20%"><b>'.trans("text_me.montant").'</b></th>
                        <th width="30%" align="right"><b>'.trans("text_me.libelle_ar").' </b></th>
                        <th align="right" width="10%"><b>'.trans("text_me.compte_ar").'</b></th>
                    </tr>
                    </thead><tbody>';
        if ($niveau != 'all') {
            $array = NomenclatureElement::where('budget_id', $id_bdg)->where('niveau', '<=', $niveau)
                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                ->select('nomenclature_elements.*', 'budget_details.montant')
                ->distinct()->get();
        } else {
            $array = NomenclatureElement::where('budget_id', $id_bdg)
                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                ->select('nomenclature_elements.*', 'budget_details.montant')
                ->distinct()->get();
        }
        if ($classe != 0 ) {
            $classes='';
            $exploded = explode(",", trim($classe));
            foreach ($exploded as $class) {
                $data = NomenclatureElement::find($class);
                $classes .=$data->code .',';
            }
            $classes  = rtrim( $classes , ",");
            $htmlg.='<div class="filter" >
                    <table width="100%">
                        <tr><td>'.trans('text_me.filtrage') .':</td><td></td><td align="right">:'. trans('text_me.filtrage') .'</td></tr>
                        <tr>
                            <td>
                                '.trans('text_me.classe') .' :
                                '.$classes.'
                            </td>
                            <td>
                                '. trans('text_me.niveau_affichage') .' :
                                '.$libelle_niveau.'
                                :'. trans('text_me.niveau_affichage_ar') .'
                            </td>
                            <td align="right">
                                '.$classes.'
                            </td>
                        </tr>
                    </table>
                </div><br>';
            $exploded = explode(",", trim($classe));
            foreach ($exploded as $clas) {
                $data = NomenclatureElement::find($clas);
                $classe_libelle = $data->libelle;
                $classe_libelle_ar = $data->libelle_ar;
                $montant = NomenclatureElement::where('budget_id', $id_bdg)->where('nomenclature_element_id', $clas)
                    ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                    ->select('nomenclature_elements.*', 'budget_details.montant')
                    ->distinct()->get()->first();
                $htmlg .= '<table width="100%">
                       <tr>
                            <td>
                                <b>
                                ' . trans('text_me.classe') . ' :
                                ' . $data->code . ' ' . $data->$lib1 . '
                                </b>
                            </td><td align="center">' . number_format($montant->montant, 2) . '</td>
                            <td align="right">
                                <b>
                                 ' . $data->libelle_ar . ' ' . $data->code . '
                                 </b>
                            </td>
                        </tr>
                    </table>';
                if ($niveau != 1) {
                    $htmlg .= $colonnes;
                    $html = $this->showElmtsBudget($clas, $niveau, $array, $id_bdg);
                    $htmlg .= '' . $html.'';
                    $htmlg .= '</tbody></table>';
                }
            }
        }
        else
        {
            if($niveau != 'all'){
                $htmlg.='<div class="filter">
                         <table width="100%">
                            <tr>
                            <td>'.trans('text_me.filtrage') .':</td>
                            <td></td><td align="right">:'. trans('text_me.filtrage') .'</td>
                            </tr>
                            <tr>
                            <td>
                            '.trans('text_me.classe') .' :
                            '.trans('text_me.tous').'
                            </td>
                            <td>
                            '.trans('text_me.niveau_affichage') .':
                            '.$libelle_niveau.'
                            :'. trans('text_me.niveau_affichage_ar') .'
                            </td>
                            <td align="right">
                                '.trans('text_me.tous').'
                            </td>
                        </tr>
                    </table>
                </div>';
            }
            $htmlg.=$colonnes;
            $html = $this->showElmtsBudget($classe, $niveau, $array, $id_bdg);
            $htmlg .=''.$html;
            $htmlg.='</table>';
        }
        $lib = ''.trans("text_me.exercice") .': '.$budgets->annee.'  : '. trans("text_me.exercice_ar");
        $conreoller = new EmployeController();
        $enetet = $conreoller->entete( $lib );
        $html1 ='';
        $html1 .=$enetet;
        $view = \View::make('finances.exports.exportPdfBudget', ['html' => $htmlg, 'commune' => $commune, 'budgets' => $budgets]);
        $html_content = $view->render();
        $html1 .= $html_content ;
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle(''.$lib.'');
        PDF::SetSubject(''.$lib.'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($html1, true, false, true, false, '');
        PDF::Output(uniqid().'budget.pdf');
    }

    public function exportEXCELBudgetFiltre($id_bdg,$niveau,$classe)
    {
        return Excel::download(new ExportBudgets($id_bdg,$niveau,$classe), 'budgets.xlsx');
    }
    public function exporterSuiviExecution($id_nom,$date1,$date2,$sence,$texte,$detail,$niveau)
    {
        $lib=trans('text_me.lib');
        $id = env('APP_COMMUNE');
        $commune = Commune::find($id);
        $entete_id = EnteteCommune::where('commune_id', $id)->get()->first()->id;
        $entete = EnteteCommune::find($entete_id);
        $html = '';
        //non detaille
        if ($detail == 0) {
            $colonnes = '<table border="1" width="100%">
                    <tr bgcolor="#add8e6">
                        <td width="10%"><b>' . trans("text_me.compte") . '</b></td>
                        <td width="30%"><b>' . trans("text_me.libelle") . '</b></td>';
            if ($sence == 1) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.recettes") . '</b></td>';
            }
            if ($sence == 2) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.depences") . '</b></td>';
            }
            $colonnes .= '<td width="30%" align="right"><b>' . trans("text_me.budget") . ' </b></td>
                        <td align="right" width="10%"><b>' . trans("text_me.TauxExecution") . '</b></td>
                    </tr>';
            $html .= $colonnes;
        }
        if($detail == 1){
            $colonnes = '<table border="1" width="100%" class="normal">
                    <tr bgcolor="#add8e6">
                        <td width="15%"><b>' . trans("text_me.date") . '</b></td>
                        <td width="30%"><b>' . trans("text_me.libelle") . '</b></td>';
            if ($sence == 1) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.origine") . '</b></td>';
            }
            if ($sence == 2) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.beneficiare") . '</b></td>';
            }
            $colonnes .= '<td width="20%" align=""><b>' . trans("text_me.compte_impitation") . ' </b></td>
                        <td align="" width="15%"><b>' . trans("text_me.montant") . '</b></td>
                    </tr>';
            $html .= $colonnes;
        }

        $annee = $this->budgetFinalanne();
        $id_budget = $this->budgetFinalid();

        $conreoller = new EmployeController();
        $titre = '';
        if ($sence == 1) {
            $titre = trans("text_me.situation_recette");
        }
        if ($sence == 2) {
            $titre = trans("text_me.situation_depence");
        }
        if($detail==1){
            $titre =' '. trans("text_me.detailleRecettes");
        }
        $enetet = $conreoller->entete($titre);
        $html1 = $enetet;
        $element = NomenclatureElement::find($id_nom);
        $totalBudget = BudgetDetail::where('budget_id', $id_budget)->where('nomenclature_element_id', $id_nom)->get()->first()->montant;
        $html1 .= '<br>&nbsp;&nbsp;&nbsp;' . trans('text_me.compte_impitation') . ':<b> ' . $element->libelle . '</b><br>';
        $html1 .= '';
        if ($niveau == 'all' and $detail==0) {
            $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.niveau_affichage') . ':<b>' . trans('text_me.tous') . '</b>';
        }
        else {
            if ($detail==0)
                $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.niveau_affichage') . ':<b>' . $niveau . '</b>';
        }
        if ($texte == 'all') {
            $texte = '';
            $html1 .= '<br>';
        } else {
            $html1 .= '&nbsp;&nbsp;' . trans('text_me.beneficiaire') . ':</b>' . $texte . '</b>';
            $html1 .= '<br>';
        }
        $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.periode') . ' ' . trans('text_me.Du') . ': <b>' . Carbon::parse($date1)->format('d-m-Y') . '</b> &nbsp;&nbsp;' . trans('text_me.Au') . ':  <b>' . Carbon::parse($date2)->format('d-m-Y') . '</b><br>';
        $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.budget') . ':  <b>' . number_format((float)$totalBudget,2) . '</b>  &nbsp;&nbsp;';
        if ($sence == 1) {
            $html1 .= '' . trans('text_me.recette');
        }
        if ($sence == 2) {
            $html1 .= '' . trans('text_me.depence');
        }

        $html1 .= ': <b>' . number_format((float)$this->getFillsObject($id_nom, $sence, $date1, $date2, $texte),2) . '</b><br>';
        $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.TauxExecution') . ': <b> &nbsp;' . number_format((($this->getFillsObject($id_nom, $sence, $date1, $date2, $texte) / $totalBudget) * 100), 2) . '%</b> <br><br><br>';
        //details recettes
        if ($detail == 1){
            $html .=$this->getFillsObjectDetails($id_nom, $sence, $date1, $date2, $texte);
        }
        //non details
        if ($detail == 0){
            $html .= '<tr><td colspan="2">' . $element->code . '/' . $element->$lib . '</td> <td  align="right">' . number_format((float)$this->getFillsObject($id_nom, $sence, $date1, $date2, $texte) ,2). '</td> <td  align="right">' . number_format((float)$totalBudget,2) . '</td><td align="right">' . number_format((($this->getFillsObject($id_nom, $sence, $date1, $date2, $texte) / $totalBudget) * 100), 2) . '%</td></tr>';
            $elements = NomenclatureElement::where('parent', $id_nom)->get();
        if ($niveau != 'all') {
            $array = NomenclatureElement::where('budget_id', $id_budget)->where('niveau', '<=', $niveau)
                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                ->select('nomenclature_elements.*', 'budget_details.montant')
                ->distinct()->get();
        } else {
            $array = NomenclatureElement::where('budget_id', $id_budget)
                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                ->select('nomenclature_elements.*', 'budget_details.montant')
                ->distinct()->get();
        }
        if ($elements->count() > 0) {
            $html .= $this->showElmtsBudgetOperation($id_nom, $niveau = 'all', $array, $id_budget, $sence, $date1, $date2, $texte);
        }
    }
        $html.='</table>';
        $view = \View::make('finances.exports.suiviExecution', ['html' => $html, 'commune' => $commune]);
        $html_content = $view->render();
        $html1 .= $html_content ;
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle(''.$titre.'');
        PDF::SetSubject(''.$titre.'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($html1, true, false, true, false, '');
        PDF::Output(uniqid().'budget.pdf');
    }
    public function exporterSuiviExecutionExcel($id_nom,$date1,$date2,$sence,$texte,$detail,$niveau)
        {
            $titre = '';
            if ($sence == 1) {
                $titre = trans("text_me.situation_recette");
            }
            if ($sence == 2) {
                $titre = trans("text_me.situation_depence");
            }
            if($detail==1){
                $titre =' '. trans("text_me.detailleRecettes");
            }
            return Excel::download(new SuiviExecutionParniveau($id_nom,$date1,$date2,$sence,$texte,$detail,$niveau), ''.$titre.'.xlsx');

        }

    public function exporterSituationBudgetaire($id_nom,$date1,$date2,$type,$detail)
    {
        $detail=0;
        $id = env('APP_COMMUNE');
        $commune = Commune::find($id);
        $entete_id = EnteteCommune::where('commune_id', $id)->get()->first()->id;
        $entete = EnteteCommune::find($entete_id);
        $html = '';

       /* if($detail == 1){
            $colonnes = '<table border="1" width="100%" class="normal">
                    <tr bgcolor="#add8e6">
                        <td width="15%"><b>' . trans("text_me.date") . '</b></td>
                        <td width="30%"><b>' . trans("text_me.libelle") . '</b></td>';
            if ($type == 1) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.origine") . '</b></td>';
            }
            if ($type == 2) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.beneficiare") . '</b></td>';
            }
            $colonnes .= '<td width="20%" align=""><b>' . trans("text_me.compte_impitation") . ' </b></td>
                        <td align="" width="15%"><b>' . trans("text_me.montant") . '</b></td>
                    </tr>';
            $html .= $colonnes;
        }*/

        $annee = $this->budgetFinalanne();
        $id_budget = $this->budgetFinalid();

        $conreoller = new EmployeController();
        $titre = '';
        if ($type == 1) {
            $titre = trans("text_me.situation_recette");
        }
        if ($type == 2) {
            $titre = trans("text_me.situation_depence");
        }
        if($detail==1){
            $titre =' '. trans("text_me.detailleRecettes");
        }
        $enetet = $conreoller->entete($titre.' '.$annee);
        $html1 = $enetet;
        //methode
        if ($type==1){
            $classes=NomenclatureElement::where('parent',0)->where('ref_type_nomenclature_id',1)->get();
            foreach ($classes as $cl){
                $html.='<br>'.$this->showElmtsBudget2Nchois($cl->id, $type, $date1, $date2,$detail);
            }
        }
        if ($type==2){
            $classes=NomenclatureElement::where('parent',0)->where('ref_type_nomenclature_id',2)->get();
            foreach ($classes as $cl){
                //dd();
                $html.=$this->showElmtsBudget2Nchois($cl->id, $type, $date1, $date2,$detail);
            }
        }
        $view = \View::make('finances.exports.suiviExecution', ['html' => $html, 'commune' => $commune]);
        $html_content = $view->render();
        $html1 .= $html_content ;
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle(''.$titre.'');
        PDF::SetSubject(''.$titre.'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($html1, true, false, true, false, '');
        PDF::Output(uniqid().'budget.pdf');
    }
    public function exporterSituationBudgetaireExcel($id_nom,$date1,$date2,$type,$detail)
        {
            $titre = '';
            if ($type == 1) {
                $titre = trans("text_me.situation_recette");
            }
            if ($type == 2) {
                $titre = trans("text_me.situation_depence");
            }
            if($detail==1){
                $titre =' '. trans("text_me.detailleRecettes");
            }
            return Excel::download(new SuiviSituationBgd($id_nom,$date1,$date2,$type,$detail), ''.$titre.'.xlsx');
        }

    function showElmtsBudget2Nchois($id_nom, $type, $date1, $date2,$detail){
        $html1=$html='';
        $lib=trans('text_me.lib');
        //non detaille
        if ($detail == 0) {
            $colonnes = '<table border="1" width="100%">
                    <tr bgcolor="#add8e6">
                        <td width="10%"><b>' . trans("text_me.compte") . '</b></td>
                        <td width="30%"><b>' . trans("text_me.libelle") . '</b></td>';
            if ($type == 1) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.recettes") . '</b></td>';
            }
            if ($type == 2) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.depences") . '</b></td>';
            }
            $colonnes .= '<td width="30%" align="right"><b>' . trans("text_me.budget") . ' </b></td>
                        <td align="right" width="10%"><b>' . trans("text_me.TauxExecution") . '</b></td>
                    </tr>';
            $html .= $colonnes;
        }
        $annee = $this->budgetFinalanne();
        $id_budget = $this->budgetFinalid();
        $element = NomenclatureElement::find($id_nom);
        $totalBudget = BudgetDetail::where('budget_id', $id_budget)->where('nomenclature_element_id', $id_nom)->get()->first()->montant;
        $html1 .= '<br>&nbsp;&nbsp;&nbsp;' . trans('text_me.compte_impitation') . ':<b> ' . $element->$lib . '</b><br>';
        $html1 .= '';
        $niveau='all';
        if ($niveau == 'all' and $detail==0) {
            $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.niveau_affichage') . ':<b>' . trans('text_me.tous') . '</b>';
        }
        else {
            if ($detail==0)
                $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.niveau_affichage') . ':<b>' . $niveau . '</b>';
        }
        $texte = 'all';
        if ($texte == 'all') {
            $texte = '';
            $html1 .= '<br>';
        } else {
            $html1 .= '&nbsp;&nbsp;' . trans('text_me.beneficiaire') . ':</b>' . $texte . '</b>';
            $html1 .= '<br>';
        }
        $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.periode') . ' ' . trans('text_me.Du') . ': <b>' . Carbon::parse($date1)->format('d-m-Y') . '</b> &nbsp;&nbsp;' . trans('text_me.Au') . ':  <b>' . Carbon::parse($date2)->format('d-m-Y') . '</b><br>';
        $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.budget') . ':  <b>' . $totalBudget . '</b>  &nbsp;&nbsp;';
        if ($type == 1) {
            $html1 .= '' . trans('text_me.recette');
        }
        if ($type == 2) {
            $html1 .= '' . trans('text_me.depence');
        }
        $id_nom=1;
        $html1 .= ': <b>' . $this->getFillsObject($id_nom, $type, $date1, $date2, $texte) . '</b><br>';
        $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.TauxExecution') . ': <b> &nbsp;' . number_format((((float)$this->getFillsObject($id_nom, $type, $date1, $date2, $texte) / 1) * 100), 2) . '%</b> <br><br><br>';
        //details recettes
        if ($detail == 1){
            $html .=$this->getFillsObjectDetails($id_nom, $type, $date1, $date2, $texte);
        }
        //non details
        if ($detail == 0){
            $html .= '<tr><td colspan="2">' . $element->code . '/' . $element->$lib . '</td> <td  align="right">' . number_format((float)$this->getFillsObject($id_nom, $type, $date1, $date2, $texte),2) . '</td> <td  align="right">' . number_format((float)$totalBudget ,2). '</td><td align="right">' . number_format((($this->getFillsObject($id_nom, $type, $date1, $date2, $texte) / 1) * 100), 2) . '%</td></tr>';
            $elements = NomenclatureElement::where('parent', $id_nom)->get();
            if ($niveau != 'all') {
                $array = NomenclatureElement::where('budget_id', $id_budget)->where('niveau', '<=', $niveau)
                    ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                    ->select('nomenclature_elements.*', 'budget_details.montant')
                    ->distinct()->get();
            }
            else {
                $array = NomenclatureElement::where('budget_id', $id_budget)
                    ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                    ->select('nomenclature_elements.*', 'budget_details.montant')
                    ->distinct()->get();
            }
            if ($elements->count() > 0) {
                $html .= $this->showElmtsBudgetOperation($id_nom, $niveau = 'all', $array, $id_budget, $type, $date1, $date2, $texte);
            }
        }
        $html.='</table>';
        return $html;
    }

    public function is_parent($id)
    {
        $res=false;
        $set_parent = NomenclatureElement::where('parent',$id)->get();
        if(count($set_parent) > 0)
            $res=true;
        return $res;
    }

    public function libelle_niveau($niveau)
    {
        $libelle='';
        switch ($niveau)
        {
            case 1:
                $libelle="Classes";
                break;
            case 2:
                $libelle="Chapitres";
                break;
            case 3:
                $libelle="Articles";
                break;
            case 4:
                $libelle="Paragraphes";
                break;

            case 5:
                $libelle="Sous paragraphes";
                break;
            default :
                $libelle="tous";
        }
        return $libelle;
    }

    public function padding_niveau($niveau)
    {
        $style='';
        switch ($niveau)
        {
            case 1:
                $style="style='padding-left:10px'";
                break;
            case 2:
                $style="style='padding-left:20px'";
                break;
            case 3:
                $style="style='padding-left:30px'";
                break;
            case 4:
                $style="style='padding-left:40px'";
                break;

            case 5:
                $style="style='padding-left:50px'";
                break;
        }
        return $style;
    }

    public function showGroupsInPanel($parent, $niveau, $array,$id)
    {
        /**
         * name        : showGroupsInPanel
         * parametres  : Parent, niveau, liste des groupes, id d'un groupe
         * return      : Html
         * Description : Permet de retourner un HTML contenant la liste des groupes du parent format treeview dans un panle
         */
        $html = "";
        $niveau_precedent = 0;
        $lib = trans("text.libelle_base");
        $lib ='libelle';
        $text_right = trans("text.text_right");
        $pul = trans("text.pul");
        $lib=trans('text_me.lib');
        $title_export = trans("text.titre_export");
        $title_st = trans("text.title_st");
        if ($array != null) {
            $html .= '';
            if (!$niveau && !$niveau_precedent)
                $html .= "  ";
            foreach ($array as $group) {
                $style = $this->padding_niveau($group->niveau);
                // dd($style);
                if ($group->parent == $parent) {
                    if ($niveau_precedent < $niveau)
                        $html .= '';
                    if($this->is_parent($group->id)) {
                        $html .= '<div  class="card p-0 m-0">';
                        $html .= '<div  class="card-header p-0 m-0  bg-transparent" id="heading' . $id . '' . $group->id . '" >';
                        $html .= '<div  class="form-row" '.$style.'>';
                        $html .= '<div  class="col-md-10 ">';
                        $html .= '<button  type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse' .$id . '' . $group->id . '">';
                        $html .= '<i  class="fa fa-plus"></i>' . $group->$lib;
                        $html .= '</button> ';
                        $html .= '</div> ';
                        $html .= '<div  class="col-md-2 float-label-control m-0 pr-3 text-right" id="'.$id.''.$group->id.'">';//number_format($group->montant, 2)
                        $html .= '<input type="text" class="form-control border-0 bg-transparent" readonly id_parent ="'.$group->parent.'" value="'. $group->montant . '" name="val_'.$id.''.$group->id.'"  id="val_'.$id.''.$group->id.'" style="text-align: right;" data-inputmask="\'alias\': \'numeric\', \'groupSeparator\': \' \', \'autoGroup\': true, \'digits\': 0, \'digitsOptional\': false, \'prefix\': \'\', \'placeholder\': \'0\'"/>';
                        $html .= '</div> ';
                        $html .= '</div> ';
                        $html .= '</div> ';
                        $html .= '<div id="collapse'.$id .''.$group->id.'" class="collapse" aria-labelledby="heading'.$id.''.$group->id.'" data-parent="#collapse'.$id.''.$group->id . '">';
                        $html .= '<div  class="card-body p-0 m-0">';
                    }
                    else {
                        $html .= '<div  class="card p-0 m-0" >';
                        $html .= '<div '.$style.'>';
                        $html .= '<div class="form-row  " >';
                        $html .= '<div  class="padding-left-niveau3 col-md-10 m-0">';
                        $html .= ' '.$group->code.'  ' . $group->$lib;
                        $html .= '</div> ';
                        /*$html .= '<div class="col-md-2  pr-2  text-right">
                        <button type="button" class="btn btn-link text-warning" onclick="historiqueEcriture('.$group->id.')" title="'.trans('text_me.suivre').'/'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button>
                        <button  type="button" class="btn btn-link" onclick="addEcriture('.$group->id.')" title="'.trans('text_me.newEcriture').'"><i  class="fa fa-fw fa-plus"></i></button>
                        </div> ';*/
                        $html .= '<div class="col-md-2 float-label-control m-0 pr-3  text-right">';
                        $html .= '
                        <input type="text" id_parent ="'.$group->parent.'"  class="form-control" value="'.$group->montant.'" name="val_'.$id.''.$group->id.'" id="val_'.$id .''.$group->id.'" style="text-align: right;" onchange="calculrecursive('.$group->id.','.$id.')" data-inputmask="\'alias\': \'numeric\', \'groupSeparator\': \' \', \'autoGroup\': true, \'digits\': 0, \'digitsOptional\': false, \'prefix\': \'\', \'placeholder\': \'0\'"/>';
                        $html .= '</div> ';
                    }
                    $niveau_precedent = $niveau;
                    $html .= $this->showGroupsInPanel($group->id, $group->niveau, $array,$id);
                }
            }

            if (($niveau_precedent == $niveau) && ($niveau_precedent != 0))
                $html .= "</div></div></div>";
            elseif ($niveau_precedent == $niveau)
                $html .= "";
            else // last level
                $html .= "</div></div></div>";
        }

        return $html;
    }

    public function showGroupsInPanelvisualiser($parent, $niveau, $array,$id)
    {

        $html = "";
        $niveau_precedent = 0;
        $lib = trans("text.libelle_base");
        $lib ='libelle';
        $lib1=trans('text_me.lib');
        $text_right = trans("text.text_right");
        $pul = trans("text.pul");
        $title_export = trans("text.titre_export");
        $title_st = trans("text.title_st");
        if ($array != null) {
            $html .= '';
            if (!$niveau && !$niveau_precedent)
                $html .= "  ";
            foreach ($array as $group) {
                $style = $this->padding_niveau($group->niveau);
                // dd($style);
                if ($group->parent == $parent) {
                    if ($niveau_precedent < $niveau)
                        $html .= '';
                    if($this->is_parent($group->id)) {
                        $html .= '<div  class="card p-0 m-0">';
                        $html .= '<div  class="card-header p-0 m-0  bg-transparent" id="headingv' . $id . '' . $group->id . '" >';
                        $html .= '<div  class="form-row" ' . $style . '>';
                        $html .= '<div  class="col-md-9">';
                        $html .= '<button  type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapsev' . $id . '' . $group->id . '">';
                        $html .= '<i  class="fa fa-plus"></i> ' . $group->$lib1;
                        $html .= '</button> ';
                        $html .= '</div> ';
                        $html .= '<div  class="col-md-1 text-center">';
                        $budg = Budget::find($id)->ref_type_budget_id;
                        if ($budg == 3) {
                            if(Auth::user()->hasAccess(4,4)) {
                                $html .= '<button type="button" class="btn btn-sm btn-warning" onClick="suiviExecution(' . $group->id . ')" data-toggle="tooltip" data-placement="top" title="' . trans('text_me.suivre') . ' ' . $group->libelle . '"><i class="fas fa-fw fa-clipboard-list"></i></button>';
                            }
                        }
                        $html .= '</div>';
                        $html .= '<div  class="col-md-2 float-label-control m-0 pr-3 text-right" >';//
                        $html .= '<input type="text" class="form-control border-0 bg-transparent" readonly  value="'. $group->montant . '" style="text-align: right;" data-inputmask="\'alias\': \'numeric\', \'groupSeparator\': \' \', \'autoGroup\': true, \'digits\': 0, \'digitsOptional\': false, \'prefix\': \'\', \'placeholder\': \'0\'"/>';
                        $html .= '</div> ';
                        $html .= '</div> ';
                        $html .= '</div> ';
                        $html .= '<div  id="collapsev'.$id .''.$group->id.'" class="collapse" aria-labelledby="headingv'.$id.''.$group->id.'" data-parent="#collapsev'.$id.''.$group->id . '">';
                        $html .= '<div  class="card-body p-0 m-0">';
                    }
                    else {
                        $html .= '<div  class="card p-0 m-0" >';
                        $html .= '<div '.$style.'>';
                        $html .= '<div class="form-row  " >';
                        $html .= '<div  class="padding-left-niveau3 col-md-8 m-0">';
                        $html .= ''.$group->code.'  ' . $group->$lib1;
                        $html .= '</div> ';
                        $html .= '<div class="col-md-2  pr-2  text-right">';
                        $budg = Budget::find($id)->ref_type_budget_id;
                        if ($budg == 3) {

                                $html .= '<button type="button" class="btn btn-link text-warning" onclick="historiqueEcriture(' . $group->id . ')" title="' . trans('text_me.suivre') . ' ' . $group->$lib1 . '"><i class="fa fa-fw fa-eye"></i></button> ';
                            if(Auth::user()->hasAccess(4,2)) {
                                $html .= '<button  type="button" class="btn btn-link" onclick="addEcriture(' . $group->id . ')" title="' . trans('text_me.newEcriture') . '"><i  class="fa fa-fw fa-plus"></i></button>';
                            }
                        }
                            $html .=' </div> ';
                        $html .= '<div  class="col-md-2 float-label-control m-0 pr-3 text-right">';
                        $html .= '<input type="text"  readonly class="form-control border-0  bg-transparent" value="'.$group->montant.'"  style="text-align: right;" data-inputmask="\'alias\': \'numeric\', \'groupSeparator\': \' \', \'autoGroup\': true, \'digits\': 0, \'digitsOptional\': false, \'prefix\': \'\', \'placeholder\': \'0\'" />';
                        $html .= '</div> ';
                    }
                    $niveau_precedent = $niveau;
                    $html .= $this->showGroupsInPanelvisualiser($group->id, $group->niveau, $array,$id);
                }
            }

            if (($niveau_precedent == $niveau) && ($niveau_precedent != 0))
                $html .= "</div></div></div>";
            elseif ($niveau_precedent == $niveau)
                $html .= "";
            else // last level
                $html .= "</div></div></div>";
        }

        return $html;
    }

    public function showElmtsBudget($parent, $niveau, $array,$id)
    {
        $html = "";
        $niveau_precedent = $niveau;
        $lib = trans("text.libelle_base");
        $lib ='libelle';
        $lib1=trans('text_me.lib');
        $text_right = trans("text.text_right");
        $pul = trans("text.pul");
        $title_export = trans("text.titre_export");
        $title_st = trans("text.title_st");
        if ($array != null) {
            $html .= '';
            if (!$niveau && !$niveau_precedent)
                $html .= "  ";
            foreach ($array as $group) {
                $style = $this->padding_niveau($group->niveau);
                if ($group->parent == $parent) {
                    if ($niveau_precedent < $niveau)
                        $html .= '';
                    if($this->is_parent($group->id)) {
                        $html .= '<tr>';
                        $html .= '<td colspan="2"'.$style.' > ' . $group->libelle.'</td>';
                        $html .= '<td align="right">'. number_format($group->montant, 2) . '</td>';
                        $html .= '<td colspan="2"'.$style.' align="right">' . $group->libelle_ar.'</td>';
                        $html .= '</tr>';
                    }
                    else {
                        $html .= '<tr>';
                        $html .= '<td >';
                        $html .= ''.$group->code;
                        $html .= '</td> ';
                        $html .= '<td >';
                        $html .= '' . $group->libelle;
                        $html .= '</td> ';
                        $html .= '<td align="right">';
                        $html .= ''.number_format($group->montant, 2).'';
                        $html .= '</td> ';
                        $html .= '<td align="right">';
                        $html .= ''.$group->libelle_ar;
                        $html .= '</td> ';
                        $html .= '<td align="right">';
                        $html .= '' .$group->code ;
                        $html .= '</td> ';
                        $html .= '</tr>';
                    }
                    $niveau_precedent = $niveau;
                    $html .= $this->showElmtsBudget($group->id, $group->niveau, $array,$id);
                }
            }

            if (($niveau_precedent == $niveau) && ($niveau_precedent != 0))
                $html .= "";
            elseif ($niveau_precedent == $niveau)
                $html .= "";
            else // last level
                $html .= "";
        }

        return $html;
    }

    public function showElmtsExecution($parent, $niveau, $array,$id)
    {
        $html = "";
        $niveau_precedent = $niveau;
        $lib = trans("text.libelle_base");
        $lib ='libelle';
        $text_right = trans("text.text_right");
        $pul = trans("text.pul");
        $title_export = trans("text.titre_export");
        $title_st = trans("text.title_st");
        if ($array != null) {
            $html .= '';
            if (!$niveau && !$niveau_precedent)
                $html .= "  ";
            foreach ($array as $group) {
                $style = $this->padding_niveau($group->niveau);
                if ($group->parent == $parent) {
                    if ($niveau_precedent < $niveau)
                        $html .= '';
                    if($this->is_parent($group->id)) {
                        $html .= '<tr>';
                        $html .= '<td colspan="2"'.$style.' > ' . $group->libelle.'</td>';
                        $html .= '<td align="right">'. number_format($group->montant, 2) . '</td>';
                        $html .= '<td colspan="2"'.$style.' align="right">' . $group->libelle_ar.'</td>';
                        $html .= '</tr>';
                    }
                    else {
                        $html .= '<tr>';
                        $html .= '<td >';
                        $html .= ''.$group->code;
                        $html .= '</td> ';
                        $html .= '<td >';
                        $html .= '' . $group->libelle;
                        $html .= '</td> ';
                        $html .= '<td align="right">';
                        $html .= ''.number_format($group->montant, 2).'';
                        $html .= '</td> ';
                        $html .= '<td align="right">';
                        $html .= ''.$group->libelle_ar;
                        $html .= '</td> ';
                        $html .= '<td align="right">';
                        $html .= '' .$group->code ;
                        $html .= '</td> ';
                        $html .= '</tr>';
                    }
                    $niveau_precedent = $niveau;
                    $html .= $this->showElmtsExecution($group->id, $group->niveau, $array,$id);
                }
            }

            if (($niveau_precedent == $niveau) && ($niveau_precedent != 0))
                $html .= "";
            elseif ($niveau_precedent == $niveau)
                $html .= "";
            else // last level
                $html .= "";
        }

        return $html;
    }

    function showElementBudget($id)
    {
        $html = '';
        $classes = NomenclatureElement::where('niveau', 1)->where('budget_id', $id)
            ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
            ->select('nomenclature_elements.*', 'budget_details.montant')
            ->distinct()->get();
        foreach ($classes as $classe) {
            $html .= '<div  class="card p-0 m-0" >';
            $html .= '<div  class="card-header p-0 m-0 bg-transparent" id="heading' . $classe->id . '">';
            $html .= '<div  class="form-row p-0 m-0" >';
            $html .= '<div  class="col-md-10 p-0 m-0">';
            $html .= '<button  type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse' . $classe->id . '">';
            $html .= '<i class="fa fa-plus"></i>'. $classe->libelle;
            $html .= '</button> ';
            $html .= '</div> ';
            $html .= '<div class="col-md-2 float-label-control text-right p-0 m-0 pr-2">';
            $html .= '' . $classe->montant . '';
            $html .= '</div> ';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div id="collapse' . $classe->id . '" class="collapse p-0 m-0" aria-labelledby="heading' . $classe->id . '" data-parent="#collapse' . $classe->id . '" >';
            $html .= '<div  class="card-body p-0 m-0">';
            $chapitres = NomenclatureElement::where('niveau', 2)->where('budget_id', $id)->where('parent', $classe->id)
                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                ->select('nomenclature_elements.*', 'budget_details.montant')
                ->distinct()->get();
            foreach ($chapitres as $chapitre) {
                $html .= '<div  class="card p-0 m-0">';
                $html .= '<div  class="card-header p-0 m-0 pl-2 pr-2 bg-transparent" id="heading' . $chapitre->id . '">';
                $html .= '<div  class="form-row">';
                $html .= '<div  class="col-md-10 ">';
                $html .= '<button  type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse' . $chapitre->id . '">';
                $html .= '<i  class="fa fa-plus"></i>' . $chapitre->libelle;
                $html .= '</button> ';
                $html .= '</div> ';
                $html .= '<div  class="col-md-2 text-right">';
                $html .= '' . $chapitre->montant;
                $html .= '</div> ';
                $html .= '</div> ';
                $html .= '</div> ';
                $html .= '<div  id="collapse' . $chapitre->id . '" class="collapse" aria-labelledby="heading' . $chapitre->id . '" data-parent="#collapse' . $chapitre->id . '">';
                $html .= '<div  class="card-body p-0 m-0">';
                $articles = NomenclatureElement::where('niveau', 3)->where('parent', $chapitre->id)->where('budget_id', $id)
                    ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                    ->select('nomenclature_elements.*', 'budget_details.montant')
                    ->get();
                foreach ($articles as $article) {
                    $html .= '<div class="card-body p-0 m-0">';
                    $paragraphes = NomenclatureElement::where('niveau', 4)->where('parent', $article->id)->where('budget_id', $id)
                        ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                        ->select('nomenclature_elements.*', 'budget_details.montant')
                        ->get();

                    if($paragraphes->count() > 0){
                        $html .='<div  class="card-header padding-left-niveau3 m-0 bg-transparent"  id="heading'.$article->id.'">';
                        $html .='<div  class="form-row">';
                        $html .='<div  class="col-md-10">';
                        $html .='<button  type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse'.$article->id.'">';
                        $html .='<i   class="fa fa-plus"></i> '.$article->code.' ' . $article->libelle;
                        $html .='</button> ';
                        $html .='</div> ';
                        $html .='<div  class="col-md-2 text-right">';
                        $html .=''.$article->montant;
                        $html .='</div> ';
                        $html .='</div> ';
                        $html .='</div> ';
                        $html .='<div  id="collapse'.$article->id.'" class="collapse" aria-labelledby="heading'.$article->id.'" data-parent="#collapse'.$article->id.'">';
                        $html .='<div  class="card-body p-0 m-0">';
                        $paragraphes = NomenclatureElement::where('niveau', 4)->where('parent', $article->id)->where('budget_id', $id)
                            ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                            ->select('nomenclature_elements.*', 'budget_details.montant')
                            ->get();
                        foreach ($paragraphes as $paragraphe) {
                            // $html .= '<div class="card-body p-0 m-0">';
                            $niveau5 = NomenclatureElement::where('niveau', 5)->where('parent', $paragraphe->id)->where('budget_id', $id)
                                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                                ->select('nomenclature_elements.*', 'budget_details.montant')
                                ->get();
                            if ($niveau5->count() > 0){
                                $html .= '<div class="card-body p-0 m-0">';
                                $html .='<div  class="card-header padding-left-niveau4 m-0 bg-transparent"  id="heading'.$paragraphe->id.'">';
                                $html .='<div  class="form-row">';
                                $html .='<div  class="col-md-10">';
                                $html .='<button  type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse'.$paragraphe->id.'">';
                                $html .='<i   class="fa fa-plus"></i> '.$paragraphe->code.'  ' . $paragraphe->libelle;
                                $html .='</button> ';
                                $html .='</div> ';
                                $html .='<div  class="col-md-2 text-right">';
                                $html .=''.$paragraphe->montant;
                                $html .='</div> ';
                                $html .='</div> ';
                                $html .='</div> ';
                                $html .='<div  id="collapse'.$paragraphe->id.'" class="collapse" aria-labelledby="heading'.$paragraphe->id.'" data-parent="#collapse'.$article->id.'">';
                                $html .='<div  class="card-body p-0 m-0">';
                                $niveau5 = NomenclatureElement::where('niveau', 5)->where('parent', $paragraphe->id)->where('budget_id', $id)
                                    ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                                    ->select('nomenclature_elements.*', 'budget_details.montant')
                                    ->get();
                                foreach ($niveau5 as $niveau){
                                    $html .= '<div class="form-row padding-left-niveau5 m-0" >';
                                    $html .= '<div  class="col-md-10 m-0 padding-left-niveau5">';
                                    $html .= '' . $niveau->code . ' ' . $niveau->libelle;
                                    $html .= '</div> ';
                                    $html .= '<div  class="col-md-2 float-label-control m-0 pr-1 text-right">';
                                    $html .= '<input type="number" class="form-control" value="' . $niveau->montant . '" name="article' . $niveau->id . '" style="text-align: right;"/>';
                                    $html .= '</div> ';
                                    $html .= '</div>';
                                }
                                $html .='</div> ';
                                $html .='</div> ';
                                $html .='</div> ';
                            }
                            else{
                                $html .= '<div class="form-row padding-left-niveau4 m-0">';
                                $html .= '<div  class="col-md-10 padding-left-niveau4 m-0 ">';
                                $html .= '' . $paragraphe->code . ' ' . $paragraphe->libelle;
                                $html .= '</div> ';
                                $html .= '<div  class="col-md-2 float-label-control  m-0 pr-1 text-right">';
                                $html .= '<input type="" class="form-control" readonly  value="' . $paragraphe->montant . '" name="article' . $paragraphe->id . '" style="text-align: right;" readonly />';
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            // $html ='</div>';
                        }
                        $html .='</div> ';
                        $html .='</div> ';
                    }
                    else{
                        $html .= '<div class="form-row  " >';
                        $html .= '<div  class="padding-left-niveau3 col-md-10 m-0">';
                        $html .= ''.$article->code.'  ' . $article->libelle;
                        $html .= '</div> ';
                        $html .= '<div  class="col-md-2 float-label-control m-0 pr-1 text-right">';
                        $html .= '<input type="number" class="form-control" value="'.$article->montant . '" name="article'.$article->id.'" style="text-align: right;"/>';
                        $html .= '</div> ';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                }
                $html .= '</div> ';
                $html .= '</div> ';
                $html .= '</div>';
            }
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }
}
