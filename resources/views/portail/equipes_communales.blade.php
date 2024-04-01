<?php
$lang = App::getLocale();
?>
<table  id="res_compdec_ddd" class="table table-striped table-bordered table-responsive nowrap"  style="width:100%;{{ trans("text.text_right") }}">
    <thead>
    <tr >
        <th>{{ trans("text.ordre") }}</th>
        <th>{{ trans("text.name") }}</th>
        <th style="width: 25%;" >{{ trans("text.fonction") }}</th>

    </tr>
    </thead>
</table>
</div>

<script type="text/javascript">
    var id_groupe="<?php echo $id_commune ?>";

    //  if (!$.fn.dataTable.isDataTable('#res'+id_groupe)) {

    var oTable = $('#res_compdec_ddd').DataTable({
        oLanguage: {
            sUrl: racine+"vendor/datatables/js/datatable-{{ $lang }}.json",
        },
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "responsive": true,
        "orderCellsTop": true,

        "ajax": racine+"get_equipes_communale/"+id_groupe,
        "columns": [
            {data: 'ordre', name: 'ordre'},
            {data: 'name', name: 'name'},
            {data: 'fonction', name: 'fonction'}

        ],
        "drawCallback": function() {
            // init tooltips
            $('[data-toggle="tooltip"]').tooltip();
        }
    });


</script>
