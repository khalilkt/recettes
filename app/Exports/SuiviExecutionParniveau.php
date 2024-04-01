<?php


namespace App\Exports;


use App\Http\Controllers\EmployeController;
use App\Http\Controllers\FinanceLocaleController;
use App\Models\Budget;
use App\Models\BudgetDetail;
use App\Models\Commune;
use App\Models\EnteteCommune;
use App\Models\Equipement;
use App\Models\NomenclatureElement;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuiviExecutionParniveau implements FromView,ShouldAutoSize
{
    public $id_nom;
    public $date1;
    public $date2;
    public $sence;
    public $texte;
    public $detail;
    public $niveau;
    public function __construct($id_nom,$date1,$date2,$sence,$texte,$detail,$niveau)
    {
        $this->id_nom=$id_nom;
        $this->date1=$date1;
        $this->date2=$date2;
        $this->sence=$sence;
        $this->texte=$texte;
        $this->detail=$detail;
        $this->niveau=$niveau;
    }
    public function view():View
    {
        $finance = new FinanceLocaleController();
        $id = env('APP_COMMUNE');
        $commune = Commune::find($id);
        $entete_id = EnteteCommune::where('commune_id', $id)->get()->first()->id;
        $entete = EnteteCommune::find($entete_id);
        $html = '';
        //non detaille
        if ($this->detail == 0) {
            $colonnes = '<table border="1" width="100%">
                        <tr bgcolor="#add8e6">
                            <td width="10%"><b>' . trans("text_me.compte") . '</b></td>
                            <td width="30%"><b>' . trans("text_me.libelle") . '</b></td>';
            if ($this->sence == 1) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.recettes") . '</b></td>';
            }
            if ($this->sence == 2) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.depences") . '</b></td>';
            }
            $colonnes .= '<td width="30%" align="right"><b>' . trans("text_me.budget") . ' </b></td>
                            <td align="right" width="10%"><b>' . trans("text_me.TauxExecution") . '</b></td>
                        </tr>';
            $html .= $colonnes;
        }
        if($this->detail == 1){
            $colonnes = '<table border="1" width="100%" class="normal">
                        <tr bgcolor="#add8e6">
                            <td width="15%"><b>' . trans("text_me.date") . '</b></td>
                            <td width="30%"><b>' . trans("text_me.libelle") . '</b></td>';
            if ($this->sence == 1) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.origine") . '</b></td>';
            }
            if ($this->sence == 2) {
                $colonnes .= '<td align="center" width="20%"><b>' . trans("text_me.beneficiare") . '</b></td>';
            }
            $colonnes .= '<td width="20%" align=""><b>' . trans("text_me.compte_impitation") . ' </b></td>
                            <td align="" width="15%"><b>' . trans("text_me.montant") . '</b></td>
                        </tr>';
            $html .= $colonnes;
        }

        $annee = $finance->budgetFinalanne();
        $id_budget = $finance->budgetFinalid();

        $conreoller = new EmployeController();
        $titre = '';
        if ($this->sence == 1) {
            $titre = trans("text_me.situation_recette");
        }
        if ($this->sence == 2) {
            $titre = trans("text_me.situation_depence");
        }
        if($this->detail==1){
            $titre =' '. trans("text_me.detailleRecettes");
        }
        $enetet = $conreoller->entete($titre);
        $html1 = $enetet;
        $element = NomenclatureElement::find($this->id_nom);
        $totalBudget = BudgetDetail::where('budget_id', $id_budget)->where('nomenclature_element_id', $this->id_nom)->get()->first()->montant;
        $html1 .= '<br>&nbsp;&nbsp;&nbsp;' . trans('text_me.compte_impitation') . ':<b> ' . $element->libelle . '</b><br>';
        $html1 .= '';
        if ($this->niveau == 'all' and $this->detail==0) {
            $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.niveau_affichage') . ':<b>' . trans('text_me.tous') . '</b>';
        }
        else {
            if ($this->detail==0)
                $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.niveau_affichage') . ':<b>' . $this->niveau . '</b>';
        }
        if ($this->texte == 'all') {
            $texte = '';
            $html1 .= '<br>';
        } else {
            $html1 .= '&nbsp;&nbsp;' . trans('text_me.beneficiaire') . ':</b>' . $this->texte . '</b>';
            $html1 .= '<br>';
        }
        $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.periode') . ' ' . trans('text_me.Du') . ': <b>' . Carbon::parse($this->date1)->format('d-m-Y') . '</b> &nbsp;&nbsp;' . trans('text_me.Au') . ':  <b>' . Carbon::parse($this->date2)->format('d-m-Y') . '</b><br>';
        $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.budget') . ':  <b>' . number_format((float)$totalBudget,2) . '</b>  &nbsp;&nbsp;';
        if ($this->sence == 1) {
            $html1 .= '' . trans('text_me.recette');
        }
        if ($this->sence == 2) {
            $html1 .= '' . trans('text_me.depence');
        }

        $html1 .= ': <b>' . number_format((float)$finance->getFillsObject($this->id_nom, $this->sence, $this->date1, $this->date2, $texte),2) . '</b><br>';
        $html1 .= '&nbsp;&nbsp;&nbsp;' . trans('text_me.TauxExecution') . ': <b> &nbsp;' . number_format((($finance->getFillsObject($this->id_nom, $this->sence, $this->date1, $this->date2, $texte) / $totalBudget) * 100), 2) . '%</b> <br><br><br>';
        //details recettes
        if ($this->detail == 1){
            $html .=$finance->getFillsObjectDetails($this->id_nom, $this->sence, $this->date1, $this->date2, $texte);
        }
        //non details
        if ($this->detail == 0){
            $html .= '<tr><td colspan="2">' . $element->code . '/' . $element->libelle . '</td> <td  align="right">' . number_format((float)$finance->getFillsObject($this->id_nom, $this->sence, $this->date1, $this->date2, $texte) ,2). '</td> <td  align="right">' . number_format((float)$totalBudget,2) . '</td><td align="right">' . number_format((($finance->getFillsObject($this->id_nom, $this->sence, $this->date1, $this->date2, $texte) / $totalBudget) * 100), 2) . '%</td></tr>';
            $elements = NomenclatureElement::where('parent', $this->id_nom)->get();
            if ($this->niveau != 'all') {
                $array = NomenclatureElement::where('budget_id', $id_budget)->where('niveau', '<=', $this->niveau)
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
                $html .= $finance->showElmtsBudgetOperation($this->id_nom, $niveau = 'all', $array, $id_budget, $this->sence, $this->date1, $this->date2, $texte);
            }
        }
        $html.='</table>';
        $view = \View::make('finances.exports.suiviExecution', ['html' => $html, 'commune' => $commune]);
        $html_content = $view->render();
        $html1 .= $html_content ;

        return view('finances.exports.suiviExecutionParniveau',['html1' =>$html1]);
    }
}
