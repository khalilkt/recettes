<?php
namespace App\Http\Controllers;

use App\Http\Requests\FamilleRequest;
use App\Http\Requests\ActiviteRequest;
use App\Models\RefTypesFamille;;
use App\Models\RefCategorieActivite;
use App\Models\Activite;


use DataTables;
use App;
use http\Client\Request;

//use Auth;

class RoleController extends Controller
{
    private $module = 'role_annees';

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
        $roles = App\Models\RolesAnnee::all();
        if ($selected != 'all')
            $roles = App\Models\RolesAnnee::orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($roles)
            ->addColumn('actions', function(App\Models\RolesAnnee $role) {
                $html = '<div class="btn-group">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$role->id.',\''.$this->module.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                $html .=' <button type="button" class="btn btn-sm btn-primary" onClick="annulerRole('.$role->id.')" data-toggle="tooltip" data-placement="top" title="'.trans('text_me.annuler').'"><i class="fas fa-trash-restore"></i></button> ';
                $html .=' <button type="button" class="btn btn-sm btn-warning" onClick="" data-toggle="tooltip" data-placement="top" title="'.trans('text.activer').'"><i class="fas fa-dice-one"></i></button> ';
               $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url($this->module.'/delete/'.$role->id).'\',\''.trans('text.confirm_suppression').''.$role->libelle.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.supprimer').'"><i class="fas fa-trash"></i></button> ';
                $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($role) use ($selected) {
                return $role->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function formAdd()
    {
        $nomenclatures=App\Models\NomenclatureElement::where('niveau','<>',1)->where('ref_type_nomenclature_id',1)->get();
        return view($this->module.'.add',['nomenclatures'=>$nomenclatures]);
    }

    public function add(\Illuminate\Http\Request $request)
    {
        $objet=new ContribuableController();
        $selectRolAct=App\Models\RolesAnnee::where('etat',1)->get();
        foreach ($selectRolAct as $selectRol)
        {
            $rr=App\Models\RolesAnnee::find($selectRol->id);
            $rr->etat=0;
            $rr->save();
        }
        $annee=$objet->annee_encours();
        $role= new App\Models\RolesAnnee();
        $role->libelle = $request->libelle;
        $role->nomenclature_element_id = $request->nomenclature_element_id;
        $role->annee = $annee;
        $role->etat = 1;
        $role->save();
        return response()->json($role->id,200);
    }

    public function edit(\Illuminate\Http\Request $request)
    {

        $role=  App\Models\RolesAnnee::find($request->id);
        $role->libelle = $request->libelle;
        $role->nomenclature_element_id = $request->nomenclature_element_id;
        $role->save();
        return response()->json('Done',200);
    }

    public function get($id)
    {
        $role = App\Models\RolesAnnee::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
        ];
        $modal_title = '<b>'.$role->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab)
    {
        $nomenclatures=App\Models\NomenclatureElement::where('niveau','<>',1)->where('ref_type_nomenclature_id',1)->get();
        $role = App\Models\RolesAnnee::find($id);
        switch ($tab) {
            case '1':

                $parametres = ['role' => $role,'nomenclatures'=>$nomenclatures];
                break;
            default :
                $parametres = ['role' => $role];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $role = App\Models\RolesAnnee::find($id);
        $role->delete();
        return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
    }

}
