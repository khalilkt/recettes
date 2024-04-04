<?php
namespace App\Http\Controllers;
use App\Models\Budget;
use App\Models\BudgetDetail;
use Cassandra\Date;
use Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Exports\ExportContribuable;
use Carbon\Carbon;
use App\Http\Requests\SuspensionRequest;
use App\Http\Requests\PayementRequest;
use App\Http\Requests\ContribualeRequest;
use App\Models\Famille;
use App\Models\Contribuable;
use App\Models\RefEmplacementActivite;
use App\Models\RefTailleActivite;
use App\Models\Activite;
use App\Models\Module;
use App\Models\DetailsPayement;
use DB;
use App\Models\ForchetteTax;
use Illuminate\Http\Request;
use App\Models\RefTypesFamille;
use App\Models\RefCategorieActivite;
use App\Models\Commune;
use App\Models\Annee;
use App\Models\EnteteCommune;
use App\Models\Mois;
use App\Models\Payement;
use App\Models\MoisService;
use App\Models\ContribuablesAnnee;
use PDF;
use DataTables;
use App\User;
use App;
use Auth;
use File;

class ContribuableController extends Controller
{
    private $module = 'contribuables';
 private $restmontrecouv = 0;

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        $annees=Annee::all();
        $annee=Annee::where('etat',1)->get()->first();
        $date=date('Y-m-d');
        $roles=App\Models\RolesAnnee::where('annee',$annee->annee)->where('etat','<>',2)->get();
        $protocoleEchencess = App\Models\Protocole::where('annee_id',$annee->id)->where('etat',null)->where('dateEch','<',$date)->get();
        $nbrproEchen=$protocoleEchencess->count();

        return view($this->module.'.index',['annees'=>$annees,'annee'=>$annee,'nbrproEchen'=>$nbrproEchen,'roles'=>$roles]);
    }

    public function getDT($type,$selected='all')
    {
        $annee= $this->annee_encours();
        $contribuales = Contribuable::whereIn('id',ContribuablesAnnee::where('annee', $annee)->pluck('contribuable_id'));

        if ($type != 'all') {
            if ($type == 0 or $type == 1 or $type == 'f'){
                if ($type == 0 or $type == 1){
                $contribuales = Contribuable::whereIn('id', ContribuablesAnnee::where('annee', $annee)->where('spontane', $type)->pluck('contribuable_id'));
                 }
                if ($type == 'f'){
                $contribuales = Contribuable::whereIn('id', ContribuablesAnnee::where('annee', $annee)->where('etat', $type)->pluck('contribuable_id'));
                 }
        }
            else{
                $contribuales = Contribuable::whereIn('id', App\Models\RolesContribuable::where('annee', $annee)->where('role_id', $type)->pluck('contribuable_id'));

            }
        }
        if ($selected != 'all')
            $contribuales = $contribuales->orderByRaw('id = ? desc', [$selected])->with('activite','ref_taille_activite','ref_emplacement_activite') ->whereIn('id',ContribuablesAnnee::where('annee', $annee)->pluck('contribuable_id'));
        return DataTables::of($contribuales)

            ->addColumn('actions', function(Contribuable $contribuale) {
                $html = '<div class="btn-group">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$contribuale->id.',\''.$this->module.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                if(Auth::user()->hasAccess(9,4)) {
                    $html .= '<button type="button" class="btn btn-sm btn-warning" onClick="exportcontribuablePDF(' . $contribuale->id . ')" data-toggle="tooltip" data-placement="top" title="' . trans('text_me.editficheContribuable') . '"><i class="fas fa-fw fa-file-pdf"></i></button>';
                }

                if(Auth::user()->hasAccess(9,4)) {
                    $html .= '<button type="button" class="btn btn-sm btn-success" onClick="sutiationcontribuablePDF(' . $contribuale->id . ')" data-toggle="tooltip" data-placement="top" title="Situation Fiscale"><i class="fas fa-fw fa-money-bill-alt"></i></button>';
                }

               if(Auth::user()->hasAccess(9,4)) {
                   $annee= $this->annee_encours();
                   $contrib = ContribuablesAnnee::where('annee', $annee)->where('etat', 'F')->where('contribuable_id', $contribuale->id)->get();
                    if ($contrib->count()>0)
                        $html .= '<button type="button" class="btn btn-sm btn-default" onClick="fichdefermercontribuable(' . $contribuale->id . ')" data-toggle="tooltip" data-placement="top" title="PV Fermerture"><i class="fas fa-fw fa-arrow-circle-down"></i></button>';
                    else
                        $html .= '<button type="button" class="btn btn-sm btn-danger" onClick="fermercontribuable(' . $contribuale->id . ')" data-toggle="tooltip" data-placement="top" title="Fermerture"><i class="fas fa-fw fa-arrow-circle-down"></i></button>';

               }

                if(Auth::user()->hasAccess(9,5)) {
                    $html .= ' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\'' . url($this->module . '/delete/' . $contribuale->id) . '\',\'' . trans('text.confirm_suppression') . '' . $contribuale->libelle . '\')" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></button> ';
                }
                $html .='</div>';
                return $html;
            })
            ->addColumn('nbreRole', function(Contribuable $contribuale) {
                $annee= $this->annee_encours();
                $nbre=0;
                $roles1 = App\Models\RolesAnnee::where('annee', $annee)->get();

                foreach ($roles1 as $role) {
                    $rolecont = $roles = App\Models\RolesContribuable::where('contribuable_id', $contribuale->id)->
                    where('role_id', $role->id)->get();
                    if ($rolecont->count() > 0) {
                        $nbre +=1;
                    }

                }
                $html = $nbre;
                return $html;
            })
            ->addColumn('nbrearticle', function(Contribuable $contribuale) {
                $annee= $this->annee_encours();
                $role=App\Models\RolesContribuable::where('contribuable_id',$contribuale->id)->
                where('annee',$annee)->get()->count();
                $html = $role;
                return $html;
            })
            ->editColumn('article', function(Contribuable $contribuale) {
                $annee= $this->annee_encours();
                $roles=App\Models\RolesContribuable::where('contribuable_id',$contribuale->id)->
                where('annee',$annee)->get();
                // $html = $role;
                $html='';
                foreach ($roles as $role)
                {
                    $html .=''.$role->article.' /';
                }
                return $html;
            })
            ->addColumn('montant', function(Contribuable $contribuale) {
                $annee= $this->annee_encours();
                $role=App\Models\RolesContribuable::where('contribuable_id',$contribuale->id)->
                where('annee',$annee)->get();
                $mt=0;
                foreach ($role as $rol){
                    $mt += $rol->montant;
                }
                $html = $mt;
                return $html;
            })
            ->addColumn('Roles', function(Contribuable $contribuale) {
                $annee= $this->annee_encours();
                $roles=App\Models\RolesContribuable::where('contribuable_id',$contribuale->id)->
                where('annee',$annee)->get();
               // $html = $role;
                $html='';
                foreach ($roles as $role)
                {
                    $librrole=App\Models\RolesAnnee::find($role->role_id)->libelle;
                    $html .=''.$librrole.' :'.$role->montant.' ';
                }
                return $html;
            })
            ->setRowClass(function ($contribuale) use ($selected) {
                return $contribuale->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','Roles','article','montant','nbrearticle','nbreRole','actions'])
            ->make(true);
    }

    public function openficherexcel(Request $request)
    {
        //$path=$request->fichier;
       // dd($path);
       // $extension = $path->getClientOriginalExtension();
       // $extension = $request->file('fichier')->getClientOriginalExtension();
        $cp=$cpenr=0;
        $nom = $request->file('fichier')->getClientOriginalName();
        //dd($extension);
       // $imageName = $document->id . '.' . $request->file('fichier')->getClientOriginalExtension();
        $request->file('fichier')->move(
            base_path() . '/public/files' , $nom
        );
        $path = './files/'.$nom;
        //{ $eau=1; }
        if(isset($request->rolecf)){
        $requete=collect();
        $reader=new Xlsx();
        $role=App\Models\RolesAnnee::where('etat',1)->get();
        if ($role){
            $role_id=$role->first()->id;
        $spreadsheet=$reader->load($path);
        $worksheet=$spreadsheet->getActiveSheet();
        foreach ($worksheet->getRowIterator() as $row)
        {
            $cellIterator= $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $i=0;
            $r='';
            foreach ($cellIterator as $cell){
                if ($i==0) $r .=$cell->getValue();
                else $r .=';'.$cell->getValue();
                $i++;
            }
            $chaine =explode(';',$r);
           if (trim($chaine[0]) !='Article'){
               $spontane=0;
              // dd(' t'.$chaine[1]);
               $verif=Contribuable::where('libelle',$chaine[1])->get();
               if ($verif->count()>0){
                   $cVrtif = ContribuablesAnnee::where('annee',$this->annee_encours())
                       ->where('contribuable_id',$verif->first()->id)->get() ;
                  if($cVrtif->count()>0){
                      $contribuale =  Contribuable::find($verif->first()->id);
                      $spontane=$contribuale->spontane;
                      $ct = ContribuablesAnnee::find($cVrtif->first()->id) ;
                  }
                  else{
                      $contribuale = new Contribuable();
                      $ct =new ContribuablesAnnee() ;
                  }
               }
               else{
                   $contribuale = new Contribuable();
                   $ct =new ContribuablesAnnee() ;
               }
               $annee=$this->annee_encours();
               $contribuale->libelle = $chaine[1];
               $contribuale->libelle_ar = $chaine[1];
               $contribuale->representant = $chaine[1];
               $contribuale->adresse = $chaine[3];
              // $contribuale->telephone = $request->telephone;
               $contribuale->activite_id = 2;
               $contribuale->article = $chaine[0];
               $contribuale->montant = $chaine[4];
               $contribuale->ref_emplacement_activite_id = 1;
               $contribuale->ref_taille_activite_id = 1;
               $contribuale->save();
               if($contribuale->id) {
                   $verifMosiServ=MoisService::where('mois_id',1)->where('annee',$this->annee_encours())->where('contribuable_id',$contribuale->id)->get();
                 if ($verifMosiServ->count()>0){}
                 else{
                   $moisService =new MoisService() ;
                   $moisService->mois_id = 1;
                   $moisService->annee = $this->annee_encours();
                   $moisService->contribuable_id = $contribuale->id;
                   $moisService->save();
                 }
                   $annee=$this->annee_encours();
                   $ct->annee = $annee;
                   $ct->contribuable_id = $contribuale->id;
                   $ct->save();
               }
               if ($spontane==1){
                   $cp +=1;
                 //  echo '<br>cet contribuable est deja paye spontanne'.$chaine[1];
                   $gcontribualeRole=new App\Models\GardeRolesContribuable();
                   $gcontribualeRole->contribuable_id=$contribuale->id;
                   $gcontribualeRole->role_id=$role_id;
                   $gcontribualeRole->annee=$this->annee_encours();
                   $gcontribualeRole->anneerel=$chaine[6];
                   $gcontribualeRole->montant=$chaine[4];
                   $gcontribualeRole->periode=$chaine[2];
                   $gcontribualeRole->emeregement='Spontanne';
                   $gcontribualeRole->article=$chaine[0];
                   $gcontribualeRole->save();
               }
               else{

               $verifControl=App\Models\RolesContribuable::where('contribuable_id',$contribuale->id)->where('article',trim($chaine[0]))->where('role_id',$role_id)
                   ->where('annee',$this->annee_encours())->get();
               if ($verifControl->count()>0){
                   $cp +=1;
                  //  echo '<br>Cet Article deja entre pour l contribuable'.$chaine[1];
                   $gcontribualeRole=new App\Models\GardeRolesContribuable();
                   $gcontribualeRole->contribuable_id=$contribuale->id;
                   $gcontribualeRole->role_id=$role_id;
                   $gcontribualeRole->annee=$this->annee_encours();
                   $gcontribualeRole->anneerel=$chaine[6];
                   $gcontribualeRole->montant=$chaine[4];
                   $gcontribualeRole->periode=$chaine[2];
                   $gcontribualeRole->emeregement='Repetition';
                   $gcontribualeRole->article=$chaine[0];
                   $gcontribualeRole->save();
               }
               else{
                   $cpenr +=1;
               $contribualeRole=new App\Models\RolesContribuable();
               $contribualeRole->contribuable_id=$contribuale->id;
               $contribualeRole->role_id=$role_id;
               $contribualeRole->annee=$this->annee_encours();
               $contribualeRole->anneerel=$chaine[6];
               $contribualeRole->montant=$chaine[4];
               $contribualeRole->periode=$chaine[2];
               $contribualeRole->adresses=trim($chaine[3]);
               $contribualeRole->emeregement=$chaine[5];
               $contribualeRole->article=$chaine[0];
               $contribualeRole->save();
                 ///  echo '<br>Cet Article est bien enregistre entre pour l contribuable'.$chaine[1];
               }
               }
           }

        }
        echo 'Nombre des lignes='.($cp+$cpenr);
        echo '<br>Nombre des lignes bien enregistres='.$cpenr;
         echo '<br>Nombre des lignes non enregistres='.$cp.'
        <a href="visualiserproblem/'.$role_id.'"  target="_blank">Visulailer ces problemes</a>
            ';
            /*<form role="form" id="formst1" name="formst1">
             <button  class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm text-right" onclick="visualiserproblem('.$role_id.')">
             <i class="fas  fa-eye text-white-50"></i> Visulailer ces problemes</button></form>*/
        }
        else{
            echo 'Il n a pas de role active';
        }
    }
        if(isset($request->rolePATENTE)){


            $requete=collect();
            $reader=new Xlsx();
            $role=App\Models\RolesAnnee::where('etat',1)->get();
            if ($role){
                $role_id=$role->first()->id;
                $spreadsheet=$reader->load($path);
                $worksheet=$spreadsheet->getActiveSheet();
                foreach ($worksheet->getRowIterator() as $row)
                {
                    $cellIterator= $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    $i=0;
                    $r='';
                    foreach ($cellIterator as $cell){
                        if ($i==0) $r .=$cell->getValue();
                        else $r .=';'.$cell->getValue();
                        $i++;
                    }
                    $chaine =explode(';',$r);
                    if (trim($chaine[0]) !='Article'){
                        $spontane=0;
                        // dd(' t'.$chaine[1]);
                        $verif=Contribuable::where('nif',$chaine[1])->get();
                        if ($verif->count()>0){
                            $cVrtif = ContribuablesAnnee::where('annee',$this->annee_encours())
                                ->where('contribuable_id',$verif->first()->id)->get() ;
                            if($cVrtif->count()>0){
                                $contribuale =  Contribuable::find($verif->first()->id);
                                $spontane=$contribuale->spontane;
                                $ct = ContribuablesAnnee::find($cVrtif->first()->id) ;
                            }
                            else{
                                $contribuale = new Contribuable();
                                $ct =new ContribuablesAnnee() ;
                            }
                        }
                        else{
                            $contribuale = new Contribuable();
                            $ct =new ContribuablesAnnee() ;
                        }
                        $annee=$this->annee_encours();
                        $contribuale->nif = $chaine[1];
                        $contribuale->libelle = $chaine[2];
                        $contribuale->libelle_ar = $chaine[2];
                        $contribuale->representant = $chaine[2];
                        $contribuale->adresse = $chaine[1];
                        // $contribuale->telephone = $request->telephone;
                        $contribuale->activite_id = 2;
                        $contribuale->article = $chaine[0];
                        $contribuale->montant = $chaine[7];

                        $contribuale->ref_emplacement_activite_id = 1;
                        $contribuale->ref_taille_activite_id = 1;
                        $contribuale->save();
                        if($contribuale->id) {
                            $verifMosiServ=MoisService::where('mois_id',1)->where('annee',$this->annee_encours())->where('contribuable_id',$contribuale->id)->get();
                            if ($verifMosiServ->count()>0){}
                            else{
                                $moisService =new MoisService() ;
                                $moisService->mois_id = 1;
                                $moisService->annee = $this->annee_encours();
                                $moisService->contribuable_id = $contribuale->id;
                                $moisService->save();
                            }
                            $annee=$this->annee_encours();
                            $ct->annee = $annee;
                            $ct->contribuable_id = $contribuale->id;
                            $ct->save();
                        }
                        if ($spontane==1){
                            $cp +=1;
                            //  echo '<br>cet contribuable est deja paye spontanne'.$chaine[1];
                            $gcontribualeRole=new App\Models\GardeRolesContribuable();
                            $gcontribualeRole->contribuable_id=$contribuale->id;
                            $gcontribualeRole->role_id=$role_id;
                            $gcontribualeRole->annee=$this->annee_encours();
                            $anner=$this->annee_encours();
                            if (trim($chaine[9]) !=''){ $anner=trim($chaine[9]);}
                            $gcontribualeRole->anneerel=$anner;
                            $gcontribualeRole->montant=$chaine[7];
                            $gcontribualeRole->periode=$chaine[4];
                            $gcontribualeRole->emeregement='Spontanne';
                            $gcontribualeRole->article=$chaine[0];
                            $gcontribualeRole->save();
                        }
                        else{

                            $verifControl=App\Models\RolesContribuable::where('contribuable_id',$contribuale->id)->where('article',trim($chaine[0]))->where('role_id',$role_id)
                                ->where('annee',$this->annee_encours())->get();
                            if ($verifControl->count()>0){
                                $cp +=1;
                                //  echo '<br>Cet Article deja entre pour l contribuable'.$chaine[1];
                                $gcontribualeRole=new App\Models\GardeRolesContribuable();
                                $gcontribualeRole->contribuable_id=$contribuale->id;
                                $gcontribualeRole->role_id=$role_id;
                                $gcontribualeRole->annee=$this->annee_encours();
                                $anner=$this->annee_encours();
                                if (trim($chaine[9]) !=''){ $anner=trim($chaine[9]);}
                                $gcontribualeRole->anneerel=$anner;
                                $gcontribualeRole->montant=$chaine[7];
                                $gcontribualeRole->periode=$chaine[4];
                                $gcontribualeRole->emeregement='Repetition';
                                $gcontribualeRole->article=$chaine[0];
                                $gcontribualeRole->save();
                            }
                            else{
                                $cpenr +=1;
                                $contribualeRole=new App\Models\RolesContribuable();
                                $contribualeRole->contribuable_id=$contribuale->id;
                                $contribualeRole->role_id=$role_id;
                                $contribualeRole->annee=$this->annee_encours();
                                $anner=$this->annee_encours();
                                if (trim($chaine[9]) !=''){ $anner=trim($chaine[9]);}
                                $contribualeRole->anneerel=$anner;
                                $contribualeRole->montant=$chaine[7];
                                $contribualeRole->montantDroit=$chaine[5];
                                $contribualeRole->penelite=$chaine[6];
                                $contribualeRole->periode=$chaine[3];
                                $contribualeRole->date_fin=$chaine[4];
                                $contribualeRole->adresses=trim($chaine[1]);
                                $contribualeRole->emeregement=$chaine[8];
                                $contribualeRole->article=$chaine[0];
                                $contribualeRole->save();
                                ///  echo '<br>Cet Article est bien enregistre entre pour l contribuable'.$chaine[1];
                            }
                        }
                    }

                }
                echo 'Nombre des lignes='.($cp+$cpenr);
                echo '<br>Nombre des lignes bien enregistres='.$cpenr;
                echo '<br>Nombre des lignes non enregistres='.$cp.'
        <a href="visualiserproblem/'.$role_id.'"  target="_blank">Visulailer ces problemes</a>
            ';
                /*<form role="form" id="formst1" name="formst1">
                 <button  class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm text-right" onclick="visualiserproblem('.$role_id.')">
                 <i class="fas  fa-eye text-white-50"></i> Visulailer ces problemes</button></form>*/
            }
            else{
                echo 'Il n a pas de role active';
            }
        }

        }
    public function formAdd()
    {
        $activites = Activite::all();
        $tailles = RefTailleActivite::all();
        $emplacements = RefEmplacementActivite::all();
        $maxOrdre = Contribuable::max('id')+1 or 1;
        return view($this->module.'.add',['activites' => $activites, 'tailles' => $tailles, 'emplacements' => $emplacements,'maxOrdre'=>$maxOrdre]);
    }

    public function annulerRole($id){
        $role=App\Models\RolesAnnee::find($id);
        $problemes =App\Models\GardeRolesContribuable::where('role_id',$id)->get();
        if ($role->etat==1){
        foreach ($problemes as $probleme)
        {
            $pr=App\Models\GardeRolesContribuable::find($probleme->id);
            $pr->delete();
        }
        $roles =App\Models\RolesContribuable::where('role_id',$id)->get();
        foreach ($roles as $rol)
        {
            $rr=App\Models\RolesContribuable::find($rol->id);
            $rr->delete();
        }
            return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
        }
    }
   public function visualiserproblem($id){
        $role=App\Models\RolesAnnee::find($id);
        $problemes =App\Models\GardeRolesContribuable::where('role_id',$id)->get();
       $html ='<h2>'.$role->libelle.'<br> Liste des contribuales ayant des problemes</h2>';
        $html.='<table border="1" style="width: 100%">';
       $html .='<tr>';
       $html .='<td style="width: 10%">Article</td>';
       $html .='<td style="width: 30%">Noms ou Raison Sociale </td>';
       $html .='<td style="width: 20%">Période </td>';
       $html .='<td style="width: 15%">Droit  impôts </td>';
      $html .='<td style="width: 35%">Probleme</td>';
       $html .='</tr>';
        foreach ($problemes as $probleme)
        {
            $contru=Contribuable::find($probleme->contribuable_id);
            $html .='<tr>';
            $html .='<td style="width: 10%">'.$probleme->article.'</td>';
            $html .='<td style="width: 30%">'.$contru->libelle.' </td>';
            $html .='<td style="width: 20%">'.$probleme->periode.' </td>';
            $html .='<td style="width: 15%">'.$probleme->montant.' </td>';
           $html .='<td style="width: 35%">'.$probleme->emeregement.' </td>';
            $html .='</tr>';
        }
       $html .='</table>';
        echo $html;
   }


    public function annee_encours(){
        $annee=Annee::where('etat',1)->get()->first()->annee;
        return $annee;
    }
    public function add(ContribualeRequest $request)
    {
        $annee=$this->annee_encours();
        $contribuale = new Contribuable();
        //dd("Y", strtotime($request->date_mas));
        $contribuale->libelle = $request->libelle;
        $contribuale->libelle_ar = $request->libelle_ar;
        $contribuale->representant = $request->representant;
        $contribuale->adresse = $request->adresse;
        $contribuale->telephone = $request->telephone;
        $contribuale->activite_id = 1;
        $contribuale->article = $request->article;
        $contribuale->montant = $request->montant;
        $contribuale->date_mas = $request->date_mas;
        $contribuale->ref_emplacement_activite_id = 1;
        $contribuale->ref_taille_activite_id = 1;
        $contribuale->save();
         if($contribuale->id) {
             $mounth=date("n", strtotime($request->date_mas));
             $day=date("j", strtotime($request->date_mas));
             if($day>15){
                 $mounth +=1;
             }
             $moisService =new MoisService() ;
             $moisService->mois_id = $mounth;
             $moisService->annee = $this->annee_encours();
             $moisService->contribuable_id = $contribuale->id;
             $moisService->save();
             $ct =new ContribuablesAnnee() ;
             $ct->annee = $this->annee_encours();
             $ct->contribuable_id = $contribuale->id;
             $ct->montant = $request->montant;
             $ct->spontane = 1;
             $ct->save();
             $contribualeRole=new App\Models\RolesContribuable();
             $contribualeRole->contribuable_id=$contribuale->id;
             $contribualeRole->role_id=1;
             $contribualeRole->annee=$this->annee_encours();
             $contribualeRole->anneerel=$this->annee_encours();
             $contribualeRole->montant=$request->montant;
             $contribualeRole->periode='';
             $contribualeRole->adresses=$request->adresse;
             $contribualeRole->emeregement='';
             $contribualeRole->article='SP'.$contribuale->id;
             $contribualeRole->save();

         }
        return response()->json($contribuale->id,200);
    }

    public function edit(ContribualeRequest $request)
    {
        $annee=$this->annee_encours();
        $contribuale = Contribuable::find($request->id);
        $contribuale->libelle = $request->libelle;
        $contribuale->libelle_ar = $request->libelle_ar;
        $contribuale->representant = $request->representant;
        $contribuale->adresse = $request->adresse;
        $contribuale->telephone = $request->telephone;

        $contribuale->montant = $request->montant;

        $contribuale->save();

        return response()->json('Done',200);
    }

    public function get($id)
    {
        $contribuale = Contribuable::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $numbers=array(1=>1,2=>2,3=>3,4=>4);

        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
            '<i class="far fa-pencil-ruler"></i> '.trans('text_me.protocoles') => $tablink.'/2',
            '<i class="far fa-money-bill-alt"></i> '.trans('text_me.payements') => $tablink.'/3',
            '<i class="fa fa-file-pdf"></i> '.trans('text_me.document') => $tablink.'/4',
        ];
        $modal_title = '<b>'.$contribuale->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab)
    {
        $annee=$this->annee_encours();
        $contribuale = Contribuable::find($id);
        $activites = Activite::all();
        $tailles = RefTailleActivite::all();
        $emplacements = RefEmplacementActivite::all();
        $nomenclatures=App\Models\NomenclatureElement::where('niveau','<>',1)->where('ref_type_nomenclature_id',1)->get();
        //mois
        $annee_id=Annee::where('etat',1)->get()->first()->id;
        //$date=date('Y-m-d');
        $protocoleEchencess = App\Models\Protocole::where('contribuable_id',$id)->where('annee_id',$annee_id)->get();
        $nbrproEchen=$protocoleEchencess->count();
        switch ($tab) {
            case '1':
                $parametres = ['nomenclatures' => $nomenclatures,'contribuale' => $contribuale,'activites' => $activites, 'tailles' => $tailles, 'emplacements' => $emplacements, 'nbrproEchen' => $nbrproEchen];
                break;
            case '2':
                $parametres = ['contribuale' => $contribuale, 'nbrproEchen' => $nbrproEchen];
                break;
            default :
                $parametres = ['contribuale' => $contribuale, 'nbrproEchen' => $nbrproEchen];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $contribuale = Contribuable::find($id);
        $contribuale->delete();
        return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
    }

    public function annulerPayement($id)
    {
        $payement = App\Models\Payementmens::find($id);
        $role=$payement->role_id;
        $montant=$payement->montant;
        $contribualroleAnnes=App\Models\RolesContribuable::where('role_id',$role)->where('contribuable_id',$payement->contribuable_id)->get();
        foreach ($contribualroleAnnes as $contribualAnne)
        {
            $conAn=App\Models\RolesContribuable::find($contribualAnne->id);
            $montanttt=($conAn->montant_paye-$montant);
            $conAn->montant_paye=$montanttt;
            $conAn->save();
        }
        $annee=Annee::where('etat',1)->get()->first()->annee;
        $contrabeAneness=ContribuablesAnnee::where('annee',$annee)->where('contribuable_id',$payement->contribuable_id)->get();
        foreach ($contrabeAneness as $contrab)
        {
            $ca=ContribuablesAnnee::find($contrab->id);
            $ca->etat=null;
            $ca->save();
        }
        $deteilPayemests=App\Models\DetailsPayementmens::where('payement_id',$id)->get();
        foreach ($deteilPayemests as $deteilPayemes)
        {
            $dp=App\Models\DetailsPayementmens::find($deteilPayemes->id);
            $dp->delete();
        }

        $payement->delete();
        return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
    }
    public function annulerProtocol($id)
    {
        $protocol = App\Models\Protocole::find($id);
        $echances =App\Models\Echeance::where('protocol_id',$id)->get();
        foreach ($echances as $echance)
        {
            $echan=App\Models\Echeance::find($echance->id);
            $echan->delete();
        }
        $annee=Annee::where('etat',1)->get()->first()->annee;
        $roles=App\Models\RolesContribuable::where('contribuable_id',$id)->
        where('annee',$annee)->get();
        foreach ($roles as $role)
        {
            $rol=App\Models\RolesContribuable::find($role->id);
            $rol->protocole_id='';
            $rol->save();
        }
        $protocol->delete();
        return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
    }
    public function selectionnerContribuable($id)
    {
        $returnn=0;
        $annee = $this->annee_encours();
        $contr = Contribuable::find($id);
        $annee_id=Annee::where('etat',1)->get()->first()->id;
        //$date=date('Y-m-d');
        $protocoleEchencess = App\Models\Protocole::where('contribuable_id',$id)->where('annee_id',$annee_id)->get();
        if ($protocoleEchencess->count()>0)
        {
            $returnn=1;
        }
        return $returnn;

    }
    public function montantArticleContribuable($id)
    {
        $role= $roles=App\Models\RolesContribuable::find($id);
        $montant=$role->montant-$role->montant_paye;
        return $montant;
    }
    public function newPayementPv($id)
    {
        $annee=$this->annee_encours();
        $contr=Contribuable::find($id);
        $mois_avmontant_arriere=$contr->montant;
        $roles=App\Models\RolesContribuable::where('contribuable_id',$id)->where('annee',$annee)->get();

        $type_pay=App\Models\RefTypepayement::all();
        $banques=App\Models\RefBanque::all();
        $applications=App\Models\RefApplication::all();
        return view('contribuables.ajax.newPayementPv',['contr' => $contr,'roles' => $roles,'applications' => $applications,'banques' => $banques, 'mois_avmontant_arriere' => $mois_avmontant_arriere, 'type_pay' => $type_pay]);
    }
    public function newPayement($id)
    {
        $banques=App\Models\RefBanque::all();
        $applications=App\Models\RefApplication::all();
        $type_pay=App\Models\RefTypepayement::all();
        $date=Date('Y-m-d');
        $annee=Annee::where('etat',1)->get()->first()->id;
        $protocoles = App\Models\Protocole::where('contribuable_id',$id)->where('annee_id',$annee)->where('etat',null)->get();
        $protocole=$protocoles->first()->id;
        $echances =App\Models\Echeance::where('protocol_id',$protocole)->where('montant','>',0)->where('etat',null)->get();
       // $protocoleEchencess = App\Models\Protocole::where('contribuable_id',$id)->where('annee_id',$annee)->where('etat','')->where('dateEch','<',$date)->get();
       // $nbrproEchen=$protocoleEchencess->count();
        return view('contribuables.ajax.newPayement',['protocole' => $protocole,'applications' => $applications,'banques' => $banques,'echances' => $echances, 'id' => $id,  'type_pay' => $type_pay]);
    }

    public function newprotpcol($id)
    {
        $annee=Annee::where('etat',1)->get()->first()->annee;
        $payement= App\Models\Payementmens::where('contribuable_id',$id)->where('annee',$annee)->orderBy('id', 'DESC')->get();
        $montantP=0;
        $montantRoles=0;
        $montantRest=0;
        if ($payement->count()>0){
        foreach ($payement as $payeme){
            $montantP +=$payeme->montant;
        }
        }

        $roles=App\Models\RolesContribuable::where('contribuable_id',$id)->
        where('annee',$annee)->get();
        $contribuable=Contribuable::find($id);
        foreach ($roles as $role)
        {
            $montantRoles +=$role->montant;
        }
        $montantRest=$montantRoles-$montantP;
        return view('contribuables.ajax.newprotpcol',['montantRest' => $montantRest, 'id' => $id]);
    }

    public function recuperemontant($id){
        $montant=App\Models\Protocole::find($id);
        return $montant->montant_arriere;
    }

    public function recuperemontant1($id,$echeance){
        $montant=App\Models\Echeance::find($echeance);
        return $montant->montant;
    }
    public function getPayementmens1($id,$selected='all'){
        $payements = App\Models\Payementmens::where('contribuable_id', $id)->where('etat','<>',3)->with('role');
        if ($selected != 'all')
            $payements = $payements->where('contribuable_id', $id)->orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($payements)
            ->addColumn('actions', function(App\Models\Payementmens $payements) {
                $html = '<div class="btn-group">';
                if(Auth::user()->hasAccess(9,5)) {
                    $html .= '<button type="button" class="btn btn-sm btn-danger" onClick="annulerPayement(' . $payements->id . ')" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></button>';
                }
                $html .='</div>';
                return $html;
            })
            ->editColumn('date', function(App\Models\Payementmens $payements) {
                return Carbon::parse($payements->date)->format('d-m-Y');
            })
            ->editColumn('etat', function(App\Models\Payementmens $payements) {
                $etat='' . trans('text_me.payer') . '';
                if ($payements->etat ==2)
                {
                    $etat =   '' . trans('text_me.nonpayer') . '';
                }
                return $etat;
            })
            ->setRowClass(function ($payements) use ($selected) {
                return $payements->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }
    public function getPayements($id,$selected='all'){
        $payements = Payement::where('contribuable_id', $id)->with('protocol');
        if ($selected != 'all')
            $payements = $payements->where('contribuable_id', $id)->orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($payements)
            ->addColumn('actions', function(Payement $payements) {
                $html = '<div class="btn-group">';
                if(Auth::user()->hasAccess(9,5)) {
               //     $html .= ' <button type="button" class="btn btn-sm btn-danger" onClick="annulerPayement(' . $payements->id . ')" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></button>';
                }
                 $html .='</div>';
                return $html;
            })
            ->editColumn('date', function(Payement $payements) {
                return Carbon::parse($payements->date)->format('d-m-Y');
            })
            ->editColumn('etat', function(Payement $payements) {
                    $etat='' . trans('text_me.payer') . '';

                return $etat;
            })
            ->setRowClass(function ($payements) use ($selected) {
                return $payements->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function getProtocoles($id,$selected='all'){
        $protocoles = App\Models\Protocole::where('contribuable_id', $id);
        if ($selected != 'all')
            $protocoles = $protocoles->where('contribuable_id', $id)->orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($protocoles)
            ->addColumn('actions', function(App\Models\Protocole $protocoles) {
                $html = '<div class="btn-group">';
                if(Auth::user()->hasAccess(9,5)) {
                    if ($protocoles->etat =='')
                    {
                    $html .= ' <button type="button" class="btn btn-sm btn-danger" onClick="annulerProtocol(' . $protocoles->id . ')" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></button>';
                }
                }
                 $html .='</div>';
                return $html;
            })
            ->editColumn('date', function(App\Models\Protocole $protocoles) {
                return Carbon::parse($protocoles->date)->format('d-m-Y');
            })
            ->editColumn('etat', function(App\Models\Protocole $protocoles) {
                    $etat='' . trans('text_me.payer') . '';
                if ($protocoles->etat =='')
                {
                    $etat =   '' . trans('text_me.nonpaye') . '';
                }
                return $etat;
            })
            ->setRowClass(function ($protocoles) use ($selected) {
                return $protocoles->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function getActvite($activite_id,$emp,$taille)
    {
        $actvite = Activite::find($activite_id);
        $data= ForchetteTax::where('ref_categorie_activite_id',$actvite->ref_categorie_activite_id)->where('ref_emplacement_activite_id',$emp)->where('ref_taille_activite_id',$taille)->get();
        if($data=='[]'){
            $data=RefCategorieActivite::find(2)->get();
        }

        return $data;
    }

    public function saveSuspension(SuspensionRequest $request)
    {
        $mois1 = $request->mois1;
        $mois2 = $request->mois2;
        $id = $request->id;
        $annee=$this->annee_encours();
        $contribuable=Contribuable::find($request->id);
        $contribuable->etat=2;
        $contribuable->save();
        $mois = Mois::whereNotIn('id',Payement::where('contribuable_id', $request->id)->where('annee', $annee)->pluck('mois_id'))->where('mois.id', '>=' , $mois1)->where('mois.id', '<=' , $mois2)->get();
        foreach($mois as $m)
        {
            $payement = new Payement();
            $payement->libelle = ''.trans('text_me.supension').' '.$m->libelle.'';
            $payement->libelle_ar = ''.trans('text_me.supension').' '.$m->libelle.'';
            $payement->annee =$annee;
            $payement->mois_id =$m->id;
            $payement->contribuable_id =$request->id;
            $payement->etat =3;
            $payement->montant =0;
            $payement->date =date('Y-m-d');
            $payement->montant_arriere =0;
            $payement->save();
             }
        return response()->json($payement->id,200);
    }

    public function suspendrePayement($id,$id_mois){
        $annee=$this->annee_encours();
        $mois= Mois::find($id_mois);
        $payement = new Payement();
        $payement->libelle = ''.trans('text_me.supension').' '.$mois->libelle.'';
        $payement->libelle_ar = ''.trans('text_me.supension').' '.$mois->libelle.'';
        $payement->annee =$annee;
        $payement->mois_id =$mois->id;
        $payement->contribuable_id =$id;
        $payement->etat =3;
        $payement->montant =0;
        $payement->date =date('Y-m-d');
        $payement->montant_arriere =0;
        $payement->save();
        $contribuable = Contribuable::find($id);
        $moisService = MoisService::find(MoisService::where('contribuable_id',$id)->where('annee',$annee)->get()->first()->id);
        $mois = Mois::whereNotIn('id',Payement::where('contribuable_id', $id)->where('annee', $annee)->pluck('mois_id'))
            ->where('mois.id', '>=' , $moisService->mois_id)->get();
        $moisSusp = Payement::where('contribuable_id', $id)->where('annee', $annee)->where('etat', 3)->orderBy('mois_id', 'asc')->with('mois')->get();
        return view($this->module . '.ajax.contenuPlaysup', ['contribuable' => $contribuable, 'mois' => $mois, 'moisSusp' => $moisSusp]);

    }

    public function savePayementpv(PayementRequest $request)
    {
        $annee=$this->annee_encours();
        $mt=$request->mt;
        $montantP=str_replace(' ', '', $request->montant);
        $montantCash=(float)($montantP);
        $montantPP=(float)($montantP);
        // $n=$request->id;
         $article=$request->article;
         $roleCont=App\Models\RolesContribuable::find($article);
         if ($request->typePayement==6)
         {
             if ($request->montant!='')
             {
                 $this->validate($request, [
                     // 'fichier' => 'required|max:50000|mimes:doc,docx,xls,xlsx,ppt,pdf,zip',
                     'fichier' =>'required',
                     'fichier.*' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt |max:50000',
                 ]);
                 $montant=$roleCont->montant-$montantPP;
                 $roleCont->montant=$montant;
                 $roleCont->save();
                 $degreve= new App\Models\DegrevementContribuable();
                 $degreve->contribuable_id=$request->id;
                 $degreve->article_id=$article;
                 $degreve->annee=$annee;
                 $degreve->montant=$montantPP;
                 $degreve->decision=$request->decision;
                 $degreve->save();
                 if ($degreve->id)
                 {
                     $files = $request->file('fichier');
                     foreach ($files as $file) {

                         $document = new App\Models\Ged();
                         $document->libelle = $request->decision;
                         $document->type = 10;
                         $document->objet_id = $request->id;
                         $document->ref_types_document_id = 6;
                         $document->type_ged = 2;
                         $document->emplacement = '/courris';
                         $document->extension = $file->getClientOriginalExtension();
                         $document->taille = $file->getSize();
                         // $document->taille = 100;
                         $document->ordre = ($request->ordre) ? $request->ordre : $document::max('ordre') + 1;
                         $document->save();
                         $imageName = $document->id . '.' . $file->getClientOriginalExtension();
                         $file->move(
                             base_path() . '/public/courris', $imageName
                         );
                     }
                 }

             }
             return response()->json($document->id,200);
         }
         else{
        $montant_paye=$montantPP+$roleCont->montant_paye;
        $roleCont->montant_paye=$montant_paye;
        $restmontant=$roleCont->montant-$montant_paye;
        $nomenclature_element_id=App\Models\RolesAnnee::find($roleCont->role_id)->nomenclature_element_id;
        $last_id_budget = Budget::where('annee', $annee)->max('id');

        $element = BudgetDetail::where('budget_id', $last_id_budget)->where('nomenclature_element_id', $nomenclature_element_id)->get()->first();
        //$element = BudgetDetail::where('budget_id', $last_id_budget)->where('nomenclature_element_id', $nomenclature_element_id)->get()->first();
        //dd($element);
        $nomeMontantFinal =$element->montant_realise+$montantPP;
        if ($request->montant!='')
        {
            $payement= new App\Models\Payementmens();

            $n=BudgetDetail::find($element->id);
            $n->montant_realise=$nomeMontantFinal;
            $n->save();
                $mont=0;
                 $payement->libelle = $request->libelle;
                $payement->libelle_ar = $request->libelle_ar;
                $payement->annee =$annee;
                $payement->montant_arriere=$restmontant;
                $payement->montant=$montantPP;
                $payement->etat=2;
                $payement->role_id =$roleCont->role_id;
                $payement->contribuable_id =$request->id;
                $payement->date =$request->date;
                $payement->save();
                if ($payement->id){
                $details=new App\Models\DetailsPayementmens();
                $details->payement_id =$payement->id;
                $details->montant =$montantPP;
                $details->description ='Payement article '.$roleCont->article;

                $modePay=App\Models\RefTypepayement::find($request->typePayement);
                $details->mode_payement =$modePay->mode_payement;
                $details->banque =$request->banque;
                $details->compte =$request->compte;
                $details->num_cheque =$request->numCheque;
                $details->nom_app =$request->nom_app;
                $details->quitance =$request->quitance;
                $details->titre =$request->titre;
                $details->save();
                }
               /* $n=BudgetDetail::find($element->id);
                $n->montant_realise=$nomeMontantFinal;
                $n->save();*/
            $roleCont->save();

        }
             return response()->json($payement->id,200);
         }


    }
    public function savePayement(App\Http\Requests\PayementRequest2 $request)
    {
        $n=$request->id;
        $annee=$this->annee_encours();

        $montantPP=$montantP=str_replace(' ', '', $request->montant);
        if ($montantPP<=0)
            return response()->json(['Exists'=>['LE MONTANTS INCORRECT']],422);
        $annee_id=Annee::where('etat',1)->get()->first()->id;
       // $nomenclature_element_id=Contribuable::find($request->id)->nomenclature_element_id;
        /*if ($nomenclature_element_id==''){

        }
        else{*/
            $role=App\Models\RolesContribuable::where('contribuable_id',$request->id)->
            where('annee',$annee)->get()->first()->role_id;
            $nomenclature_element_id=App\Models\RolesAnnee::find($role)->nomenclature_element_id;
       // }
        $last_id_budget = Budget::where('annee', $annee)->max('id');
        $element = BudgetDetail::where('budget_id', $last_id_budget)->where('nomenclature_element_id', $nomenclature_element_id)->get()->first();
        $nomeMontantFinal =$element->montant_realise+$montantPP;
        $protocol=App\Models\Protocole::find($request->protocol);
        $montantCash=(float)($montantPP);
        $montant_ar=$protocol->montant_arriere-$montantCash;
        $protocol->montant_arriere=$montant_ar;
        if ($request->typePayement==6)
        {
            if ($request->montant!='')
            {

                $this->validate($request, [
                    // 'fichier' => 'required|max:50000|mimes:doc,docx,xls,xlsx,ppt,pdf,zip',
                    'fichier' =>'required',
                    'fichier.*' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt |max:50000',
                ]);
                //$montant=$roleCont->montant-$montantPP;
                $protocolc=App\Models\Protocole::find($request->protocol);
                $protocolc->montantdegv=$protocolc->montant_arriere;
                $protocolc->montant_arriere=$montant_ar;
                $montantpr=$protocolc->montant-$montantCash;
                $protocolc->montant=$montantpr;

                $protocolc->save();
                $montantCashecheance=$montantCash;
                if ($request->echeances_id=='all')
                {
                    $echances =App\Models\Echeance::where('protocol_id',$request->protocol)->get();
                    foreach ($echances as $echance)
                    {
                        if($montantCashecheance>0){
                            $echan=App\Models\Echeance::find($echance->id);
                            $verif=0;
                            $verif=$montantCashecheance-$echan->montant;
                            $echan->montantdegv=$echan->montant;
                            if ($verif>=0){
                                $montantCashecheance=  $montantCashecheance-$echan->montant;
                                $echan->montant=0;

                            }
                            else{
                                $mm=$echan->montant;
                                $echan->montant=$echan->montant-$montantCashecheance;
                                $montantCashecheance=  $montantCashecheance-$mm;
                            }

                            $echan->save();

                        }

                    }
                }
                else{
                    $echan1=App\Models\Echeance::find($request->echeances_id);
                    $echan1->montantdegv=$echan1->montant;
                    $echan1->montant=($echan1->montant-$montantCash);
                    $echan1->save();
                }
                /*$roleCont->montant=$montant;
                $roleCont->save();*/
                $degreve= new App\Models\DegrevementContribuable();
                $degreve->contribuable_id=$request->id;
                $degreve->protocol_id=$request->protocol;
                $degreve->annee=$annee;
                $degreve->montant=$montantPP;
                $degreve->decision=$request->decision;
                $degreve->save();
                if ($degreve->id)
                {
                    $files = $request->file('fichier');
                    foreach ($files as $file) {

                        $document = new App\Models\Ged();
                        $document->libelle = $request->decision;
                        $document->type = 10;
                        $document->objet_id = $request->id;
                        $document->ref_types_document_id = 6;
                        $document->type_ged = 2;
                        $document->emplacement = '/courris';
                        $document->extension = $file->getClientOriginalExtension();
                        $document->taille = $file->getSize();
                        // $document->taille = 100;
                        $document->ordre = ($request->ordre) ? $request->ordre : $document::max('ordre') + 1;
                        $document->save();
                        $imageName = $document->id . '.' . $file->getClientOriginalExtension();
                        $file->move(
                            base_path() . '/public/courris', $imageName
                        );
                    }
                }

            }
            return response()->json($document->id,200);
        }

        else{
        if($montantCash > 0 ){
            $payement = new Payement();
            $payement->libelle = 'Payement du protocol'.$protocol->libelle;
            $payement->libelle_ar = 'Payement du protocol'.$protocol->libelle;
            $payement->annee =$annee;
            $payement->protocol_id =$request->protocol;
            $payement->contribuable_id =$request->id;
            if ($montant_ar==0 or $montant_ar<0)  $payement->etat =2;
            else  $payement->etat =null;
            $payement->montant =$montantCash;
            $payement->date =date('Y-m-d');
            $payement->montant_arriere =$montant_ar;
            $payement->save();
            if ($payement->id){
                $details=new DetailsPayement();
                $details->payement_id =$payement->id;
                $details->montant =$montantCash;
                $details->description =$payement->libelle;
                $modePay=App\Models\RefTypepayement::find($request->typePayement);
                $details->mode_payement =$modePay->mode_payement;
                $details->banque =$request->banque;
                $details->compte =$request->compte;
                $details->num_cheque =$request->numCheque;
                $details->nom_app =$request->nom_app;
                $details->quitance =$request->quitance;
                $details->titre =$request->titre;
                $details->save();
                $n=BudgetDetail::find($element->id);
                $n->montant_realise=$nomeMontantFinal;
                $n->save();
                $protocol->save();
                if ($request->echeances_id=='all')
                {
                    $echances =App\Models\Echeance::where('protocol_id',$request->protocol)->get();
                    foreach ($echances as $echance)
                    {
                        $echan=App\Models\Echeance::find($echance->id);
                        $echan->etat='all';
                        $echan->save();
                    }
                }
                else{
                         $echan1=App\Models\Echeance::find($request->echeances_id);
                         $echan1->montant=($echan1->montant-$montantPP);
                         $echan1->save();
                }
            }

        }
        return response()->json($payement->id,200);
        }
    }

    public function saveProtocol(App\Http\Requests\PayementRequest4 $request)
    {
        $annee=$annee=Annee::where('etat',1)->get()->first()->id;
        $nbre=$request->nbreEch;
        if ($nbre==1){
            $this->validate($request, [
                'date' => 'required',
                'montant1' => 'required'
            ]);
        }
        if ($nbre==2){
            $this->validate($request, [
                'date' => 'required',
                'montant1' => 'required',
                'date2' => 'required',
                'montant2' => 'required',
            ]);
        }
        if ($nbre==3){
            $this->validate($request, [
                'date' => 'required',
                'montant1' => 'required',
                'date2' => 'required',
                'montant2' => 'required',
                'date3' => 'required',
                'montant3' => 'required',
            ]);
        }
        if ($nbre==4){
            $this->validate($request, [
                'date' => 'required',
                'montant1' => 'required',
                'date2' => 'required',
                'montant2' => 'required',
                'date3' => 'required',
                'montant3' => 'required',
                'date4' => 'required',
                'montant4' => 'required',
            ]);
        }
        if ($nbre==5){
            $this->validate($request, [
                'date' => 'required',
                'montant1' => 'required',
                'date2' => 'required',
                'montant2' => 'required',
                'date3' => 'required',
                'montant3' => 'required',
                'date4' => 'required',
                'montant4' => 'required',
                'date5' => 'required',
                'montant5' => 'required',
            ]);
        }
        $montantP=str_replace(' ', '', $request->montant);
        $montantCash=(float)($montantP);
        $montantPrevus=0;
        if ($request->date !=''){
            $montantPrevus += $request->montant1;
        }
        if ($request->date2 !=''){
            $montantPrevus +=$request->montant2;
        }
        if ($request->date3 !=''){
            $montantPrevus +=$request->montant3;
        }
        if ($request->date4 !=''){
            $montantPrevus +=$request->montant4;
        }
        if ($request->date5 !=''){
            $montantPrevus +=$request->montant5;
        }
        if ($montantPrevus !=$montantCash)
            return response()->json(['Exists'=>['LES MONTANTS NON CONFORMES']],422);
            $protocol = new App\Models\Protocole();
            $protocol->libelle = $request->libelle;
            $protocol->annee_id =$annee;
            $protocol->contribuable_id =$request->id;
            $protocol->montant =$montantCash;
            $protocol->dateEch =$request->date;
            $protocol->remarque =$request->remarque;
            $protocol->montant_arriere =$montantCash;
            $protocol->save();
if ($protocol->id){
    if ($request->date !=''){
        $this->insertEcheance($protocol->id,$request->date,$request->montant1);
    }
    if ($request->date2 !=''){
        $this->insertEcheance($protocol->id,$request->date2,$request->montant2);
    }
    if ($request->date3 !=''){
        $this->insertEcheance($protocol->id,$request->date3,$request->montant3);
    }
    if ($request->date4 !=''){
        $this->insertEcheance($protocol->id,$request->date4,$request->montant4);
    }
    if ($request->date5 !=''){
        $this->insertEcheance($protocol->id,$request->date5,$request->montant5);
    }
    $roles=App\Models\RolesContribuable::where('contribuable_id',$request->id)->
    where('annee',$annee)->get();

    foreach ($roles as $role)
    {
        $rr=App\Models\RolesContribuable::find($role->id);
        $rr->protocole_id=$protocol->id;
        $rr->save();
    }
}

        return response()->json($protocol->id,200);
    }
public function insertEcheance($protocol,$date,$montant)
{
    $eche2=new App\Models\Echeance();
    $eche2->protocol_id=$protocol;
    $eche2->dateEch=$date;
    $eche2->montant=$montant;
    $eche2->save();
}

    public function payercontibiable($annee,$type){
        //$module= Module::find(5);
        $annee=$this->annee_encours();
       if ($type == 0 ){
           $contribuables =  App\Models\RolesContribuable::where('annee', $annee)->where('role_id', '<>',1)->get();
        }
        elseif ($type == 1 ){
           $contribuables =  App\Models\RolesContribuable::where('annee', $annee)->where('role_id', 1)->get();
        }
        else{
            $contribuables =  App\Models\RolesContribuable::where('annee', $annee)->where('role_id', $type)->get();
        }
        $mois=Mois::all();
        $type_pay=App\Models\RefTypepayement::all();
        $banques=App\Models\RefBanque::all();
        $applications=App\Models\RefApplication::all();
        return view($this->module . '.ajax.payercontibiable', ['applications' => $applications,'banques' => $banques,'type_pay' => $type_pay ,'annee' => $annee ,'contribuables' => $contribuables,'mois' => $mois]);
    }


    public function suiviContibuable($annee){
        $module= Module::find(5);
        $annee=$this->annee_encours();
       // $contribuables = Contribuable::whereIn('id',ContribuablesAnnee::where('annee', $annee)->pluck('contribuable_id'))->get();
        $mois=Mois::all();
        return view($this->module . '.ajax.filtrage', ['annee' => $annee ,'mois' => $mois,'module' => $module]);
    }

    public function suspension($id){
        $annee=$this->annee_encours();
        $module= Module::find(6);
        $contribuable = Contribuable::find($id);
        $moisService = MoisService::find(MoisService::where('contribuable_id',$id)->where('annee',$annee)->get()->first()->id);
        $mois = Mois::whereNotIn('id',Payement::where('contribuable_id', $id)->where('annee', $annee)->pluck('mois_id'))
            ->where('mois.id', '>=' , $moisService->mois_id)->get();
        return view($this->module . '.ajax.suspension', ['contribuable' => $contribuable,'mois' => $mois,'module' => $module]);
    }

    public function reprendrePayement($id,$id_pay){
        $annee=$this->annee_encours();
        $payement= Payement::find($id_pay);
        $payement->forceDelete();
        $contribuable = Contribuable::find($id);
        $moisService = MoisService::find(MoisService::where('contribuable_id',$id)->where('annee',$annee)->get()->first()->id);
        $mois = Mois::whereNotIn('id',Payement::where('contribuable_id', $id)->where('annee', $annee)->pluck('mois_id'))
            ->where('mois.id', '>=' , $moisService->mois_id)->get();
        $moisSusp = Payement::where('contribuable_id', $id)->where('annee', $annee)->where('etat', 3)->orderBy('mois_id', 'asc')->with('mois')->get();
        return view($this->module . '.ajax.contenuPlaysup', ['contribuable' => $contribuable, 'mois' => $mois, 'moisSusp' => $moisSusp]);
    }

    public function changeAnnee($annee)
    {
        $ans= Annee::all();
        foreach ($ans as $a){
            $an=Annee::find($a->id);
            $an->etat = 0;
            $an->save();
        }
        $an=Annee::find($annee);
        $an->etat = 1;
        $an->save();
        return $ans;
    }

    public function playsup($id){
        $annee=$this->annee_encours();
        $module= Module::find(6);
        $contribuable = Contribuable::find($id);
        $moisService = MoisService::find(MoisService::where('contribuable_id',$id)->where('annee',$annee)->get()->first()->id);
        $mois = Mois::whereNotIn('id',Payement::where('contribuable_id', $id)->where('annee', $annee)->pluck('mois_id'))
            ->where('mois.id', '>=' , $moisService->mois_id)->get();
        $moisSusp = Payement::where('contribuable_id', $id)->where('annee', $annee)->where('etat', 3)->orderBy('mois_id', 'asc')->with('mois')->get();
        return view($this->module . '.ajax.playsup', ['contribuable' => $contribuable, 'mois' => $mois, 'moisSusp' => $moisSusp,'module' => $module]);
    }

    public function getPayementAnnne($annee,$contr='all',$date1='all',$date2='all'){
        $annee=$this->annee_encours();
        $payements =Payement::where('annee',$annee)->where('montant','<>',0)
                        ->select('contribuable_id','date', DB::raw('SUM(montant) as montants'))
                        ->groupBy('contribuable_id')
                        ->groupBy('date');
        if ($contr !='all'){
            $payements = $payements->where('contribuable_id', $contr);
        }
        if ($date1 != 'all' and $date2 != 'all' )
            $payements = $payements->where('date','>=', $date1)->where('date','<=', $date2);
        return DataTables::of($payements)
            ->editColumn('date', function($payements) {
                return Carbon::parse($payements->date)->format('d-m-Y');
            })
            ->addColumn('contribuable', function($payements) {
                $contribuable = Contribuable::find($payements->contribuable_id);
                return $contribuable->libelle;
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function pdfSuiviPayementCtb($annee,$filtrage,$date1,$date2,$role='all')
    {
        if ($filtrage==1)
        {
            $libelleRole='';
            $payementprotocoles = App\Models\Payement::where('annee', $annee)->where('montant','<>',0)->where('date','>=', $date1)->where('date','<=', $date2)->get();
            if ($role !='all')
            {
                $payements = App\Models\Payementmens::where('annee', $annee)->where('role_id', $role)->where('montant','<>',0)->where('date','>=', $date1)->where('date','<=', $date2)->get();
                $roleli=App\Models\RolesAnnee::find($role);
                $libelleRole='<br>Rôle : <b>'.$roleli->libelle.'</b>';
            }
            else{
                $payements = App\Models\Payementmens::where('annee', $annee)->where('montant','<>',0)->where('date','>=', $date1)->where('date','<=', $date2)->get();
            }
        $idc = env('APP_COMMUNE');
        $commune = Commune::find($idc);
        $entete_id = EnteteCommune::where('commune_id', $idc)->get()->first()->id;
        $entete = EnteteCommune::find($entete_id);
        $lib = trans("text_me.suiviContribuable1");
        $conreoller = new EmployeController();
        $enetet = $conreoller->entete(  $lib.' '.$annee);
        $html = '';
        $html .= $enetet;
        if ( ($date1 != 'all' and $date2 != 'all'))
        {
            $html .= '<div class="filter">
                 <table width="100%" >
                    <tr><td>' . trans("text_me.filtrage") . ' :</td></tr>
                    <tr>
                        <td>';

        if ($date1 != 'all' and $date2 != 'all') {
            $html .= ' ' . trans("text_me.Du") . ' <b>' . Carbon::parse($date1)->format('d-m-Y').'</b>';
            $html .= ' ' . trans("text_me.Au") . ' <b>' .  Carbon::parse($date2)->format('d-m-Y').'</b>';
            $html .=$libelleRole;
        }
        $html .= '</td>
                </tr>
                </table>
                </div><br>';
    }
        $montants=0;
        $html .= '<table width="100%"   border="1" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" style="width:22%">
                            <b>Contribuable</b>
                        </td>
                        <td style="width:10%">
                            <b>Adresse</b>
                        </td>
                        <td style="width:18%">
                            <b>Rôle</b>
                        </td>
                        <td style="width:40% ;">
                            <b>Details</b><br><table border="1"style="width: 100%;">
                            <tr>
                <td style="width: 20%;"><b>Descript</b></td>
                <td style="width: 20%;"><b>Montant Payé</b></td>
                <td style="width: 20%;"><b>Date de paiement</b></td>
                <td style="width: 20%;"><b>N°Quittance</b></td>
                <td style="width: 20%;"><b>N°Titre</b></td>
                </tr></table>
                        </td>
                         <td style="width:10%" align="center">
                            <b>Reste à payer </b>
                        </td>
                   </tr>
                    ';
        $montants =0;
        foreach ($payements as $payement)
        {

           $html .=$this->situationpayement($payement->id);
           //dd($payement);
            $montants +=$payement->montant;
        }
        $html .='</tbody> </table>
                 <table border="1">
                 <tr>';
        $html .='<td  align=""><b>'. trans("text_me.total") .'</b></td>';
        $html .='<td align="center"><b>'.strrev(wordwrap(strrev($montants), 3, ' ', true)).'</b></td>';
        $html .='</tr>';
        $html .='
        </table>';
        if ($payementprotocoles->count()>0){
            $html .='<div style="page-break-after: always"></div>';
            $html .= $enetet;
            if ( ($date1 != 'all' and $date2 != 'all'))
            {
                $html .= '<div class="filter">
                 <table width="100%">
                    <tr><td>' . trans("text_me.filtrage") . ' : <b>Payement des protocols</b></td></tr>
                    <tr>
                       <td>';

                if ($date1 != 'all' and $date2 != 'all') {
                    $html .= ' ' . trans("text_me.Du") . '<b>' . Carbon::parse($date1)->format('d-m-Y').'</b>';
                    $html .= ' ' . trans("text_me.Au") . '<b>' .  Carbon::parse($date2)->format('d-m-Y').'</b>';

                }
                $html .= '</td>
                </tr>
                </table>
                </div><br>';
            }
            $html .= '<br> <br> ';
            $html .= '<table width="100%"   border="1" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" style="width:30%">
                            <b>Contribuable</b>
                        </td>
                        <td style="width:10%">
                            <b>Adresse</b>
                        </td>

                        <td style="width:50% ;">
                            <b>Details</b><br><table border="1"style="width: 100%;">
                            <tr>
                <td style="width: 20%;"><b>Descript</b></td>
                <td style="width: 20%;"><b>Montant Payé</b></td>
                <td style="width: 20%;"><b>Date de paiement</b></td>
                <td style="width: 20%;"><b>N°Quittance</b></td>
                <td style="width: 20%;"><b>N°Titre</b></td>
                </tr></table>
                        </td>
                         <td style="width:10%" align="center">
                            <b>Reste à payer </b>
                        </td>
                   </tr>
                    ';
            $montantprotocols =0;
            foreach ($payementprotocoles as $payement)
            {

                $html .=$this->situationpayementprotocol($payement->id);
                //dd($payement);
                $montantprotocols +=$payement->montant;
            }
            $html .='</tbody> </table>
                 <table border="1">
                 <tr>';
            $html .='<td  align=""><b>'. trans("text_me.total") .'</b></td>';
            $html .='<td align="center"><b>'.strrev(wordwrap(strrev($montantprotocols), 3, ' ', true)).'</b></td>';
            $html .='</tr>';
            $html .='
        </table>';}
        }
        if ($filtrage==2)
        {
            $payements = App\Models\DegrevementContribuable::where('annee', $annee)->where('montant','<>',0)->where('created_at','>=', $date1)->where('created_at','<=', $date2)->get();
            $idc = env('APP_COMMUNE');
            $commune = Commune::find($idc);
            $entete_id = EnteteCommune::where('commune_id', $idc)->get()->first()->id;
            $entete = EnteteCommune::find($entete_id);
            $lib = trans("text_me.suiviContribuable2");
            $conreoller = new EmployeController();
            $enetet = $conreoller->entete(  $lib.' '.$annee);
            $html = '';
            $html .= $enetet;
            if ( ($date1 != 'all' and $date2 != 'all'))
            {
                $html .= '<div class="filter">
                 <table width="100%" >
                    <tr><td>' . trans("text_me.filtrage") . ' :</td></tr>
                    <tr>
                        <td>';

                if ($date1 != 'all' and $date2 != 'all') {
                    $html .= ' ' . trans("text_me.Du") . ' <b>' . Carbon::parse($date1)->format('d-m-Y').'</b>';
                    $html .= ' ' . trans("text_me.Au") . ' <b>' .  Carbon::parse($date2)->format('d-m-Y').'</b>';
                }
                $html .= '</td>
                </tr>
                </table>
                </div><br>';
            }
            $montants=0;
            $html .= '<table width="100%"   border="1" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" style="width:30%">
                            <b>Contribuable</b>
                        </td>
                        <td style="width:15%">
                            <b>Adresse</b>
                        </td>
                        <td style="width:40%;">
                            <b>Decision</b>
                        </td>
                         <td style="width:15%">
                            <b>Montant </b>
                        </td>
                   </tr>
                    ';
            $montants =0;
            foreach ($payements as $payement)
            {

               // $html .=$this->situationpayement($payement->id);
                //dd($payement);
                 $html .=$this->situationpayement2($payement->id);
                //
                $montants +=$payement->montant;
            }
            $html .='</tbody> </table>
                 <table border="1">
                 <tr>';
            $html .='<td  align=""><b>'. trans("text_me.total") .'</b></td>';
            $html .='<td><b>'.strrev(wordwrap(strrev($montants), 3, ' ', true)).'</b></td>';
            $html .='</tr>';
            $html .='
        </table>';
        }
        if ($filtrage==3)
        {
            $libelleRole='';
            $start_time_contr  = Carbon::now();

            $contribuables =Contribuable::all();
            $end_time_contr = Carbon::now();
            $time_contr = $end_time_contr->diffInSeconds($start_time_contr);

            $idc = env('APP_COMMUNE');
            $commune = Commune::find($idc);
            $entete_id = EnteteCommune::where('commune_id', $idc)->get()->first()->id;
            $entete = EnteteCommune::find($entete_id);
            $lib = trans("text_me.suivirecouvrement");
            $conreoller = new EmployeController();
            $enetet = $conreoller->entete(  $lib.' '.$annee);
            $html = '';

            $html .= $enetet;
                $html .= '<div class="filter">
                 <table width="100%" >
                    <tr><td>' . trans("text_me.filtrage") . ' :</td></tr>
                    <tr>
                        <td>';
            if ($role !='all')
            {
                $roleli=App\Models\RolesAnnee::find($role);
                $libelleRole='<br>Rôle : <b>'.$roleli->libelle.'</b>';

            }


                $html .= $libelleRole;
                $html .= '</td>
                </tr>
                </table>
                </div><br>';

            $montants=0;
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
            $montants =0;
        //    dd($contribuables);
            $start_time_loop  = Carbon::now();
            foreach ($contribuables as $contribuable)
            {
              //  $listeroles=App\Models\RolesContribuable::where('contribuable_id',)
                if ($role !='all')
                {
                    $roles = App\Models\RolesContribuable::where('contribuable_id', $contribuable->id)->
                            where('annee', $annee)->where('id', $role)->get();
                }
                else{
                    $roles = App\Models\RolesContribuable::where('contribuable_id', $contribuable->id)->
                    where('annee', $annee)->get();
              }

                $montantresr=$montantde=$montant_paye=$montantdgr=0;

                foreach ($roles as $rol){
                    $montantde +=$rol->montant;
                    $montant_paye += $rol->montant_paye;
                }

                $montantresr = $montantde-$montant_paye;

                //if ($montantresr>0){
                    $montants +=$montantresr;
                   // if ($contribuable->id !=72)
                    $html .=$this->contribuablePartie($contribuable->id,$annee,$montantresr,$role);
               // }
            }
            $end_time_loop = Carbon::now();
            $time_loop = $end_time_loop->diffInSeconds($start_time_loop);
            dump(
                "{
                'time_contr' => $time_contr,
                'time_loop' => $time_loop
            }"
            );


            $html .='</tbody> </table>
                 <table border="1">
                 <tr>';
            $html .='<td  align=""><b>'. trans("text_me.total") .'</b></td>';
            $html .='<td><b>'.strrev(wordwrap(strrev($this->restmontrecouv), 3, ' ', true)).'</b></td>';
            $html .='</tr>';
            $html .='
        </table>';
        }
        $html .='<br><br><table><tr><td align="right"><b>'. trans("text_me.signature").'</b></td></tr></table>';
// dd($html);
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle('Contribuable');
        PDF::SetSubject('Contribuable');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('L', 'A4');
        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().'liste_emp.pdf');
    }
    public function contribuablePartie($id,$annee,$montantresr,$role)
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
        if ($contribuable and $rstap>0){
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
            $this->restmontrecouv +=$rstap;
        }
        return $html;
    }
    public function situationpayement2($idpay)
    {
        $payement = App\Models\DegrevementContribuable::find($idpay);
        if ($payement){
         //   dd($payement);
        $contribuable=Contribuable::find($payement->contribuable_id);
        $motantPayes=0;
        $html = '  <tr>
                        <td  align="center">
                            <b>'.$contribuable->libelle.'</b>
                        </td>
                        <td>
                            <b>'.$contribuable->adresse.'</b>
                        </td>
                       ';


            $html .= '
                <td >' . $payement->decision . '</td>
                <td >' . $payement->montant . '</td>
                </tr>
               ';


       return $html;
        }
    }
    public function situationpayement($idpay)
    {
        $payement = App\Models\Payementmens::find($idpay);
        $role=App\Models\RolesAnnee::find($payement->role_id);
        if ($payement){
         //   dd($payement);
        $contribuable=Contribuable::find($payement->contribuable_id);
        $detatPays = App\Models\DetailsPayementmens::where('payement_id', $idpay)->get();
        $motantPayes=0;
        $html = '  <tr>
                        <td  align="center">
                            <b>'.$contribuable->libelle.'</b>
                        </td>
                        <td>
                            <b>'.$contribuable->adresse.'</b>
                        </td>
                         <td>
                            <b>'.$role->libelle.'</b>
                        </td>
                        <td style="width:40% ;">';
        $html1 = '<table border="1"style="width: 100%;">';
        /*$html1 .= '<tr>
                <td style="width: 20%;"><b>Descript</b></td>
                <td style="width: 20%;"><b>Montant Payé</b></td>
                <td style="width: 20%;"><b>Date de paiement</b></td>
                <td style="width: 20%;"><b>N°Quittance</b></td>
                <td style="width: 20%;"><b>N°Titre</b></td>
                </tr>';*/
        foreach ($detatPays as $detatPa) {
            $motantPayes += $detatPa->montant;

            $html1 .= '<tr>
                <td style="width: 20%;">' . $detatPa->description . '</td>
                <td style="width: 20%;">' . $detatPa->montant . '</td>
                <td style="width: 20%;">' . $payement->date . '</td>
                <td style="width: 20%;">' . $detatPa->quitance . '</td>
                <td style="width: 20%;">' . $detatPa->titre . '</td>
                </tr>';
        }
        $html1 .='</table>
        </td>
        <td>'.strrev(wordwrap(strrev($payement->montant_arriere), 3, ' ', true)).'</td>
        </tr>';
        $html .= $html1;
       return $html;
        }
    }
    public function situationpayementprotocol($idpay)
    {
        $payement = App\Models\Payement::find($idpay);

        if ($payement){
         //   dd($payement);
        $contribuable=Contribuable::find($payement->contribuable_id);
        $detatPays = App\Models\DetailsPayement::where('payement_id', $idpay)->get();
        $motantPayes=0;
        $html = '  <tr>
                        <td  align="center">
                            <b>'.$contribuable->libelle.'</b>
                        </td>
                        <td>
                            <b>'.$contribuable->adresse.'</b>
                        </td>

                        <td style="width:50% ;">';
        $html1 = '<table border="1"style="width: 100%;">';
        /*$html1 .= '<tr>
                <td style="width: 20%;"><b>Descript</b></td>
                <td style="width: 20%;"><b>Montant Payé</b></td>
                <td style="width: 20%;"><b>Date de paiement</b></td>
                <td style="width: 20%;"><b>N°Quittance</b></td>
                <td style="width: 20%;"><b>N°Titre</b></td>
                </tr>';*/
        foreach ($detatPays as $detatPa) {
            $motantPayes += $detatPa->montant;

            $html1 .= '<tr>
                <td style="width: 20%;">' . $detatPa->description . '</td>
                <td style="width: 20%;">' . $detatPa->montant . '</td>
                <td style="width: 20%;">' . $payement->date . '</td>
                <td style="width: 20%;">' . $detatPa->quitance . '</td>
                <td style="width: 20%;">' . $detatPa->titre . '</td>
                </tr>';
        }
        $html1 .='</table>
        </td>
        <td>'.strrev(wordwrap(strrev($payement->montant_arriere), 3, ' ', true)).'</td>
        </tr>';
        $html .= $html1;
       return $html;
        }
    }
    public function excelSuiviPayementCtb($annee,$contr,$date1,$date2, $filtrage)
    {
        return Excel::download(new ExportContribuable($annee,$contr,$date1,$date2, $filtrage), ''.trans("text_me.suiviContribuable1").'.xlsx');
    }

    public function exporterListeprotocolEch(){
        $idc = env('APP_COMMUNE');
        $commune = Commune::find($idc);
        $entete_id = EnteteCommune::where('commune_id', $idc)->get()->first()->id;
        $entete = EnteteCommune::find($entete_id);


        $conreoller = new EmployeController();
        $annee=$this->annee_encours();
        $date=Date('Y-m-d');
        $dat1=Date('d-m-Y');
        $enetet = $conreoller->entete(trans("text_me.protocolla_liste").' au date du  '.$dat1);
        $html ='';
        $html .=$enetet;
        $infos='';
        $tablemois='';

        $annee_id=Annee::where('etat',1)->get()->first()->id;
        $tablemois.='
                    <table border="1" class="normal" width="100%">
                    <tr>
                    <th width="45%"><b>'.trans("text_me.nom").'</b></th>
                    <th width="20%"><b>'.trans("text_me.protocole").'</b></th>
                    <th  width="15%"><b>'.trans("text_me.date").'</b></th>
                    <th  width="20%"><b>'.trans("text_me.montant").'</b></th>

                    </tr>';
        $annee= $this->annee_encours();
        $contribuales = Contribuable::whereIn('id',ContribuablesAnnee::where('annee', $annee)->pluck('contribuable_id'))->get();
        foreach ($contribuales as $contribuale)
        {
           $protocoles= App\Models\Protocole::where('contribuable_id',$contribuale->id)->where('annee_id',$annee_id)->where('etat',null)->where('dateEch','<',$date)->get();
     if ($protocoles->count()>0){
         $nbre=$protocoles->count()+1;
         $tablemois.='
                    <tr>
                    <td rowspan="'.$nbre.'" ><b>'.$contribuale->libelle.'</b></td>
                    </tr>';
         foreach ($protocoles as $protocole){
             $tablemois.='
                    <tr>
                    <td width="20%"><b>'.$protocole->libelle.'</b></td>
                    <td  width="15%"><b>'.$protocole->dateEch.'</b></td>
                    <td  width="20%"><b>'.$protocole->montant_arriere.'</b></td>

                    </tr>';
         }

     }
        }
        $tablemois .='</table>';
        $html .=$tablemois;
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle('Contribuable');
        PDF::SetSubject('Contribuable');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().'liste_emp.pdf');
    }

    public function exportcontribuablePDF($id){
        $idc = env('APP_COMMUNE');
        $commune = Commune::find($idc);
        $entete_id = EnteteCommune::where('commune_id', $idc)->get()->first()->id;
        $entete = EnteteCommune::find($entete_id);
        $contribuable = Contribuable::find($id);
        $lib=$contribuable->libelle;
        $conreoller = new EmployeController();
        $annee=$this->annee_encours();
        $enetet = $conreoller->entete(trans("text_me.ficheContribuable").' '.$lib.'<br>'.trans("text_me.annee").' '.$annee);
        $html ='';
        $html .=$enetet;
        $infos='';

        $infos .= '<table width="100%"   border="1" class="normal">
                    <tr>
                        <td colspan="2"  height="3%" align="center" bgcolor="#add8e6"> ' . trans("text.info") . '</td>
                    </tr>
                    <tr>
                        <td colspan="2" >
                            <b>' . trans('text_me.nom') . '</b>: ' . $contribuable->libelle . '
                        </td>
                    </tr>
                     <tr>
                         <td style="width: 50%">
                             <b>' . trans("text_me.adresse") . '</b>: ' . $contribuable->adresse . '

                         </td>
                         <td style="width: 50%">
                             <b>' . trans("text_me.telephone") . '</b>: ' . $contribuable->telephone . '

                         </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        <b>' . trans("text_me.representant") . '</b>: ' . $contribuable->representant . '
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%" >
                            <b>' . trans('text_me.activite') . '</b>: ' . $contribuable->activite->libelle . '
                        </td>
                        <td style="width: 50%">
                             <b>' . trans('text_me.montant') . '</b>: ' . number_format((float)($contribuable->montant),2) . '
                        </td>
                    </tr>
                     <tr>
                        <td style="width: 50%" >
                            <b>' . trans('text_me.emplacement') . '</b>: ' . $contribuable->ref_emplacement_activite->libelle . '
                        </td>
                        <td style="width: 50%">
                            <b>' . trans("text_me.taille") . '</b>: ' . $contribuable->ref_taille_activite->libelle  . '
                        </td>
                    </tr>
                    </table>';
        $html .=$infos;
        $tablemois='';
        $annee=$this->annee_encours();
        $annee_id=Annee::where('etat',1)->get()->first()->id;
        $protocoles = App\Models\Protocole::where('contribuable_id',$id)->where('annee_id',$annee_id)->get();
        $montants=$montantsgen=0;
        $tablemois.='<br><br><b>'. trans("text_me.protocolespay").'</b><br>
                    <table border="1" class="normal" width="100%">
                    <tr>
                    <th width="20%"><b>'.trans("text_me.protocole").'</b></th>
                    <th width="45%"><b>'.trans("text_me.description").'</b></th>
                    <th  width="20%"><b>'.trans("text_me.montant").'</b></th>
                    <th  width="15%"><b>'.trans("text_me.payements").'</b></th>
                    </tr>';
                    foreach ($protocoles as $protocole)
                    {
                        $montants=0;
                        $payement= Payement::where('contribuable_id', $id)->where('protocol_id', $protocole->id)->where('annee', $annee)->get();
                        $rwsp=$payement->count();
                        $tablemois.='<tr>
                                    <td colspan="4" align="">-'.$protocole->libelle.' date d écheance est :'.$protocole->dateEch.'</td></tr>';
                        $description='';
                        $etatpayement='';
                        $montant='';
                       // dd($payement);
                        if ($payement !=null)
                        {
                            foreach ($payement as $p)
                            {
                                $tablemois.='<tr><td colspan="2" align="center">'.$p->libelle.' effectué le : '.$p->created_at.'</td><td>'.$p->montant.'</td><td></td></tr>';
                                $description .=  $p->libelle.' <br>';
                                $montant=$p->montant;
                                $montants +=$montant;
                                $montantsgen +=$montant;

                            }
                        }
                        $tablemois.='<tr>

                                    <td colspan="3" align="right"><b>Total payés</b></td>
                                    <td align="right">'.number_format((float)($montants),2).'</td>

                                    </tr>';
                    }
        $tablemois.='<tr><td colspan="2" align=""><b>Total des payements</b></td><td align="right" colspan="2">'.number_format((float)($montantsgen),2).'</td></tr>';
        $tablemois.='<tr><td colspan="2" align=""><b>Total reste</b></td><td align="right" colspan="2">'.number_format((float)($contribuable->montant-$montantsgen),2).'</td></tr>

                    </table><br><br><br>';
        $tablemois.='<table><tr><td align="right"><b>'. trans("text_me.signature").'</b></td></tr></table>';
        $html .=$tablemois;
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle('Contribuable');
        PDF::SetSubject('Contribuable');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().'liste_emp.pdf');
    }

    public function sutiationcontribuablePDF($id,$ann)
    {
        $idc = env('APP_COMMUNE');
        $commune = Commune::find($idc);
        $entete_id = EnteteCommune::where('commune_id', $idc)->get()->first()->id;
        $entete = EnteteCommune::find($entete_id);
        $contribuable = Contribuable::find($id);
        $lib = $contribuable->libelle;
        $conreoller = new EmployeController();
        $annee = Annee::find($ann)->annee;
        $enetet = $conreoller->entete('Situation Fiscale');
        $html = '';
        $html .= $enetet;
        $impots = 'CF';
        $montantdue = 0;
        $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
        where('annee', $annee)->get();
        $degrevements = App\Models\DegrevementContribuable::where('contribuable_id', $id)->where('annee', $annee)->get();
        foreach ($degrevements as $deg)
        {
            $montantdue +=$deg->montant;
        }
        // $html = $role;

        foreach ($roles as $role) {
            if ($role->emeregement!=''){
                $impots =$role->emeregement;
            }
        }
        $html .= '<table width="100%"   border="1" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="7"  height="3%" align="center" bgcolor="#add8e6"> ' . trans("text.info") . '</td>
                    </tr>
                    <tr>
                        <td style="width: 22%;" align="center">
                            <b>Nom</b>
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
                        <td style="width:10% ;">
                            <b>Article</b>
                        </td>
                        <td style="width:25% ;" align="center">
                            <b>Rôle</b>
                        </td>
                        <td style="width:13% ;" >
                            <b>Montant due</b>
                        </td>
                    </tr>
                     <tr>
                        <td style="width: 22%;">
                            <b> ' . $contribuable->libelle . '</b>
                        </td>
                        <td style="width:10% ;" align="center">
                            ' . $contribuable->adresse . '
                        </td>
                        <td style="width:10% ;" align="center">
                           '.$impots.'
                        </td>
                        <td style="width:10% ;">
                            ' . $annee . '
                        </td>
                        <td style="width:10% ;">';
        $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
        where('annee', $annee)->get();
        // $html = $role;
        //$montantdue = 0;
        foreach ($roles as $role) {
            if ($role->emeregement!=''){

            }
            $html .= '' . $role->article . '<br> ';
            $montantdue += $role->montant;
        }
        $html .= ' </td>
        <td style="width:25% ;"> ';
        $roles1 = App\Models\RolesAnnee::where('annee', $annee)->get();

        // $html = $role;

        foreach ($roles1 as $role) {
            $rolecont = $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
            where('role_id', $role->id)->get();
            if ($rolecont->count() > 0) {
                $html .= '' . $role->libelle . '<br> ';
            }

        }
        $html .= ' </td>
                        <td style="width:13% ;">
                            <b>' . $montantdue . '</b>
                        </td>
                    </tr>
                    ';

        $tablemois = '';
        $annee = $this->annee_encours();
        $html .= '</table><br><br><br>';
        $montantdegr = 0;
        $motantPayes = 0;
        $annee_id = Annee::where('etat', 1)->get()->first()->id;
        $payements = Payement::where('contribuable_id', $id)->where('annee', $annee)->get();
        $payementNrs = App\Models\Payementmens::where('contribuable_id', $id)->where('annee', $annee)->get();
        $html1 = '<table border="1"style="width: 100%;">';
        $html1 .= '<tr>
                <td style="width: 25%;"><b>Montant Payé</b></td>
                <td style="width: 25%;"><b>Date de paiement</b></td>
                <td style="width: 25%;"><b>N°Quittance</b></td>
                <td style="width: 25%;"><b>N°Titre</b></td>
</tr>';
        foreach ($payements as $payement) {
            $detatPays = DetailsPayement::where('payement_id', $payement->id)->get();
            foreach ($detatPays as $detatPa) {
                $motantPayes += $detatPa->montant;

                $html1 .= '<tr>
                <td style="width: 25%;">' . $detatPa->montant . '</td>
                <td style="width: 25%;">' . $detatPa->created_at . '</td>
                <td style="width: 25%;">' . $detatPa->quitance . '</td>
                <td style="width: 25%;">' . $detatPa->titre . '</td>
                </tr>';
            }

        }
        foreach ($payementNrs as $payement) {
            $detatPays = App\Models\DetailsPayementmens::where('payement_id', $payement->id)->get();
            foreach ($detatPays as $detatPa) {
                $motantPayes += $detatPa->montant;

                $html1 .= '<tr>
                <td style="width: 25%;">' . $detatPa->montant . '</td>
                <td style="width: 25%;">' . $payement->date . '</td>
                <td style="width: 25%;">' . $detatPa->quitance . '</td>
                <td style="width: 25%;">' . $detatPa->titre . '</td>
                </tr>';
            }

        }
        $html1 .= '</table><br><br>';

        $html .= $html1;
       if ($degrevements->count()>0){
        $html2 = '<br><br><table border="1"style="width: 100%;">';
        $html2 .= '<tr>
                <td style="width: 33%;"><b>Montant dégrevé</b> </td>
                <td style="width: 34%;"><b>N°Décision</b></td>
                <td style="width: 33%;"><b>Date</b></td>
               </tr>';

        foreach ($degrevements as $degrevement) {
            $montantdegr += $degrevement->montant;
            $html2 .= '<tr>
                            <td style="width: 33%;">' . $degrevement->montant . ' </td>
                            <td style="width: 34%;">' . $degrevement->decision . '</td>
                            <td style="width: 33%;">' . $degrevement->created_at . '</td>
                           </tr>';
        }
        $html2 .= '</table><br><br>';
        $html .= $html2;
    }
        $html.='<table><tr><td align="">Reste à payer  = '.($montantdue -$motantPayes-$montantdegr).'</td></tr></table>';
        $html.='<br><br><br><table><tr><td align="">Fait à Nouadhibou, le :  '.date('d-m-Y').'</td></tr></table>';
        $tablemois.='<br><br><br><br><table><tr><td align="right"><b>'. trans("text_me.signature").'</b><br><br><b></b></td></tr></table>';
        $html .=$tablemois;
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle('Contribuable');
        PDF::SetSubject('Contribuable');
        PDF::SetMargins(8, 8, 8);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('9px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('dejavusans', '', 9);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().'liste_emp.pdf');
    }

    public function fichdefermercontribuable($id)
    {
        $idc = env('APP_COMMUNE');
        $commune = Commune::find($idc);
        $entete_id = EnteteCommune::where('commune_id', $idc)->get()->first()->id;
        $entete = EnteteCommune::find($entete_id);
        $contribuable = Contribuable::find($id);
        $lib = $contribuable->libelle;
        $conreoller = new EmployeController();
        $anneeen=Annee::where('etat',1)->get()->first();
        $annee = $anneeen->annee;
        $enetet = $conreoller->entete('<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>PROCES-VERBAL DE FERMETURE, DE MAGASINS,<BR>BOUTIQUES, ENTRPOTS REPRESENTATIONS <BR> OU BUREAUX');
        $html = '';
        $html .= $enetet;
        $agence=App\Models\Secteur::where('ordre',1)->get()->first();
        $html .='<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br><div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .='L\'an '.$anneeen->description.',le   '.date('d-m-Y').', </div>';
        $html .='<div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Nous <b>'.$agence->libelle.'</b>, Agent de poursuite assermenté en service à la perception de  <b>TR NDB</b> , ';
        $html .='</div>';

        $html .='<br><div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
En application de l\'article L.97 du  Code  Général  des Impôts (Loi N<sup><u>o</u></sup> 2019-18 du 29 Avril 2019 )<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;avons procédé à la fermeture des magasins,boutiques ou entrepôts propriétés de M.<b>'.$contribuable->libelle.'</b>';
        $html .='</div>';
        $divers='';
        $impots = 'CONTRIBUTION FONCIERE ';

        $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
        where('annee', $annee)->get();

        foreach ($roles as $role) {
            if ($role->emeregement!=''){
                $impots =$role->emeregement;
            }

        }
        if($roles->count()>1)
        {
            $divers='Divers Articles';
        }
        else{
            if ($roles->count()>0)
            {
                $role=App\Models\RolesAnnee::find($roles->first()->role_id);
                $divers='Article '.$roles->first()->article.' / '.$role->libelle.' / EX '.$annee ;
            }

        }
        $restapqye=0;

        $obje=new ProgrammesController();
        $restapqye=$obje->contribuableRestApaye($id,$annee);
       // $html .='<br><br><div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$impots.' '.$annee.' . . . . </b></div>';
        $html .='<br><div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Redevable à la perception de <b>TR NDB </b> de la somme de <b>'.strrev(wordwrap(strrev($restapqye), 3, ' ', true)).'</b> ouguiya au titre d\'impôts  <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>'.$impots.' '.$annee.'</b>, <br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <b>'.$divers.' </b>
 <br><br>
&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  </div>';
//<b>PCA</b> ...Propriétaire , gardien de sa boutique ou de son magasin ou de son entrepôts.
 $html .='<br><br><br><br><br><div align="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
L\'Opération ci-dessus a été effectuée en  présence de:</div>';
        $html .='<br><div align="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

</div>';

       /* $html .='<br><div align="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Fait et cios le présent procés-verbal que le propriétaire,le reprèsentant de la<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
police ont signé avec nous les jours n mois et ans ci-dessus</div><br>';*/

        $html .='<br><br><br><br><br><br><table border="0" style="width: 100%;"><tr>
<td><b>LE PROPRIETAIRE</b></td>
<td align="center"><b>LE REPRESENTANT <br>DE LA POLICE</b></td>
<td align="right"><b>L\'AGENT DE POURSUITE</b></td>
</tr></table>';
        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle('Contribuable');
        PDF::SetSubject('Contribuable');
        PDF::SetMargins(8, 8, 8);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('9px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('dejavusans', '', 9);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().'liste_emp.pdf');
    }

    public function fermercontribuable($id){
        $annee= $this->annee_encours();
        $contribuales = ContribuablesAnnee::where('annee', $annee)->where('contribuable_id', $id)->get();
        foreach ($contribuales as $contribuale)
        {
            $cont=ContribuablesAnnee::find($contribuale->id);
            $cont->etat='F';
            $cont->save();
        }
    }
}
