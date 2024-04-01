@extends('portail/layout_export')
@section('page-title')
    Export pdf
@endsection
@section('page-content')
    <?php  $path = $commune->path_carte;
    $text_right = trans("text.text_right");
    $position = trans("text.position_right");
    $direction = trans("text.direction");
    $libelle = trans("text.libelle_base");

    ?>

    @if(App::isLocale('ar'))
        <link href="{{ URL::asset('css/test.css') }}" rel="stylesheet">
    @endif
    <div class="exp_info">

        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    <table>

                        @if($niveau == 1)
                            <tr>
                                <td><b>{{ trans("text.commune") }}: </b></td>
                                <td>{{ $commune->$libelle }}</td>
                            </tr>
                            <tr>
                                <td></i> <b>{{ trans("text.moughataa") }}: </b></td>
                                <td> {{ $commune->get_moughataa->$libelle }}</td>
                            </tr>
                            <tr>
                                <td><b>{{ trans("text.wilaya") }}: </b></td>
                                <td>{{ $commune->get_moughataa->get_wilaya->$libelle }}</td>
                            </tr>
                            <tr>
                                <td></i> <b> {{ trans("text.population") }}: </b></td>
                                <td><?php echo strrev(wordwrap(strrev($commune->nbr_habitans), 3, ' ', true));  ?></td>
                            </tr>
                            <tr>
                                <td><b>{{ trans("text.surface") }} :</b></td>
                                <td>{{ $commune->surface }} km&sup2;</td>
                            </tr>
                            @if(env('DCS_APP') == '0' ))
                            <tr>
                                <td><b>{{ trans("text.nbr_village") }} :</b></td>
                                <td>{{ $commune->nbr_villages_localites }}</td>
                            </tr>
                            <tr>
                                <td><b>{{ trans("text.nbr_cons") }} : </b></td>
                                <td>{{ $commune->nbr_conseillers_municipaux }}</td>

                                <td></td>
                            </tr>
                            <tr>
                                <td><i></i> <b>{{ trans("text.nbr_emp_per") }} : </b></td>
                                <td> {{  $commune->nbr_employes_municipaux_permanents }}</td>
                            </tr>
                            <tr>
                                <td><i></i> <b>{{ trans("text.pndidlle") }} : </b></td>
                                <td><?php if ($commune->pnidlle == 1) echo trans("text.oui"); else echo trans("text.non") ?></td>

                            </tr>
                            @endif

                            <tr>
                                <td><b> {{ trans("text.decret") }} : </b></td>
                                <td> {{ $commune->decret_de_creation }}</td>
                            </tr>

                        @elseif($niveau==6)
                            <tr>
                                <td><b>{{ $commune->get_type_objet->$libelle  }} : </b></td>
                                <td>{{ $commune->libelle }}</td>
                            </tr>
                            @php
                            switch ($commune->get_type_objet->niveau_admin_geo ) {
                            case 1:
                            $set_niveau_objet = App\Models\ref_communes::find($commune->object_id);
                            $libe_commune = $set_niveau_objet->$libelle ;
                            @endphp
                            <tr>
                                <td><b>{{ trans("text.commune") }} : </b></td>
                                <td>{{ $libe_commune }}</td>
                            </tr>
                            @php
                            break;
                            case 2 :
                            $set_niveau_objet = App\Models\ref_moughataas::find($commune->object_id);
                            $libe_commune = $set_niveau_objet->$libelle ;
                            @endphp
                            <tr>
                                <td><b>{{ trans("text.moughataa") }} : </b></td>
                                <td>{{ $libe_commune }}</td>
                            </tr>
                            @php
                            break;
                            case 3:
                            $set_niveau_objet = App\Models\ref_wilayas::find($commune->object_id);
                            $libe_commune = $set_niveau_objet->$libelle ;
                            @endphp
                            <tr>
                                <td><b>{{ trans("text.wilaya") }} : </b></td>
                                <td>{{ $libe_commune }}</td>
                            </tr>
                            @php

                            break;
                            }
                            @endphp
                             @if($commune->types_objets_geo_id == 1)
                                <tr>
                                    <td><b>code : </b></td>
                                    <td>{{ $commune->code }}</td>
                                </tr>
                                <tr>
                                    <td><b>Ancien code : </b></td>
                                    <td>{{ $commune->ancien_code }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans("text.nbr_village") }}  : </b></td>
                                    <td>{{ $commune->nbr_villages }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><b>Année de creation  : </b></td>
                                <td>{{ $commune->annee_creation }}</td>
                            </tr>
                            <tr>
                                <td><b>{{ trans("text.surface") }} : </b></td>
                                <td>{{ $commune->superficie }}</td>
                            </tr>
                        @elseif($niveau==2)
                            <tr>
                                <td></i> <b>{{ trans("text.moughataa") }}: </b></td>
                                <td> {{ $commune->$libelle }}</td>
                            </tr>
                            <tr>
                                <td><b>{{ trans("text.wilaya") }}: </b></td>
                                <td>{{ $commune->get_wilaya->$libelle }}</td>
                            </tr>
                            <tr>
                                <td></i> <b> {{ trans("text.population") }}: </b></td>
                                <td><?php echo strrev(wordwrap(strrev($commune->nbr_habitans), 3, ' ', true));  ?></td>
                            </tr>

                        @elseif($niveau==3)
                            <tr>
                                <td></i> <b>{{ trans("text.wilaya") }}: </b></td>
                                <td> {{ $commune->$libelle }}</td>
                            </tr>
                            <tr>
                                <td><b>{{ trans("text.nbr_moughataa") }}: </b></td>
                                <td>{{ count($commune->get_moughataas) }}</td>
                            </tr>
                            <tr>
                                <td></i> <b> {{ trans("text.population") }}: </b></td>
                                <td><?php echo strrev(wordwrap(strrev($commune->nbr_habitans), 3, ' ', true));  ?></td>
                            </tr>
                        @endif
                    </table>

                </td>

                <td style="width: 50%;">
                    <?php if($path != null)
                    { ?>
                    <img style="width:380px;margin-left:-15px;" src="{{ url($path)  }}">
                    <?php } ?>
                </td>
            </tr>
        </table>

    </div>

    <div class="row">

        <div class="col-md-12">

            <div id="resultat_fiche">
                {!!$data!!}
            </div>


        </div>


    </div>


@endsection
