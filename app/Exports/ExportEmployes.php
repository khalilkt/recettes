<?php
namespace App\Exports;
use App\Models\Employe;
use App\Models\Equipement;
use App\Models\RefGenre;
use App\Models\RefSituationFamilliale;
use App\Models\RefTypesContrat;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Http\Controllers\EmployeController;

class ExportEmployes implements FromView,ShouldAutoSize
{
    public $type;
    public $genre;
    public $sit_fam;
    public function __construct($type,$genre,$sit_fam)
    {
        $this->type=$type;
        $this->genre=$genre;
        $this->sit_fam=$sit_fam;
    }

    public function view():View
    {
        $prefix = new EmployeController();
        $type=$this->type;
        $genre=$this->genre;
        $sit_fam=$this->sit_fam;
        $employes = Employe::with('ref_genre', 'ref_types_contrat', 'ref_situation_familliale');

//  dd($employes=Employe::al);
        $f_genre = trans('text_hb.all');
        $f_type = trans('text_hb.all');
        $f_sit_fam = trans('text_hb.all');
        // $lib=trans('text_hb.libelle');
        $lib =trans('text_me.lib');
        $titre = "Liste des empoyes";
        $entete = $prefix->entete($titre, 'L');

        $header = array('nom et prénom', 'Genre', 'Situation Fam', 'Date de naissance', 'lieu de naissance', 'Type contrat', 'Function', 'Service');
        //$header =array('nom et prénom','Genre','Situation Famillaile','Date de naissance');

        //dd($liste_emp)
        if ($genre != 'all') {
            $employes = $employes->where('ref_genre_id', $genre);
            $f_genre = RefGenre::find($genre)->$lib;
        }
        if ($type != 'all') {
            $employes = $employes->where('ref_types_contrat_id', $type);
            $f_type = RefTypesContrat::find($type)->$lib;
        }
        if ($sit_fam != 'all') {
            $employes = $employes->where('ref_situation_familliale_id', $sit_fam);
            $f_sit_fam = RefSituationFamilliale::find($sit_fam)->$lib;
        }

        $employes = $employes->get();

        $liste_emp = $prefix->liste_employes1($header, $employes);

        $filter = $prefix->filter_export($f_type, $f_genre, $f_sit_fam);
       $html=$entete;
       $html .= '<table><tr><td>'.trans('text_me.filtre').'</td></tr></table>';
       $html .=$filter;
       $html .=$liste_emp;
      // dd($html);
        return view('employes.exports.export_resultat',['html1' =>$html]);
    }
}
