<?php
$lang = App::getLocale();
?>
<table  id="res{{ $id_groupe }}" class="table table-striped table-bordered table-responsive nowrap"  style="width:100%;{{ trans("text.text_right") }}">
    <thead>
    <tr >
        <th>{{ trans("text.code") }}</th>
        <th>{{ trans("text.question") }}</th>
        <th>{{ trans("text.source_q") }}</th>
        <th style="width: 25%;" >{{ trans("text.rep") }}</th>

    </tr>
    </thead>
</table>
</div>

<script type="text/javascript">
    var id_groupe="<?php echo $id_groupe ?>"
    var id_com ="<?php echo $id_com ?>"
    var id_fiche="<?php echo $id_fiche ?>"
    var niveau="<?php echo $niveau ?>"
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
    var oTable = $('#res<?php echo $id_groupe ?>').DataTable({
        oLanguage: {
            sUrl: racine+"vendor/datatables/js/datatable-{{ $lang }}.json",
        },
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "responsive": true,
        "orderCellsTop": true,

        "ajax": racine+"getReponsesQuestion/"+id_groupe+'+'+id_com+'+'+id_fiche+'+'+niveau,
        "columns": [
            {data: 'code', name: 'code'},
            {data: 'question', name: 'question'},
            {data: 'structure', name: 'structure'},
            {data: 'reponse', name: 'reponse'}

        ],
        "drawCallback": function() {
            // init tooltips
            $('[data-toggle="tooltip"]').tooltip();
        }
    });




</script>
