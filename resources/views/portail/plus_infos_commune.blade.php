<?php
$lang = App::getLocale();
$libelle = trans("text.libelle_base");
$wilaya = trans("text.wilaya");
$moug = trans("text.moughataa");
$commun = trans("text.commune");

?>
<table id="res_plus_commune" class="table table-striped table-bordered table-responsive nowrap"
       style="width:100%;{{ trans("text.text_right") }}">
    <thead>
    <tr>

        @if($niveau == 1)
            <th>{{ $commun }}</th>
            <th>{{ $moug }}</th>
            <th>{{ $wilaya }}</th>

        @elseif($niveau==2)
            <th>{{ $moug }}</th>
            <th>{{ $wilaya }}</th>

        @elseif($niveau==3)
            <th>{{ $wilaya }}</th>
        @endif
        @foreach($info_cartes as $info)
            <th>{{ $info->$libelle}}</th>
        @endforeach

    </tr>
    </thead>
    <tbody>
    @foreach($communes as $c)
        @if(isset($data[$c->id]))
            <tr>
                @if($niveau == 1)

                    <td style="color:{{ $data[$c->id] }}">{{ $c->$libelle }} <span
                                style="color:{{ $data[$c->id] }}; width: 40px;"></span></td>
                    <td>{{ $c->get_moughataa->$libelle }}</td>
                    <td>{{ $c->get_moughataa->get_wilaya->$libelle }}</td>

                @elseif($niveau==2)

                    <td style="color:{{ $data[$c->id] }}">{{ $c->$libelle }} <span
                                style="color:{{ $data[$c->id] }}; width: 40px;"></span></td>
                    <td>{{ $c->get_wilaya->$libelle }}</td>

                @elseif($niveau==3)

                    <td style="color:{{ $data[$c->id] }}">{{ $c->$libelle }} <span
                                style="color:{{ $data[$c->id] }}; width: 40px;"></span></td>
                @endif

                @php
                $cont = new \App\Http\Controllers\mapController();
                $val_communes =$cont->info_excel($module,$niveau,$c->id,$ref);
                @endphp
                @foreach($val_communes as $val)
                    <td>{{ $val }}</td>
                @endforeach

            </tr>
        @endif
    @endforeach
    </tbody>
</table>

