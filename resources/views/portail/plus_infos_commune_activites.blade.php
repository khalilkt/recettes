<?php
$lang = App::getLocale();
$libelle = trans("text.libelle_base");
$wilaya = trans("text.wilaya");
$moug = trans("text.moughataa");
$commun = trans("text.commune");

?>

<table  class=" plus-info" style="overflow: scroll!important;">
    <thead style="background: #eee">
    <tr>

            <th >Planification stratégique</th>
            <th>@lang('text_me.activite')</th>
            <th>@lang('text.code')</th>
            <th>@lang('text_me.date_debut')</th>
            <th>@lang('text_me.date_fin')</th>
            <th>@lang('text_mdp.structure')</th>
            <th>@lang('text_me.budget')</th>
            <th>@lang('text_me.total_engagements')</th>
            <th>@lang('text_me.total_decaissements')</th>
            <th>Export</th>

    </tr>
    </thead>
    <tbody>
    @foreach($data as $c)
        <tr>
       @php

          $id = $c->id;
          $color = $c->color;
          $set_activite = App\Models\BRrActivite::find($id);
          $nom = $set_activite->$libelle;
          $planification = $set_activite->pr_groupes_question->$libelle;
          $code = $set_activite->code;
          $date_deb = $set_activite->date_plan_deb;
          $date_fin = $set_activite->date_plan_fin;
          $structure = $set_activite->b_stricture->libelle;
          $budgets_total = $set_activite->budgets_total;

        $cont = new \App\Http\Controllers\Activite2Controller();
                $eng =$cont->totalEnguagement($id);
                $dec =$cont->totalDecaissement($id);
       @endphp

                   <th style="color:{{ $color }}"> {{ $planification }}</th>
                   <td  style="color:{{ $color }}">{{ $nom }} <span style="color:{{ $color }}; width: 40px;"></span></td>
                   <td style="color:{{ $color }}">{{ $code }} <span style="color:{{ $color }}; width: 40px;"></span></td>
                   <td style="color:{{ $color }}">{{ $date_deb }} <span style="color:{{ $color }}; width: 40px;"></span></td>
                   <td style="color:{{ $color }}">{{ $date_fin }} <span style="color:{{ $color }}; width: 40px;"></span></td>
                   <td style="color:{{ $color }}">{{ $structure }} <span style="color:{{ $color }}; width: 40px;"></span></td>
                   <td style="text-align: center; color:{{ $color }}"> {{  strrev(wordwrap(strrev($budgets_total), 3, ' ', true)) }}    <span style="color:{{ $color }}; width: 40px;"></span></td>
                   <td style="text-align: center; color:{{ $color }}"> {{  strrev(wordwrap(strrev($eng), 3, ' ', true)) }}    <span style="color:{{ $color }}; width: 40px;"></span></td>
                   <td style="text-align: center; color:{{ $color }}"> {{  strrev(wordwrap(strrev($dec), 3, ' ', true)) }}    <span style="color:{{ $color }}; width: 40px;"></span></td>
                   <td> <button type="button" class="btn btn-sm btn-warning" onclick="imprimerActivite('{{ $id }}')" data-toggle="tooltip" data-placement="top" title="text.imprimer"><i class="fa fa-fw fa-file-pdf"></i></button> </td>
             </tr>

    @endforeach
    </tbody>
</table>

