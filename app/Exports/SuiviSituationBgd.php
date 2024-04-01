<?php


namespace App\Exports;


use App\Http\Controllers\EmployeController;
use App\Http\Controllers\FinanceLocaleController;
use App\Models\Budget;
use App\Models\Commune;
use App\Models\EnteteCommune;
use App\Models\Equipement;
use App\Models\NomenclatureElement;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuiviSituationBgd implements  FromView,ShouldAutoSize
{
    public $id_nom;
    public $date1;
    public $date2;
    public $type;
    public $detail;
    public function __construct($id_nom,$date1,$date2,$type,$detail)
{
    $this->id_nom=$id_nom;
    $this->date1=$date1;
    $this->date2=$date2;
    $this->type=$type;
    $this->detail=$detail;
}

    public function view():View
{
    $id_nom=$this->id_nom;
    $date1=$this->date1;
    $date2=$this->date2;
    $type=$this->type;
    $detail=$this->detail;
    $finance = new FinanceLocaleController();
    $detail=0;
    $id = env('APP_COMMUNE');
    $commune = Commune::find($id);
    $entete_id = EnteteCommune::where('commune_id', $id)->get()->first()->id;
    $entete = EnteteCommune::find($entete_id);
    $html = '';

    $annee = $finance->budgetFinalanne();
    $id_budget = $finance->budgetFinalid();

    $conreoller = new EmployeController();
    $titre = '';
    if ($type == 1) {
        $titre = trans("text_me.situation_recette");
    }
    if ($type == 2) {
        $titre = trans("text_me.situation_depence");
    }
    if($detail==1){
        $titre =' '. trans("text_me.detailleRecettes");
    }
    $enetet = $conreoller->entete($titre.' '.$annee);
    $html1 = $enetet;
    //methode
    if ($type==1){
        $classes=NomenclatureElement::where('parent',0)->where('ref_type_nomenclature_id',1)->get();
        foreach ($classes as $cl){
            $html.='<br>'.$finance->showElmtsBudget2Nchois($cl->id, $type, $date1, $date2,$detail);
        }
    }
    if ($type==2){
        $classes=NomenclatureElement::where('parent',0)->where('ref_type_nomenclature_id',2)->get();
        foreach ($classes as $cl){
            //dd();
            $html.=$finance->showElmtsBudget2Nchois($cl->id, $type, $date1, $date2,$detail);
        }
    }
    $view = \View::make('finances.exports.suiviExecution', ['html' => $html, 'commune' => $commune]);
    $html_content = $view->render();
    $html1 .= $html_content ;

    return view('finances.exports.suiviSituationBdg',['html1' =>$html1]);
}

}
