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
    var niveau="<?php echo $niveau ?>"
    var module="<?php echo $module ?>"
    var id="<?php echo $id ?>"
    var date_ref="<?php echo $date_ref ?>"

    var oTable = $('#res<?php echo $id_groupe ?>').DataTable({
        oLanguage: {
            sUrl: racine+"vendor/datatables/js/datatable-{{ $lang }}.json",
        },
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "responsive": true,
        "orderCellsTop": true,

        "ajax": racine+"getReponsesQuestionAgregation/"+id+'+'+id_groupe+'+'+date_ref+'+'+id_com+'+'+module+'+'+niveau,
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
