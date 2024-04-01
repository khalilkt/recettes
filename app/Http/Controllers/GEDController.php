<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeurRequest;
use App\Http\Requests\userRequest;
use App\Models\Employeur;
use App\Models\Agent;
use App\Models\Pay;
use App\Models\RefCommune;
use App\Models\Secteur;
use App\Models\Agence;
use App\Models\RefEtatEmployeur;
use App\Models\Formation;
use App\Models\Ged;
use App\Models\RefTypesDocument;
use App\Models\Offre;
use App\Models\DemendeurEmplois;
use App\Models\Etude;
use App\Models\Experience;
use App\Models\RefEtatOffre;

use DataTables;
use App\User;
use App;
use File;
use Auth;

class GEDController extends Controller
{
    private $module = 'employeurs';

    public function __construct()
    {
        $this->middleware('auth');
    }

    //begin GED
    public function get_document($id_objet, $type_objet)
    {
        $types_docum = RefTypesDocument::all();
        $destination = "/files";
        switch ($type_objet) {
            case 1 : //employeur
                $objet = App\Models\Employe::find($id_objet);
                $tilte = " de l'employé : $objet->prenom  $objet->nom $objet->nom_famille";
                break;
            case 2 : //depence
                $objet = App\Models\Depense::find($id_objet);
                $tilte = " de la depense : " . '" ' . $objet->libelle . ' " ';
                break;
            case 3 : //reccete
                $objet = App\Models\Recette::find($id_objet);
                $tilte = " de recette : " . '" ' . $objet->libelle . ' " ';
                break;
            case 8 : //courriers
                $objet = App\Models\Courrier::find($id_objet);
                $tilte = " du courrier : " . '" ' . $objet->titre . ' " ';
                break;
            case 9 : //Image  emplacement
                $objet = App\Models\Emplacement::find($id_objet);
                $tilte = " du emplacement : " . '" ' . $objet->titre . ' " ';
                break;
            case 10 : //Image  equipement
                $objet = App\Models\Contribuable::find($id_objet);
                $tilte = " du contrubiable : " . '" ' . $objet->titre . ' " ';
                break;

            case 4 : //DE
                $objet = DemendeurEmplois::find($id_objet);
                $tilte = " demendeur d'empois  : " . '" ' . $objet->nom . ' ' . $objet->penom . ' " ';
                $destination = "/demendeurs/$id_objet";
                break;
            case 5 : //Etude
                $objet = Etude::find($id_objet);
                $tilte = " de l'etude : " . '" ' . $objet->libelle . ' " ';
                $id_de = $objet->demendeur_emploi_id;
                $destination = "/demendeurs/$id_de";
                break;
            case 5 : //Experinece
                $objet = Experience::find($id_objet);
                $tilte = " de l'expérience : " . '" ' . $objet->libelle . ' " ';
                $id_de = $objet->demendeur_emploi_id;
                $destination = "/demendeurs/$id_de";
                break;

            default:
                # code...
                break;
        }

        return view('ged.get_document', ['objet' => $objet, 'type_objet' => $type_objet, 'tilte' => $tilte, 'types_docum' => $types_docum, 'destination' => $destination]);
    }

    public function addDocument(Request $request)
    {
        $this->validate($request, [
            // 'libelle' => 'required|unique:ged,libelle',
            'fichier' => 'required',
            'ref_types_documents_id' => 'required',
        ]);

        $gedEXist = Ged::where(['objet_id' => $request->objet_id, 'libelle' => $request->libelle])->get();
        if (count($gedEXist) > 0) {
            return response()->json(['date' => [trans('text_my.liblle_existe')]], 422);
        }
        else {
            $document = new Ged;
            $document->libelle = $request->libelle;
            $document->type = $request->type;
            $document->objet_id = $request->objet_id;
            $document->ref_types_document_id = $request->ref_types_documents_id;
            $document->type_ged = 2;
            $document->emplacement = $request->destination;
            $document->extension = $request->file('fichier')->getClientOriginalExtension();
            $document->taille = $request->file('fichier')->getSize();
            // $document->taille = 100;
            $document->ordre = ($request->ordre) ? $request->ordre : $document::max('ordre') + 1;
            $document->save();
            $imageName = $document->id . '.' . $request->file('fichier')->getClientOriginalExtension();
            $request->file('fichier')->move(
                base_path() . '/public/' . $request->destination, $imageName
            );
            $resultat = array();
            $resultat['objet_id'] = $request->objet_id;
            $resultat['type_objet'] = $request->type;
            $resultat['id'] = $document->id;
            return response()->json($resultat, 200);
        }
    }

    public function getDocuments($id_objet, $type_objet,$selected='all')
    {

        $documents = Ged::where(["objet_id" => $id_objet, 'type_ged' => 2, "type" => $type_objet])->with(['ref_types_document']);
        // dd($documents);
        $documents = $documents->orderBy('id','desc');
        $collection = collect();
       /* foreach ($documents as $document) {
            $actions = '<div class="btn-group">';
            $actions .= '<a href="' . url($document->emplacement . '/' . $document->id . '.' . $document->extension) . '" class="btn btn-sm btn-dark" target="_blank" data-toggle="tooltip" data-placement="top" title="' . trans('text_my.voir_fichier') . '"><i class="fa fa-fw fa-eye" ></i></a>';
            $actions .= '<a href="#" onclick="deleteDocument(' . $document->id . ')"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></a>';
            $actions .= '</div>';
            $collection->push([
                'libelle' => $document->libelle,
                'extention' => $document->extension,
                'type_document' => ($document->type_document()->exists()) ? $document->type_document->libelle : "",
                'taille' => '<div class="text-right">' . number_format($document->taille, 0, ',', ' ') . '</div>',
                'actions' => $actions,
            ]);
        }*/

        if ($selected != 'all')
            $documents = $documents->orderByRaw('ged.id = ? desc', [$selected]);

        return Datatables::of($documents)
            ->addColumn('actions', function ($document)  use($type_objet) {
                $actions = '<div class="btn-group">';
                $actions .= '<a href="' . url($document->emplacement . '/' . $document->id . '.' . $document->extension) . '" class="btn btn-sm btn-dark" target="_blank" data-toggle="tooltip" data-placement="top" title="' . trans('text_my.voir_fichier') . '"><i class="fa fa-fw fa-eye" ></i></a>';
               switch ($type_objet)
               {
                   case 1:
                       if(Auth::user()->hasAccess([6],5))
                           $actions .= '<a href="#" onclick="deleteDocument(' . $document->id . ')"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></a>';
                       break;
                   case 2:
                       if(Auth::user()->hasAccess([4],5))
                           $actions .= '<a href="#" onclick="deleteDocument(' . $document->id . ')"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></a>';
                       break;

                   case 3:
                       if(Auth::user()->hasAccess([4],5))
                           $actions .= '<a href="#" onclick="deleteDocument(' . $document->id . ')"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></a>';
                       break;
                   case 8:
                       if(Auth::user()->hasAccess([7],5))
                           $actions .= '<a href="#" onclick="deleteDocument(' . $document->id . ')"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></a>';
                       break;
                   case 9:
                       if(Auth::user()->hasAccess([1],5))
                           $actions .= '<a href="#" onclick="deleteDocument(' . $document->id . ')"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></a>';
                       break;
                   case 10:
                       if(Auth::user()->hasAccess([1],5))
                           $actions .= '<a href="#" onclick="deleteDocument(' . $document->id . ')"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></a>';
                       break;
               }
                   $actions .= '</div>';
                return $actions;
            })
            ->setRowClass(function ($de) use ($selected) {
                return $de->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['actions', 'taille'])
            ->make(true);
    }

    public function deleteDocument($id)
    {
        /**
         *  name      : deleteDocument
         * parametres :
         * return     : message
         * Descrption :
         */
        $document = Ged::find($id);

        $image_path= $document->emplacement . '/' . $document->id . '.' . $document->extension;

        $this->removeFile($image_path);
        $document->forceDelete();
        return response()->json($document->id, 200);

    }

    public function removeFile($path)
    {
        if(\File::exists(public_path($path))){

            \File::delete(public_path($path));

        }else{

            dd('File does not exists.');

        }

    }
}
