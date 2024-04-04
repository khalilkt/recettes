<?php
namespace App\Http\Controllers;
use App\Http\Requests\CategorieRequest;
use App\Models\RefCategorieActivite;


use DataTables;
use App;
//use Auth;

class CategorieController extends Controller
{
    private $module = 'categories';

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
        $categories = RefCategorieActivite::query();
        if ($selected != 'all')
            $categories = $categories->orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($categories)
            ->addColumn('actions', function(RefCategorieActivite $categories) {
                $html = '<div class="btn-group">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$categories->id.',\''.$this->module.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url($this->module.'/delete/'.$categories->id).'\',\''.trans('text.confirm_suppression').''.$categories->libelle.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.supprimer').'"><i class="fas fa-trash"></i></button> ';
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

        return view($this->module.'.add');
    }

    public function add(CategorieRequest $request)
    {
        $montant=str_replace(' ', '', $request->montant);
        $value = (float)$montant;
        $categorie = new RefCategorieActivite();
        $categorie->libelle = $request->libelle;
        $categorie->libelle_ar = $request->libelle_ar;
        $categorie->montant = $value;
        $categorie->save();
        return response()->json($categorie->id,200);
    }

    public function edit(CategorieRequest $request)
    {
        $montant=str_replace(' ', '', $request->montant);
        $value = (float)$montant;
        $categorie = RefCategorieActivite::find($request->id);
        $categorie->libelle = $request->libelle;
        $categorie->libelle_ar = $request->libelle_ar;
        $categorie->montant = $value;
        $categorie->save();
        return response()->json('Done',200);
    }

    public function get($id)
    {
        $categorie = RefCategorieActivite::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
        ];
        $modal_title = '<b>'.$categorie->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab)
    {
        $categorie = RefCategorieActivite::find($id);
        switch ($tab) {
            case '1':
                $parametres = ['categorie' => $categorie];
                break;
            default :
                $parametres = ['categorie' => $categorie];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $categorie = RefCategorieActivite::find($id);
        $categorie->delete();
        return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
        }

}
