<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FamilleRequest;
use App\Models\Famille;
use App\Models\RefTypesFamille;;

use DataTables;
use App\User;
use App;
//use Auth;

class RecetteController extends Controller
{
    private $module = 'recettes';
    //private $viewLink = 'backend.'.$this->module;

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
        $familles = Famille::query();
       // $familles = Famille::with('ref_types_famille');
        if ($selected != 'all')
            $familles = Famille::orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($familles)
            ->addColumn('actions', function(Famille $famille) {
                $html = '<div class="btn-group">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$famille->id.',\''.$this->module.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url($this->module.'/delete/'.$famille->id).'\',\''.trans('text.confirm_suppression').'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.supprimer').'"><i class="fas fa-trash"></i></button> ';
                $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($famille) use ($selected) {
                return $famille->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function formAdd()
    {
        return view($this->module.'.add');
    }

    public function add(FamilleRequest $request)
    {
        $famille = new Famille;
        $famille->libelle = $request->libelle;
        $famille->save();
        return response()->json($famille->id,200);
    }

    public function edit(FamilleRequest $request)
    {
        $famille = Famille::find($request->id);
        $famille->libelle = $request->libelle;
        $famille->save();
        return response()->json('Done',200);
    }

    public function get($id)
    {
        $famille = Famille::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
            '<i class="fa fa-plus-circle"></i> '.trans('text.elements') => $tablink.'/2',
        ];
        $modal_title = '<b>'.$famille->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab)
    {
        $famille = Famille::find($id);
        switch ($tab) {
            case '1':
                $parametres = ['famille' => $famille];
                break;
            default :
                $parametres = ['famille' => $famille];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $famille = Famille::find($id);
        if ($famille->has_articles)
            return response()->json(['success'=>'false', 'msg'=>trans('text.famille_cant_be_del_bcuz_of_articles')],200);
        else {
            $famille->delete();
            return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
        }
    }
}
