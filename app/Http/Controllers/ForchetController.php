<?php
namespace App\Http\Controllers;

use App\Http\Requests\ForchetRequest;
use App\Http\Requests\ActiviteRequest;
use App\Models\RefTypesFamille;;
use App\Models\RefCategorieActivite;
use App\Models\Activite;
use App\Models\ForchetteTax;

use DataTables;
use App;
//use Auth;

class ForchetController extends Controller
{
    private $module = 'forchets';

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        return view($this->module.'.index');
    }

    public function getDT($selected='all')
    {
        $forchets = ForchetteTax::with('ref_categorie_activite','ref_emplacement_activite','ref_taille_activite');
        if ($selected != 'all')
            $forchets = $forchets->orderByRaw('id = ? desc', [$selected])->with('ref_categorie_activite','ref_emplacement_activite','ref_taille_activite');
        return DataTables::of($forchets)
            ->addColumn('actions', function(ForchetteTax $forchets) {
                $html = '<div class="btn-group">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$forchets->id.',\''.$this->module.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url($this->module.'/delete/'.$forchets->id).'\',\''.trans('text.confirm_suppression').''.$forchets->libelle.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.supprimer').'"><i class="fas fa-trash"></i></button> ';
                $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($forchets) use ($selected) {
                return $forchets->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function formAdd()
    {
        $categories = RefCategorieActivite::all();
        $emplacements = App\Models\RefEmplacementActivite::all();
        $tailles = App\Models\RefTailleActivite::all();
        return view($this->module.'.add',['categories' => $categories, 'emplacements' => $emplacements, 'tailles' => $tailles]);
    }

    public function add(ForchetRequest $request)
    {
        if (ForchetteTax::where('ref_emplacement_activite_id',$request->categorie)->where('ref_emplacement_activite_id',$request->emplacement)->where('ref_taille_activite_id',$request->taille)->exists())
            return response()->json(['Exists'=>[''.trans('text_me.taxe_existe').'']],422);
        $forchet = new ForchetteTax();
        $forchet->ref_categorie_activite_id = $request->categorie;
        $forchet->ref_emplacement_activite_id = $request->emplacement;
        $forchet->ref_taille_activite_id = $request->taille;
        $forchet->montant = $request->montant;
        $forchet->save();
        return response()->json($forchet->id,200);
    }

    public function edit(ForchetRequest $request)
    {
        if (ForchetteTax::where('ref_emplacement_activite_id',$request->categorie)->where('ref_emplacement_activite_id',$request->emplacement)->where('ref_taille_activite_id',$request->taille)->where('id','<>',$request->id)->exists())
            return response()->json(['Exists'=>[''.trans('text_me.taxe_existe').'']],422);
        $forchet = ForchetteTax::find($request->id);
        $forchet->ref_categorie_activite_id = $request->categorie;
        $forchet->ref_emplacement_activite_id = $request->emplacement;
        $forchet->ref_taille_activite_id = $request->taille;
        $forchet->montant = $request->montant;
        $forchet->save();
        return response()->json('Done',200);
    }

    public function get($id)
    {
        $forchet = ForchetteTax::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
        ];
        $modal_title = '<b>'.$forchet->ref_categorie_activite->libelle.'-'.$forchet->ref_emplacement_activite->libelle.'-'.$forchet->ref_taille_activite->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab)
    {
        $forchet = ForchetteTax::find($id);
        switch ($tab) {
            case '1':
                $categories = RefCategorieActivite::all();
                $emplacements = App\Models\RefEmplacementActivite::all();
                $tailles = App\Models\RefTailleActivite::all();
                $parametres = ['forchet' => $forchet, 'categories' => $categories, 'emplacements' => $emplacements, 'tailles' => $tailles];
                break;
            default :
                $parametres = ['forchet' => $forchet,];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $forchet = ForchetteTax::find($id);
        $forchet->delete();
        return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
        }

public function getCategorie($id){
    $data= RefCategorieActivite::find($id)->get();
    return $data;
}
}
