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
use App\Models\Payement;
use App\Models\Payementmens;
use App\Models\DegrevementContribuable;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

use Illuminate\Support\Facades\Log;
class ExportContribuable implements FromView,ShouldAutoSize
{
    public $annee;
    public $contr;
    public $date1;
    public $date2;
    public $filtrage;

    public function __construct($annee,$contr,$date1,$date2, $filtrage)
    {
        $this->annee=$annee;
        $this->contr=$contr;
        $this->date1=$date1;
        $this->date2=$date2;
        $this->filtrage=$filtrage;
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
        $lib = trans("text_me.suiviContribuable1");
        $conreoller = new EmployeController();
        $enetet = $conreoller->entete(  $lib.' '.$this->annee);
        $html = '';
        $html .= $enetet;

        if ($this->contr != 'all' or ($this->date1 != 'all' and $this->date2 != 'all'))
        {
            $html .= '<div >
                 <table width="100%" >
                    <tr><td>' . trans("text_me.filtrage") . ' :</td></tr>
                    <tr>
                        <td>';
            if ($this->contr != 'all') {
                $html .= '' . trans("text_me.contribuable") . ' :<b>' . $contribuable .'</b>';
            }
            if ($this->date1 != 'all' and $this->date2 != 'all') {
                $html .= ' ' . trans("text_me.Du") . ' <b>' . Carbon::parse($this->date1)->format('d-m-Y').'</b>';
                $html .= ' ' . trans("text_me.Au") . ' <b>' .  Carbon::parse($this->date2)->format('d-m-Y').'</b>';
            }
            $html .= '</td>
                </tr>
                </table>
                </div><br>';
        }

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
            $montants=0;
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
            $payements = App\Models\DegrevementContribuable::where('annee', $annee)->where('montant','<>',0)->where('created_at','>=', $date1)->where('created_at','<=', $date2)->get();
            $montants=0;
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
                $montants +=$payement->montants;
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
}


