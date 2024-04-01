<?php
namespace App\Http\Controllers;

use App\Models\RefTypesEquipement;
use Illuminate\Http\Request;
use App\Http\Requests\ModeleActeRequest;
use App\Http\Requests\ModeleActeItemRequest;
use App\Http\Requests\RefChoixElementRequest;
use App\Models\ModelesActe;
use App\Models\ModelesActesItem;
use App\Models\RefChoixItemesActe;
use App\Models\ActesValue;
use App\Models\Acte;
use DataTables;
use App\User;
use App;

//use Auth;
use PDF;
class ModeleController extends Controller
{
    private $module = 'modeles';
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
        $actes= ModelesActe::all();
        if ($selected != 'all')
            $actes = ModelesActe::orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($actes)
            ->addColumn('actions', function(ModelesActe $acte) {
                $html = '<div class="btn-group">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$acte->id.',\''.$this->module.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                    $html .= ' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\'' . url($this->module . '/delete/' . $acte->id) . '\',\'' . trans('text.confirm_suppression') . '' . $acte->libelle . '\')" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></button> ';
                $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($acte) use ($selected) {
                return $acte->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function formAdd()
    {
        return view($this->module.'.add');
    }

    public function add(ModeleActeRequest $request)
    {
        $acte = new ModelesActe();
        $acte->titre = $request->titre;
        $acte->titre_ar = $request->titre_ar;
        $acte->libelle = $request->libelle;
        $acte->libelle_ar = $request->libelle_ar;
        $acte->save();
        return response()->json($acte->id,200);
    }

    public function edit(ModeleActeRequest $request)
    {

        $acte = ModelesActe::find($request->id);
        $acte->titre = $request->titre;
        $acte->titre_ar = $request->titre_ar;
        $acte->libelle = $request->libelle;
        $acte->libelle_ar = $request->libelle_ar;
        $acte->save();
        return response()->json('Done',200);
    }

    public function get($id)
    {
        $acte = ModelesActe::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
            '<i class="fa fa-list"></i> '.trans('text_me.elements') => $tablink.'/2',
        ];
        $modal_title = '<b>'.$acte->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab)
    {
        $acte = ModelesActe::find($id);
        $etat=count(Acte::where('modeles_acte_id',$id)->get());
        switch ($tab) {
            case '1':
                $parametres = ['acte' => $acte];
                break;
            case '2':
                $elementss = ModelesActesItem::where('modeles_acte_id', $id)->get();
                $parametres = ['acte' => $acte, 'elementss' => $elementss, 'etat' => $etat];
                break;
            case '3':
                $element = ModelesActesItem::find($id);
                $parametres = ['element' => $element];
                break;
            default :
                $parametres = ['acte' => $acte];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $acte = ModelesActe::find($id);
        $acte->delete();
            return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
    }

    public function deleteElement($id)
    {
        $acte = ModelesActesItem::find($id);
        $acte->delete();
            return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
    }

    public function getElts($id,$selected='all')
    {
        $elements = ModelesActesItem::where('modeles_acte_id', $id);
        $etat=count(Acte::where('modeles_acte_id',$id)->get());
        if ($selected != 'all')
            $elements = $elements->orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($elements, $etat)
            ->editColumn('type_content', function(ModelesActesItem $element) {
                return  $element->type_text ;
            })
            ->addColumn('actions', function(ModelesActesItem $element) {
                $html = '<div class="btn-group">';
                if($element->type_content==3){
                    $html .=' <button type="button" class="btn btn-sm btn-warning" onClick="showchoixElement('.$element->id.',\''.$this->module.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fas fa-fw fa-cog"></i></button>';
                }
                $html .= ' <button type="button" class="btn btn-sm btn-success" onClick="showEditElementFormActe('. $element->id .')" data-toggle="tooltip" data-placement="top" title="' . trans('text.modifier') . '"><i class="fas fa-fw fa-eye"></i></button>';

                $etat=0;
                $etat=count(Acte::where('modeles_acte_id',$element->modeles_acte_id)->get());
                if ($etat==0){
                    $html .= ' <button type="button" class="btn btn-sm btn-danger" onClick="deleteElementModele(' . $element->id . ')" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-fw fa-trash"></i></button>';

                }
                     $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($element) use ($selected) {
                return $element->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function ligne($acte_id,$ligne){
        $l=1;
        if ($ligne=='P'){
            $l=1;
        }
        elseif ($ligne== '0'){
            $l=1;
            $elements = ModelesActesItem::where('modeles_acte_id', $acte_id)->orderBy('ligne')->get();
            $i = 1;
            foreach ($elements as $element) {
                $item = ModelesActesItem::find($element->id);
                $item->ligne =  ($item->ligne +1);
                $item->save();
            }
        }
        elseif ($ligne == 'D'){
            $max = ModelesActesItem::where('modeles_acte_id', $acte_id)->max('ligne') +1;
            $l = $max;
        }
        else {
            $l = $ligne+1;
            $elements = ModelesActesItem::where('modeles_acte_id', $acte_id)->where('ligne', '>=', $l )->orderBy('ligne')->get();
            //$i = $l + 1;
            foreach ($elements as $element) {
                    $item = ModelesActesItem::find($element->id);
                    $item->ligne = ($item->ligne+1);
                    $item->save();
            }
        }
       return  $l;
    }
    public function addElement($id)
    {
        $acte = ModelesActe::find($id);
        $itemes = ModelesActesItem::where('modeles_acte_id',$id)->where('nature_content', 0)->get() ;
       /* $lignes=collect();
        $lignes->push(["ligne" => 1]);*/
        $lignes1 = ModelesActesItem::where('modeles_acte_id',$id)->where('postion', 'like', '%<br>%')->where('nature_content', 0)->get();
        $cpt=0;
        foreach ($lignes1 as $ligne){
            $cpt+=1;
        }
        $lignes=$cpt;
        $maxOrdre = ModelesActesItem::where('modeles_acte_id',$id)->max('ordre')+1 or 1;
        return view('modeles.ajax.addElementActe',['acte'=>$acte, 'itemes'=>$itemes, 'lignes'=>$lignes, 'maxOrdre'=>$maxOrdre]);
    }

    public function saveElement(ModeleActeItemRequest $request)
    {
        $item = new ModelesActesItem();
        $espaces=$br='';
        $ordre=$parent=0;
        $l=$request->ligne;
        if ($request->parent== 0 or $request->parent==''){$parent=0;}
        else{$parent=$request->parent;}
        if ($request->align=='Tabilation'){
            $espaces.='&nbsp;&nbsp;&nbsp;' ;
        }
        if ($request->nbrebr!=0){
            for ($i=0;$i<$request->nbrebr;$i++)
            {
                $br.='<br>' ;
            }
        }
        if ($request->position=='<br>'){
            $ordre=1;
            $item->ordre = $ordre;
           // $request->ligne
            $l=$this->ligne($request->id,$request->ligne);
            //dd($request->ligne);
        }
        elseif ($request->parent==0 and $request->position!='<br>' ){
            $verif= $request->ordres;
            if ($verif=='P'){
                $ordre=1;
                $item->ordre = $ordre;
            }
            elseif ($verif=='0'){
                $ordre=1;
                $item->ordre = $ordre;
                $elements = ModelesActesItem::where('modeles_acte_id', $request->id)->where('ligne', $request->ligne)->where('parent', 0)->orderBy('ordre')->get();
                $i=1;
                foreach ($elements as $element){
                    $i +=1;
                    $it = ModelesActesItem::find($element->id);
                    $it->ordre =$i;
                    $it->save();
                }
            }
            elseif ($verif=='D'){
                $max = ModelesActesItem::where('modeles_acte_id', $request->id)->where('ligne', $request->ligne)->where('parent', 0)->max('ordre')+1;
                $item->ordre = $max;
            }
            else{
                $elements = ModelesActesItem::where('modeles_acte_id', $request->id)->where('ligne', $request->ligne)->where('parent', 0)->where('ordre','>', $verif)->orderBy('ordre')->get();
                $item->ordre = $verif+1;
                $i=$verif+1;
                foreach ($elements as $element){
                    $i +=1;
                    $it = ModelesActesItem::find($element->id);
                    $it->ordre =$i;
                    $it->save();
                }
            }
        }
        elseif ($request->parent!=0 and $request->position!='<br>'){
            $verif= $request->ordres;
            if ($verif=='P'){
                $ordre=1;
                $item->ordre = $ordre;
                  }
            elseif ($verif=='0'){
                $ordre=1;
                $item->ordre = $ordre;
                $elements =ModelesActesItem::where('modeles_acte_id', $request->id)->where('parent', $request->parent)->orderBy('ordre')->get();
                $i=1;
                foreach ($elements as $element){
                    $i +=1;
                    $it = ModelesActesItem::find($element->id);
                    $it->ordre =$i;
                    $it->save();
                }
            }
            elseif ($verif=='D'){
                $max = ModelesActesItem::where('modeles_acte_id', $request->id)->where('parent', $request->parent)->max('ordre')+1;
                $item->ordre = $max;
            }
            else{
                $elements =ModelesActesItem::where('modeles_acte_id', $request->id)->where('parent', $request->parent)->where('ordre','>', $verif)->orderBy('ordre')->get();
                $item->ordre = $verif+1;
                $i=$verif+1;
                foreach ($elements as $element){
                    $i +=1;
                    $it = ModelesActesItem::find($element->id);
                    $it->ordre =$i;
                    $it->save();
                }
            }
        }
        $item->modeles_acte_id = $request->id;
        $item->content_value = $request->content_value;
        $item->content_value_ar = $request->content_value_ar;
        $item->nature_content = $request->nature;
        $item->postion = $request->position.''. $br.''.$espaces;
        $item->alignement = $request->align;
        $item->parent = $parent;
        $item->ligne = $l;
        if ($request->nature == 1){
            $item->type_content = $request->type;
            $item->texte_secondaire = $request->texte;
            if ($request->type == 3){
                if (trim($request->choix) !=''){
                    $item->save();
                }
            }
            else{
                $item->save();
            }
            if ($item->id and $request->type == 3) {
                $data = collect();
                $exploded = explode(",", trim($request->choix));
                foreach ($exploded as $value) {
                    $data->push(["libelle" => $value,"libelle_ar" => $value, "modeles_actes_item_id" => $item->id]);
                }
                RefChoixItemesActe::insert($data->toArray());
            }
            if ($item->id)
            {
                $actes= Acte::where('modeles_acte_id', $request->id)->get();
                foreach ($actes as $acte) {
                    $actevalue = new ActesValue();
                    $actevalue->acte_id = $acte->id;
                    $actevalue->modeles_actes_item_id = $item->id;
                    $actevalue->value = '';
                    $actevalue->save();
                }
            }
        }
        else{
            $item->save();
        }
        return response()->json($item->id,200);
    }


    public function updateElement(ModeleActeItemRequest $request)
    {
        $item =  ModelesActesItem::find($request->id_element);
        $espaces=$br='';
        $ordre=$parent=0;
        $l=$request->ligne;
        $pp='';
        if ($request->parent== 0 or $request->parent==''){$parent=0;}
        else{$parent=$request->parent;}
        if ($request->align=='Tabilation'){
            $espaces.='&nbsp;&nbsp;&nbsp;' ;
        }
        if ($request->nbrebr!=0 and $request->position=='<br>'){
            for ($i=0;$i<$request->nbrebr;$i++)
            {
                $br.='<br>' ;
                $pp='<br>';
            }
        }
        if ($request->position=='<br>'){
            $ordre=1;
            $pp='<br>';
            $item->ordre = $ordre;
            // $request->ligne
            $l=$this->ligne($request->id,$request->ligne);
            //dd($request->ligne);
        }
        elseif ($request->parent==0 and $request->position!='<br>' ){
            $verif= $request->ordres;
            if ($verif=='0'){
                $ordre=1;
                $item->ordre = $ordre;
                $elements = ModelesActesItem::where('modeles_acte_id', $request->id)->where('ligne', $request->ligne)->where('parent', 0)->orderBy('ordre')->get();
                $i=1;
                foreach ($elements as $element){

                    $i +=1;
                    $it = ModelesActesItem::find($element->id);
                    $it->ordre =$i;
                    $it->save();
                }
            }
            elseif ($verif=='D'){
                $max = ModelesActesItem::where('modeles_acte_id', $request->id)->where('ligne', $request->ligne)->where('parent', 0)->max('ordre')+1;
                $item->ordre = $max;
            }
            else{
                $elements = ModelesActesItem::where('modeles_acte_id', $request->id)->where('ligne', $request->ligne)->where('parent', 0)->where('ordre','>', $verif)->orderBy('ordre')->get();
                $item->ordre = $verif+1;
                $i=$verif+1;
                foreach ($elements as $element){
                    $i +=1;
                    $it = ModelesActesItem::find($element->id);
                    $it->ordre =$i;
                    $it->save();
                }
            }
        }
        elseif ($request->parent!=0 and $request->position!='<br>'){
            $verif= $request->ordres;
            if ($verif=='P'){
                $ordre=1;
                $item->ordre = $ordre;
            }
            elseif ($verif=='0'){
                $ordre=1;
                $item->ordre = $ordre;
                $elements =ModelesActesItem::where('modeles_acte_id', $request->id)->where('parent', $request->parent)->orderBy('ordre')->get();
                $i=1;
                foreach ($elements as $element){
                    $i +=1;
                    $it = ModelesActesItem::find($element->id);
                    $it->ordre =$i;
                    $it->save();
                }
            }
            elseif ($verif=='D'){
                $max = ModelesActesItem::where('modeles_acte_id', $request->id)->where('parent', $request->parent)->max('ordre')+1;
                $item->ordre = $max;
            }
            else{
                $elements =ModelesActesItem::where('modeles_acte_id', $request->id)->where('parent', $request->parent)->where('ordre','>', $verif)->orderBy('ordre')->get();
                $item->ordre = $verif+1;
                $i=$verif+1;
                foreach ($elements as $element){
                    $i +=1;
                    $it = ModelesActesItem::find($element->id);
                    $it->ordre =$i;
                    $it->save();
                }
            }
        }
        if ( $pp!='<br>'){
            $br='' ;
        }
        $item->modeles_acte_id = $request->id;
        $item->content_value = $request->content_value;
        $item->content_value_ar = $request->content_value_ar;
        $item->nature_content = $request->nature;
        $item->postion = $request->position.''. $br.''.$espaces;
        $item->alignement = $request->align;
        $item->parent = $parent;
        $item->ligne = $l;
        $item->save();
        if ($request->type  == 3) {
            $oldChoix = $request->choisa;
            $newChoix = $request->choix.',';
            if(trim($oldChoix) != trim($newChoix) ){
                $exploded = explode(",", trim($request->choix));
                $element = ModelesActesItem::find($request->id_element);
                $choix = ($element->ref_choix_itemes_actes()) ? $element->ref_choix_itemes_actes()->pluck('libelle') : null;
                if ($choix)
                {
                    $verif=0;
                    foreach ($choix as $choi)
                    {
                        foreach ($exploded as $value)
                        {
                            if($choi == $value) {   $verif=1; break;}
                        }
                        if($verif == 0){
                            $refChoix1 = RefChoixItemesActe::where('modeles_actes_item_id',$request->id_element)->where('libelle',$choi)->forceDelete();
                        }
                        $verif=0;
                    }
                }
                $valeur ='';
                foreach ($exploded as $value) {
                    $valeur = RefChoixItemesActe::where('modeles_actes_item_id',$request->id_element)->where('libelle',$value)->get();
                    if(trim($valeur) != '[]'){
                        $valeur ='';
                    }
                    else{
                        $refChoix= new RefChoixItemesActe();
                        $refChoix->modeles_actes_item_id = $request->id_element;
                        $refChoix->libelle = $value;
                        $refChoix->save();
                    }
                }
            }
            // RefChoixElement::insert($data->toArray());
        }
        return response()->json('Done',200);
    }

    public function getParentLigne($id,$ligne)
    {
       $elements =ModelesActesItem::where('modeles_acte_id', $id)->where('ligne', $ligne)->where('parent', 0)->orderBy('ordre')->get();
       return $elements;
    }

    public function getFilstLigne($id,$parent)
    {
       $fils =ModelesActesItem::where('modeles_acte_id', $id)->where('parent', $parent)->orderBy('ordre')->get();
       return $fils;
    }

    public function getLignes($id)
    {
        $lignes = ModelesActesItem::where('modeles_acte_id',$id)->where('postion', 'like', '%<br>%')->orderBy('ligne')->get();
        return $lignes;
    }

    public function EditElts($id)
    {
        $element = ModelesActesItem::find($id);
        $parents=$parent=$ligne ='';
        $saut_deligne='';
        $position='';
        $positions='';
        $ordre=$ordres='';
        if(trim($element->postion)=="" or trim($element->postion)==" ") {
            $ligne = '';
            $position = ModelesActesItem::where('modeles_acte_id', $element->modeles_acte_id)->where('ligne', $element->ligne)->get()->first();
            $positions =ModelesActesItem::where('modeles_acte_id', $element->modeles_acte_id)->where('postion', 'like', '%<br>%')->get();
            $parents = ModelesActesItem::where('modeles_acte_id', $element->modeles_acte_id)->where('ligne', $element->ligne)->where('parent', 0)->get();

            if ($element->parent != 0){
                $parent = ModelesActesItem::find($element->parent);
                $ordre=$element->ordre;
                $ordres= ModelesActesItem::where('parent', $element->parent)->orderBy('ordre','asc')->get();
            }
            $ordre=$element->ordre;
            $ordres= ModelesActesItem::where('modeles_acte_id', $element->modeles_acte_id)->where('ligne', $element->ligne)->where('parent', 0)->orderBy('ordre','asc')->get();
        }
        else{
            $ligne ='<br>';
            $saut_deligne=substr_count($element->postion, '<br>');
            $position = ModelesActesItem::where('modeles_acte_id', $element->modeles_acte_id)->where('ligne', $element->ligne)->get()->first();
            $positions =ModelesActesItem::where('modeles_acte_id', $element->modeles_acte_id)->where('postion', 'like', '%<br>%')->get();

        }
        $this_parent = ModelesActesItem::find($element->parent);
        if ($this_parent !=null){
            $famille = ModelesActesItem::where('parent', $element->parent)->orderBy('ordre')->get();
        }
        else{
            $famille = ModelesActesItem::where('modeles_acte_id', $element->modeles_acte_id)->where('ligne', $element->ligne)->orderBy('ordre')->get();
        }
        $options = '';

        $choix = ($element->ref_choix_itemes_actes()) ? $element->ref_choix_itemes_actes()->pluck('libelle') : null;
        if ($choix){
            foreach ($choix as $choi)   {
                $options .= $choi.',';
            }
        }
        $etat=0;
        $etat=count(Acte::where('modeles_acte_id',$element->modeles_acte_id)->get());
        return view('modeles.ajax.editElementActe',['element'=>$element, 'famille'=>$famille,'this_parent'=>$this_parent, 'position'=>$position, 'positions'=>$positions, 'options'=>$options, 'ligne'=>$ligne, 'saut_deligne'=>$saut_deligne,'parent'=>$parent,'parents'=>$parents,'ordres'=>$ordres,'ordre'=>$ordre,'etat'=>$etat]);
    }

    public function showChoiceValue($id)
    {
        $element = ModelesActesItem::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $numbers=array(1=>3);
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/3',
        ];
        $modal_title = '<b>'.trans('text_me.element').' :'.$element->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title,'numbre'=>$numbers]);
    }

    public function editChoixElement(RefChoixElementRequest $request)
    {
        $element = ModelesActesItem::find($request->id);
        foreach ($element->ref_choix_itemes_actes as $choix){
            $refChoixElement = RefChoixItemesActe::find($choix->id);
            $refChoixElement->libelle = $request->input('fr'.$choix->id.'').'';
            $refChoixElement->libelle_ar = $request->input('ar'.$choix->id.'').'';
            $refChoixElement->save();
        }
        return response()->json('Done',200);
    }

    public function ischildusetoshow($iteme,$id)
    {
        $corp ='';
        $val= ModelesActesItem::where('modeles_actes_items.parent',$iteme->id)->orderBy('ordre','asc')->get();

        foreach ($val as $v)
        {
           // $corp .='<td '.$v->alignement.'>';
           // $corp .=''.$v->postion.'';
            $corp .=' <b>['.$v->content_value.']</b> '.$v->texte_secondaire;
            //$corp .='</td>';
        }
        return $corp;
    }

    public function imprimerModele($id)
    {
        $corp='';
        $itemes = ModelesActesItem::where('modeles_acte_id',$id)->where('parent',0)->orderBy('ligne','asc')->orderBy('ordre','asc')->get();
        $parent=0;
        $corp .='<table width="100%" >';
        $rupture_ligne=$itemes->first()->ligne;
        $corp .='<tr>';
        $corp .='<td '.$itemes->first()->alignement.'><table><tr>';
        foreach ($itemes as $iteme)
        {
            if ($rupture_ligne != $iteme->ligne)
            {
                $rupture_ligne = $iteme->ligne;
                $corp .='</tr>';
                $corp .='</table>';
                $corp .='</td>';
                $corp .='</tr>';
                $corp .='<tr>';
                $corp .='<td  '.$iteme->alignement.'><table width="100%" ><tr>';
            }
            //testetstte
            //shshhdh
            $parent=$iteme->id;
            $corp .='<td '.$iteme->alignement.'>'.$iteme->postion.'';
            $corp .='<span>'.$iteme->content_value.'  </span>';
            $corp .=$this->ischildusetoshow($iteme,$id);
            $corp .='</td>';
        }
        $corp .='</tr>';
        $corp .='</table>';
        $corp .='</td>';
        $corp .='</tr>';
        $corp .='</table>';
        $conroller = new EmployeController();
        $enetet = $conroller->entete(ModelesActe::find($id)->libelle);
        $html ='';
        $html .=$enetet;
        $view = \View::make('modeles.exports.modele', ['corp' => $corp]);
        $html_content = $view->render();
        $html .= $html_content ;
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle(''.ModelesActe::find($id)->libelle.'');
        PDF::SetSubject(''.ModelesActe::find($id)->libelle.'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($html, true, false, true, false, '');
        // PDF::writeHTML($html_content, true, false, true, false, '');
        PDF::Output(uniqid().'liste_emp.pdf');
    }
}
