<?php
$lang = App::getLocale();
$libelle = trans("text.libelle_base");
$wilaya = trans("text.wilaya");
$moug = trans("text.moughataa");
$res = " {data: 'wilaya', name: 'wilaya'}, {data: 'moughataa', name: 'moughataas'}";
/*oreach ($info_cartes as $info)
    {
        $res =$res.", {data: '".$info->libelle."', name: '".$info->libelle."'}";
    }*/
?>*
<table  id="res_plus_commune" class="table table-striped table-bordered table-responsive nowrap"  style="width:100%;{{ trans("text.text_right") }}">
    <thead>
    <tr>
        <th>{{ $wilaya }}</th>
        <th>{{ $moug }}</th>

        @foreach($info_cartes as $info)
            <th>{{ $info->$libelle}}</th>
        @endforeach

    </tr>
    </thead>
    <tbody>
    @foreach($communes as $c)
        @if($data[$c->id] != 0)
            <tr>
                <td>{{ $c->get_moughataa->get_wilaya->$libelle }}</td>
                <td>{{ $c->get_moughataa->$libelle }}</td>
                @php
                    $cont = new \App\Http\Controllers\mapController();
                    $val_communes =$cont->info_excel($module,$niveau,$c->id,$ref);
                @endphp
                @foreach($val_communes as $val)
                    <td>{{ $val }}</td>
                @endforeach
                @foreach($info_cartes as $info)
                    <td>{{ $info->$libelle}}</td>
                @endforeach
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
</div>

{{--}}
<script type="text/javascript">


    var data="{<?php echo $data ?>}"
    var niveau="<?php echo $niveau ?>"
    var module="<?php echo $module ?>"
    //  if (!$.fn.dataTable.isDataTable('#res'+id_groupe)) {
    /*switch (niveau)
    {
        case '1':
            url =racine+"getReponsesQuestion/"+id_groupe+'+'+id_com+'+'+id_fiche+'+'+niveau;
            break;
        case '2':
            url=racine+"getReponsesQuestion/"+id_groupe+'+'+id_com+'+'+id_fiche+'+'+niveau;
            break;
        case '3':
                url = racine+"getReponsesQuestion/"+id_groupe+'+'+id_com+'+'+id_fiche+'+'+niveau;
            break;
    }*/
    var oTable = $('#res_plus_commune').DataTable({
        oLanguage: {
            sUrl: racine+"vendor/datatables/js/datatable-{{ $lang }}.json",
        },
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "responsive": true,
        "orderCellsTop": true,

        "ajax": racine+"resultat_plus_infos_commune",
        data: { data : data,
            module:module,
            ref:ref,
            niveau:niveau
        },
        "columns": [
           <?php echo $res; ?>

        ],
        "drawCallback": function() {
            // init tooltips
            $('[data-toggle="tooltip"]').tooltip();
        }
    });




</script>--}}
