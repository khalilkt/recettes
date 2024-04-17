<?php
namespace App\Exports;

use App\Http\Controllers\EmployeController;
use App\Http\Controllers\FinanceLocaleController;
use App\Models\Budget;
use App\Models\Commune;
use App\Models\Contribuable;
use App\Models\EnteteCommune;
use App\Models\Equipement;
use App\Models\NomenclatureElement;
use App\Models\Payementmens;
use App\Models\DegrevementContribuable;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;
use App\Models\DetailsPayement;
use App\Models\DetailsPayementmens;
use App\Models\RolesAnnee;
use App\Models\Annee;
use App\Models\RolesContribuable;

use App\Models\Payement;


use Illuminate\Support\Facades\Log;
class ExportContribuable implements FromView,ShouldAutoSize
{
    public $annee;
    public $contr;
    public $date1;
    public $date2;
    public $filtrage;
    public $role;
    private $restmontrecouv = 0;


    public function __construct($annee,$contr,$date1,$date2, $filtrage , $role)
    {
        $this->annee=$annee;
        $this->contr=$contr;
        $this->date1=$date1;
        $this->date2=$date2;
        $this->filtrage=$filtrage;
        $this->role = $role;

    }

    public function view():View
    {
        $contribuable = '';

        // if (!isset($this->contr)){
        //     $contr = 'all';
        // }

        if ($this->contr == "undefined"){
            $this->contr = 'all';
        }

        $html = "";
        $idc = env('APP_COMMUNE');
        $commune = Commune::find($idc);
        $entete_id = EnteteCommune::where('commune_id', $idc)->get()->first()->id;
        $entete = EnteteCommune::find($entete_id);
        if ($this->filtrage== 1){
            $lib = trans("text_me.suiviContribuable1");
        }else if ($this->filtrage== 2){
            $lib = trans("text_me.suiviContribuable2");
        } else if ($this->filtrage== 3){
            $lib = trans("suivirecouvrement");
        }

        $conreoller = new EmployeController();
        $enetet = $conreoller->entete(  $lib.' '.$this->annee);
        $html = '';
        $html .= $enetet;

        if ($this->contr != 'all' or ($this->date1 != 'all' and $this->date2 != 'all') or $this->role != 'all')
        {
            $html .= '<div >
                 <table width="100%" >
                    <tr><td>' . trans("text_me.filtrage") . ' :</td></tr>
                    <tr>
                        <td>';
            if ($this->contr != 'all') {
                $html .= '' . trans("text_me.contribuable") . ' :<b>' . $contribuable .'</b>';
            }
            if ($this->role != 'all') {
                $roleLi = RolesAnnee::find($this->role)->libelle;
                if ($roleLi != null) {
                    // insert a new line 
                    $html .= "<b>" . $roleLi . "</b>";
                }
            }
            if ($this->date1 != 'all' and $this->date2 != 'all') {
                $html .= "<br>";

                $html .= ' ' . trans("text_me.Du") . ' <b>' . Carbon::parse($this->date1)->format('d-m-Y').'</b>';
                $html .= ' ' . trans("text_me.Au") . ' <b>' .  Carbon::parse($this->date2)->format('d-m-Y').'</b>';
            }
            $html .= '</td>
                </tr>
                </table>
                </div><br>';
        }
        $montants=0;

        if ($this->filtrage == 1){

                // $payements = Payement::where('annee', $this->annee)->where('montant','<>',0)
                $payements = Payementmens::where('annee', $this->annee)->where('montant','<>',0)
                ->select('contribuable_id', 'date', DB::raw('SUM(montant) as montants'))
                ->groupBy('contribuable_id')
                ->groupBy('date')->get();

                if ($this->contr != 'all') {
                    $payements = $payements->where('contribuable_id', $this->contr);
                    $contribuable = Contribuable::find($payements->first()->contribuable_id)->libelle;
                    //dd($payements->first()->contribuable_id);
                }
                if ($this->date1 != 'all' and $this->date2 != 'all'){
                    // $payements = $payements->where('date', '>=',$date1 )->where('date', '<=', $date2);
                    $payements = $payements->where('date','>=', $this->date1)->where('date','<=', $this->date2);
                }
            $html .='<table border="1" width="100%" class="normal" >
            <thead>
            <tr bgcolor="#add8e6">
            <th><b>'.trans("text_me.contribuable") .'</b></th>
            <th><b>'. trans("text_me.date") .'</b></th>
            <th><b>'. trans("text_me.montant") .'</b></th>
            </tr>
            </thead>
            <tbody>';

            foreach ($payements as $payement)
                {
                    $html .='<tr>';
                    $html .='<td>'.Contribuable::find($payement->contribuable_id)->libelle.'</td>';
                    $html .='<td>'.Carbon::parse($payement->date)->format('d-m-Y').'</td>';
                    $html .='<td>'.number_format((float)$payement->montants,2).'</td>';
                    $html .='</tr>';
                    $montants +=$payement->montants;
                }
        }
        if ($this->filtrage == 2){
            $payements = DegrevementContribuable::where('annee', $this->annee)->where('montant','<>',0)->where('created_at','>=', $this->date1)->where('created_at','<=', $this->date2)->get();
            $html .='<table border="1" width="100%" class="normal" >
            <thead>
            <tr bgcolor="#add8e6">
            <th><b>Contribuable</b></th>
            <th><b>Adresse</b></th>
            <th><b>Decision</b></th>
            <th><b>Montant</b></th>
            </tr>
            </thead>
            <tbody>';
            foreach ($payements as $payement)
            {
                $contribuable=Contribuable::find($payement->contribuable_id);
                $html .='<tr>';
                $html .='<td>'.$contribuable->libelle.'</td>';
                $html .='<td>'.$contribuable->adresse.'</td>';
                $html .='<td>'.$payement->decision.'</td>';
                $html .='<td>'.$payement->montant.'</td>';
                $html .='</tr>';
                $montants +=$payement->montant;
                
            }

        }else if ($this->filtrage == 3){
            $html .='<table border="1" width="100%" class="normal" >
            <thead>
            <tr bgcolor="#add8e6">
            <th><b>Contribuable</b></th>
            <th><b>Adresse</b></th>
            <th><b>Impôts</b></th>
            <th><b>Année</b></th>
            <th><b>Article</b></th>
            <th><b>Rôle</b></th>
            <th><b>Montant</b></th>
            <th><b>Montant due</b></th>
            <th><b>Degrevement</b></th>
            <th><b>Reste à payer</b></th>
            </tr>
            </thead>
            <tbody>';


       

            $contribuables = Contribuable::selectRaw("contribuables.*")->whereIn('contribuables.id', function($query)  {
                $query->select('contribuable_id')->from('contribuables_annees')->where('annee', $this->annee);
            });
           
            if ($this->role != "all"){
                $contribuables = $contribuables->leftJoin('roles_contribuables', 'roles_contribuables.contribuable_id', '=', 'contribuables.id')
               ->where('roles_contribuables.role_id', $this->role);
            }
            
            if ($this->date1 != 'all'){
                
                $contribuables = $contribuables->where('contribuables.created_at','>=', $this->date1);
            }
            if ($this->date2 != "all"){
                $contribuables = $contribuables->where('contribuables.created_at','<=', $this->date2);
            }

            $contribuables = $contribuables->get();
            
            foreach ($contribuables as $contribuable)
            {
            //     if ($role !='all')
            //     {
            //         $roles = App\Models\RolesContribuable::where('contribuable_id', $contribuable->id)->
            //                 where('annee', $annee)->where('id', $role)->get();
            //     }
            //     else{
            //         $roles = App\Models\RolesContribuable::where('contribuable_id', $contribuable->id)->
            //         where('annee', $annee)->get();
            //   }

                $montantresr=$montantde=$montant_paye=$montantdgr=0;

                // foreach ($roles as $rol){
                foreach ($contribuable->roles as $rol){
                    $montantde +=$rol->montant;
                    $montant_paye += $rol->montant_paye;
                }

                $montantresr = $montantde-$montant_paye;

                //if ($montantresr>0){
                    $montants +=$montantresr;
                   // if ($contribuable->id !=72)
                    $html .= $this->contribuablePartie($contribuable->id,$this->annee,$montantresr,$this->role);
                    // $html .= $contribuable->id;
               // }
            }
            
        }
        $html .='<tr>';
        $html .='<td colspan="2" align=""><b>'. trans("text_me.total") .'</b></td>';
        $html .='<td><b>'.number_format((float)$montants,2).'</b></td>';
        $html .='</tr>';
        $html .='</tbody>
        </table>';
        $html .='<br><br><table><tr><td align="right"><b>'. trans("text_me.signature").'</b></td></tr></table>';
        $html = str_replace(' & ', ' &amp; ', $html);
        return view('contribuables.exports.export_contribuables',['html' =>$html]);
    }

    public function contribuablePartie($id,$annee,$montantresr,$role)
    {
        $contribuable = Contribuable::find($id);
        $impots = 'CF';
        $nbrroles=0;
        $montantdue = 0;$articles='';
        $roles = RolesContribuable::where('contribuable_id', $id)->
        where('annee', $annee)->get();
        $degrevements = DegrevementContribuable::where('contribuable_id', $id)->where('annee', $annee)->get();
        // foreach ($degrevements as $deg)
        // {
        //     $montantdue +=$deg->montant;
        // }
        foreach ($roles as $role) {
            if ($role->emeregement!=''){
                $impots =$role->emeregement;
            }
        }
        if ($roles->count()){
            // $nbrroles=1;
            $nbrroles=$roles->count();
        }
        else{
            $nbrroles=1;
        }
        if ($nbrroles < 1){
            $nbrroles=1;
        }

        // $html = $role;
        $montantdue = 0;
        $html='';$html1='';
        $html1 .='<td colspan="3"><table border="0  ">';
        foreach ($roles as $role) {
            // if ($role->emeregement!=''){

            // }
            $rr=RolesAnnee::find($role->role_id);
            $articles .= ''.$role->article .'<br> ';
            $montantdue += $role->montant;
            $html1 .='<tr>
            <td  align="left" >
            '.$role->article.'
            </td>
            <td  align="left" >
            '.$rr->libelle.'
            </td>
            <td align="right" >
            '.strrev(wordwrap(strrev($role->montant), 3, ' ', true)).'
            </td>
            </tr>';
        }
        $html1 .='</table></td>';
        $roles1 = RolesAnnee::where('annee', $annee)->get();

        // $html = $role;

        $roless='';
        foreach ($roles1 as $role) {
            $rolecont = $roles = RolesContribuable::where('contribuable_id', $id)->
            where('role_id', $role->id)->get();
            if ($rolecont->count() > 0) {
                $roless .= '' . $role->libelle . '<br> ';
            }
        }

        $montantdegr = 0;
        $motantPayes = 0;
        $annee_id = Annee::where('etat', 1)->get()->first()->id;
        $payements = Payement::where('contribuable_id', $id)->where('annee', $annee)->get();
        $payementNrs = Payementmens::where('contribuable_id', $id)->where('annee', $annee)->get();
        foreach ($payements as $payement) {
            $detatPays = DetailsPayement::where('payement_id', $payement->id)->get();
            foreach ($detatPays as $detatPa) {
                $motantPayes += $detatPa->montant;
            }
        }
        foreach ($payementNrs as $payement) {
            $detatPays = DetailsPayementmens::where('payement_id', $payement->id)->get();
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
        if ($contribuable and $rstap>0){
        $html .= '      <tr>
                        <td  align="center" >
                            <b>'.$contribuable->libelle.' </b>
                        </td>
                        <td  >
                            '.$contribuable->adresse.'
                        </td>
                        <td  >
                           '.$impots.'
                        </td>
                        <td  >
                             ' . $annee . '
                        </td>';
        $html .=$html1;
        // $html .= ' <td colspan="3" >"'.$nbrroles.'"</td>';
        $html.='
                        <td >
                            '.strrev(wordwrap(strrev($montantdue), 3, ' ', true)).'
                        </td>
                        <td  >
                            '.strrev(wordwrap(strrev($montantdegr), 3, ' ', true)).'
                        </td>
                         <td  >
                           '.strrev(wordwrap(strrev($rstap), 3, ' ', true)).'
                        </td>
                    </tr>
                    ';
            $this->restmontrecouv +=$rstap;
        }
        return $html;
    }

}


