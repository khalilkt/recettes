<?php
namespace App\Http\Controllers;

use App\Http\Requests\FamilleRequest;
use App\Http\Requests\ActiviteRequest;
use App\Models\RefTypesFamille;;
use App\Models\RefCategorieActivite;
use App\Models\Activite;


use DataTables;
use App;
//use Auth;

class ActiviteController extends Controller
{
    private $module = 'activites';

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
        $activites = Activite::with('ref_categorie_activite');
        if ($selected != 'all')
            $activites = $activites->orderByRaw('id = ? desc', [$selected])->with('ref_categorie_activite');
        return DataTables::of($activites)
            ->addColumn('actions', function(Activite $activite) {
                $html = '<div class="btn-group">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$activite->id.',\''.$this->module.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url($this->module.'/delete/'.$activite->id).'\',\''.trans('text.confirm_suppression').''.$activite->libelle.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.supprimer').'"><i class="fas fa-trash"></i></button> ';
                $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($activite) use ($selected) {
                return $activite->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function formAdd()
    {
        $categories = RefCategorieActivite::all();
        return view($this->module.'.add',['categories' => $categories]);
    }

    public function add(ActiviteRequest $request)
    {
        $activite = new Activite();
        $activite->libelle = $request->libelle;
        $activite->libelle_ar = $request->libelle_ar;
        $activite->ref_categorie_activite_id = $request->categorie;
        $activite->save();
        return response()->json($activite->id,200);
    }

    public function edit(ActiviteRequest $request)
    {
        $activite = Activite::find($request->id);
        $activite->libelle = $request->libelle;
        $activite->libelle_ar = $request->libelle_ar;
        $activite->ref_categorie_activite_id = $request->categorie;
        $activite->save();
        return response()->json('Done',200);
    }

    public function get($id)
    {
        $activite = Activite::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
        ];
        $modal_title = '<b>'.$activite->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab)
    {
        $activite = Activite::find($id);
        switch ($tab) {
            case '1':
                $categories= RefCategorieActivite::all();
                $parametres = ['activite' => $activite, 'categories' => $categories];
                break;
            default :
                $parametres = ['activite' => $activite];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $activite = Activite::find($id);
        $activite->delete();
        return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
        }

}
