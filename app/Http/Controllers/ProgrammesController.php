<?php
namespace App\Http\Controllers;

use App\Http\Requests\FamilleRequest;
use App\Http\Requests\ActiviteRequest;
use App\Models\Annee;
use App\Models\Commune;
use App\Models\Contribuable;
use App\Models\DetailsPayement;
use App\Models\EnteteCommune;
use App\Models\Payement;
use App\Models\RefTypesFamille;;
use App\Models\RefCategorieActivite;
use App\Models\Activite;


use DataTables;
use App;
use http\Client\Request;
use PDF;
//use Auth;

class ProgrammesController extends Controller
{
    private $module = 'programmes';

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
        $anne=App\Models\Annee::where('etat',1)->get()->first()->id;
        $programmes= App\Models\Programmejour::where('annee_id',$anne);
        if ($selected != 'all')
            $programmes = App\Models\Programmejour::orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($programmes)
            ->addColumn('actions', function(App\Models\Programmejour $programmes) {
                $html = '<div class="btn-group">';

                    $html .= '<button type="button" class="btn btn-sm btn-warning" onClick="exportprogrammePDF(' . $programmes->id . ')" data-toggle="tooltip" data-placement="top" title="' . trans('text_me.editficheContribuable') . '"><i class="fas fa-fw fa-file-pdf"></i></button>';

                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$programmes->id.',\''.$this->module.'\',2,\'lg\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
               $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url($this->module.'/delete/'.$programmes->id).'\',\''.trans('text.confirm_suppression').''.$programmes->libelle.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.supprimer').'"><i class="fas fa-trash"></i></button> ';
                $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($programmes) use ($selected) {
                return $programmes->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function formAdd()
    {
        return view($this->module.'.add');
    }

    public function add(\Illuminate\Http\Request $request)
    {
        $anne=App\Models\Annee::where('etat',1)->get()->first()->id;
        $programme= new App\Models\Programmejour();
        $programme->libelle = $request->libelle;
        $programme->date = $request->date;
        $programme->annee_id = $anne;
        $programme->etat = 1;
        $programme->save();
        return response()->json($programme->id,200);
    }

    public function edit(\Illuminate\Http\Request $request)
    {
        $anne=App\Models\Annee::where('etat',1)->get()->first()->id;
        $programme=  App\Models\Programmejour::find($request->id);
        $programme->libelle = $request->libelle;
        $programme->date = $request->date;
        $programme->annee_id = $anne;
        $programme->etat = 1;
        $programme->save();

        return response()->json('Done',200);
    }

    public function get($id)
    {
        $programme = App\Models\Programmejour::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
            '<i class="fa fa-info-circle"></i> '.trans('text_me.contribuables') => $tablink.'/2',
        ];
        $modal_title = '<b>'.$programme->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab)
    {
        $programme = App\Models\Programmejour::find($id);
        $contribuables=App\Models\Programmejourcont::where('programmejour_id',$id)->get();
        switch ($tab) {
            case '1':
                $parametres = ['programme' => $programme,'contribuables'=>$contribuables];
                break;
            default :
                $parametres = ['programme' => $programme,'contribuables'=>$contribuables];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $role = App\Models\Programmejour::find($id);

        if ($role->id){
            $prs=App\Models\Programmejourcont::where('programmejour_id',$role->id)->get();
            foreach ($prs as $pr)
            {
                $p=App\Models\Programmejourcont::find($pr->id);
                $p->delete();
            }
        }
        $role->delete();

        return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
    }
    public function getDroitsDT($id)
    {
        $programme = App\Models\Programmejour::find($id);
        $droits =App\Models\Contribuable::whereNotIn('id',App\Models\Programmejourcont::all()->pluck('contribuable_id'))->get();
        return Datatables::of($droits)
            ->editColumn('libelle', function($droit){
                //$annee= $this->annee_encours();
                $annee=Annee::where('etat',1)->get()->first()->annee;
                $roles=App\Models\RolesContribuable::where('contribuable_id',$droit->id)->
                where('annee',$annee)->get();
                // $html = $role;
                $html='';
                foreach ($roles as $role)
                {
                    $html .=''.$role->article.' /';
                }
                return $html.' '.$droit->libelle;
            })
            ->addColumn('actions', function($droit) {
                $html = '<div class="btn-group float-right">';
                $html .='<button type="button" idelt="'.$droit->id.'" libelle="'.$droit->libelle.'" data-toggle="tooltip" data-placement="top" title="Ajouter" class="btn btn-light" onClick="updateGroupeElements(this)"><i class="fa fa-fw fa-arrow-right"></i></button>';
                return $html.'</div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function updateGrouping($list, $id)
    {
        App\Models\Programmejourcont::where(['programmejour_id'=>$id])->ForceDelete();
        if ($list) {
            $list = explode(',',$list);
            foreach ($list as $qst_id) {
                $grp_qst = new App\Models\Programmejourcont();
                $grp_qst->programmejour_id = $id;
                $grp_qst->contribuable_id = $qst_id;
                $grp_qst->save();
            }
        }
        $res = "le groupe a été bien mise a jour";
        return response()->json($list ,200);
    }

    public function addToGrouping($contr_id, $programme_id){
        $isExist = App\Models\Programmejourcont::where(['contribuable_id'=>$contr_id, 'programmejour_id'=>$programme_id])->get();
        if ($isExist->count() == 0){
            $grp_qst = new App\Models\Programmejourcont();
            $grp_qst->programmejour_id = $programme_id;
            $grp_qst->contribuable_id = $contr_id;
            $grp_qst->save();
        }


    }

    public function exportprogrammePDF($id)
    {
        $idc = env('APP_COMMUNE');
        $commune = Commune::find($idc);
        $entete_id = EnteteCommune::where('commune_id', $idc)->get()->first()->id;
        $entete = EnteteCommune::find($entete_id);
        $progarammme = App\Models\Programmejour::find($id);
        $lib = '<br>'.$progarammme->libelle .' ' .$progarammme->date;
        $conreoller = new EmployeController();
        $annee=Annee::where('etat',1)->get()->first()->annee;
        $ann=Annee::where('etat',1)->get()->first()->id;
        //$annee = Annee::find()->annee;
        $enetet = $conreoller->entete( ''.$lib.'');

        $html =$enetet;
        $tablemois='<br><br><br><br><table><tr><td align="right"><b>'. trans("text_me.signature").'</b><br><br></td></tr></table>';

        $contriProgs=App\Models\Programmejourcont::where('programmejour_id',$id)->get();
        $html .= '<table width="100%"   border="1" cellspacing="0" cellpadding="0">  
                    <tr>
                        <td style="width: 15%;" align="center">
                            <b>Contribuable</b> 
                        </td>
                        <td style="width:10% ;">
                            <b>Adresse</b> 
                        </td>
                        <td style="width:10% ;">
                            <b>Impôts</b> 
                        </td>
                        <td style="width:10% ;">
                            <b>Année</b> 
                        </td>
                        <td style="width:5% ;">
                            <b>Article</b> 
                        </td>
                        <td style="width:15% ;" align="center">
                            <b>Rôle</b> 
                        </td>
                        <td style="width:7% ;" >
                            <b>Montant </b> 
                        </td>
                        <td style="width:8% ;" >
                            <b>Montant due</b> 
                        </td>
                        <td style="width:10% ;" >
                            <b>Degrevement </b> 
                        </td>
                         <td style="width:10% ;" >
                            <b>Reste à payer </b> 
                        </td>
                    </tr>
                    ';
        foreach ($contriProgs as $contriProg)
        {
            $html .=$this->contribuablePartie($contriProg->contribuable_id,$annee);
        }
        $html .= '</table>';
        $html .= $tablemois;
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle('Contribuable');
        PDF::SetSubject('Contribuable');
        PDF::SetMargins(8, 8, 8);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('9px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('L', 'A4');
        PDF::SetFont('dejavusans', '', 9);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().'liste_emp.pdf');
    }

    public function contribuablePartie($id,$annee)
{
    $contribuable = Contribuable::find($id);
    $impots = 'CF';
    $nbrroles=0;
    $montantdue = 0;$articles='';
    $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
    where('annee', $annee)->get();
    $degrevements = App\Models\DegrevementContribuable::where('contribuable_id', $id)->where('annee', $annee)->get();
    foreach ($degrevements as $deg)
    {
        $montantdue +=$deg->montant;
    }
    foreach ($roles as $role) {
        if ($role->emeregement!=''){
            $impots =$role->emeregement;
        }
    }
    $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
    where('annee', $annee)->get();
    if ($roles->count()){
        $nbrroles=1;
    }
    else{
        $nbrroles=1;
    }

    // $html = $role;
   // $montantdue = 0;
    $html='';$html1='';
    $html1 .='<td colspan="3"><table border="0" style="width: 100%;">';
    foreach ($roles as $role) {
        if ($role->emeregement!=''){

        }
        $rr=App\Models\RolesAnnee::find($role->role_id);
        $articles .= ''.$role->article .'<br> ';
        $montantdue += $role->montant;
        $html1 .='<tr>
            <td  align="left" style="width: 15%;">
            '.$role->article.'
            </td>
            <td  align="left" style="width: 60%;">
            '.$rr->libelle.' 
            </td>
            <td align="right" style="width: 25%;">
            '.strrev(wordwrap(strrev($role->montant), 3, ' ', true)).'
            </td>
            </tr>';
    }
    $html1 .='</table></td>';
    $roles1 = App\Models\RolesAnnee::where('annee', $annee)->get();

    // $html = $role;

    $roless='';
    foreach ($roles1 as $role) {
        $rolecont = $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
        where('role_id', $role->id)->get();
        if ($rolecont->count() > 0) {
            $roless .= '' . $role->libelle . '<br> ';
        }
    }

    $montantdegr = 0;
    $motantPayes = 0;
    $annee_id = Annee::where('etat', 1)->get()->first()->id;
    $payements = Payement::where('contribuable_id', $id)->where('annee', $annee)->get();
    $payementNrs = App\Models\Payementmens::where('contribuable_id', $id)->where('annee', $annee)->get();
    foreach ($payements as $payement) {
        $detatPays = DetailsPayement::where('payement_id', $payement->id)->get();
        foreach ($detatPays as $detatPa) {
            $motantPayes += $detatPa->montant;
        }
    }
    foreach ($payementNrs as $payement) {
        $detatPays = App\Models\DetailsPayementmens::where('payement_id', $payement->id)->get();
        foreach ($detatPays as $detatPa) {
            $motantPayes += $detatPa->montant;
        }
    }
    if ($degrevements->count()>0){
        foreach ($degrevements as $degrevement) {
            $montantdegr += $degrevement->montant;

        }
    }
    $rstap=0;
    $rstap=$montantdue -$motantPayes-$montantdegr;
$html .= '      <tr>
                        <td style="width: 15%;" align="center" rowspan="'.$nbrroles.'">
                            <b>'.$contribuable->libelle.' </b> 
                        </td>
                        <td style="width:10% ;" rowspan="'.$nbrroles.'">
                            '.$contribuable->adresse.' 
                        </td>
                        <td style="width:10% ;" rowspan="'.$nbrroles.'">
                           '.$impots.'
                        </td>
                        <td style="width:10% ;" rowspan="'.$nbrroles.'">
                             ' . $annee . ' 
                        </td>';
                    $html .=$html1;
                    $html.='
                        <td style="width:8% ;" rowspan="'.$nbrroles.'">
                            '.strrev(wordwrap(strrev($montantdue), 3, ' ', true)).'
                        </td>
                        <td style="width:10% ;" rowspan="'.$nbrroles.'">
                            '.strrev(wordwrap(strrev($montantdegr), 3, ' ', true)).'  
                        </td>
                         <td style="width:10% ;" rowspan="'.$nbrroles.'">
                           '.strrev(wordwrap(strrev($rstap), 3, ' ', true)).'
                        </td>
                    </tr>
                    ';
    return $html;
}
 public function contribuableRestApaye($id,$annee)
{
    $contribuable = Contribuable::find($id);
    $impots = 'CF';
    $montantdue = 0;$articles='';
    $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
    where('annee', $annee)->get();
    $degrevements = App\Models\DegrevementContribuable::where('contribuable_id', $id)->where('annee', $annee)->get();
    foreach ($degrevements as $deg)
    {
        $montantdue +=$deg->montant;
    }
    foreach ($roles as $role) {
        if ($role->emeregement!=''){
            $impots =$role->emeregement;
        }
    }
    $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
    where('annee', $annee)->get();
    $nbrroles=$roles->count();
    // $html = $role;
   // $montantdue = 0;

    foreach ($roles as $role) {
        if ($role->emeregement!=''){

        }
        $rr=App\Models\RolesAnnee::find($role->role_id);
        $articles .= ''.$role->article .'<br> ';
        $montantdue += $role->montant;

    }

    $roles1 = App\Models\RolesAnnee::where('annee', $annee)->get();

    // $html = $role;

    $roless='';
    foreach ($roles1 as $role) {
        $rolecont = $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
        where('role_id', $role->id)->get();
        if ($rolecont->count() > 0) {
            $roless .= '' . $role->libelle . '<br> ';
        }
    }

    $montantdegr = 0;
    $motantPayes = 0;
    $annee_id = Annee::where('etat', 1)->get()->first()->id;
    $payements = Payement::where('contribuable_id', $id)->where('annee', $annee)->get();
    $payementNrs = App\Models\Payementmens::where('contribuable_id', $id)->where('annee', $annee)->get();
    foreach ($payements as $payement) {
        $detatPays = DetailsPayement::where('payement_id', $payement->id)->get();
        foreach ($detatPays as $detatPa) {
            $motantPayes += $detatPa->montant;
        }
    }
    foreach ($payementNrs as $payement) {
        $detatPays = App\Models\DetailsPayementmens::where('payement_id', $payement->id)->get();
        foreach ($detatPays as $detatPa) {
            $motantPayes += $detatPa->montant;
        }
    }
    if ($degrevements->count()>0){
        foreach ($degrevements as $degrevement) {
            $montantdegr += $degrevement->montant;

        }
    }
    $rstap=0;
    $rstap=$montantdue -$motantPayes-$montantdegr;


    return $rstap;
}


}
