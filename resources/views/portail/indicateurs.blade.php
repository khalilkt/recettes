<?php
$text_right=trans("text.text_right");
$position=trans("text.position_right");
$direction =trans("text.direction");
$libelle = trans("text.libelle_base");
$lang = App::getLocale();
?>
<div class="modal-header">

    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" style="{{ $position }}" id="myModalLabel"><i class="fa fa-filter"></i>  <a >Indicateurs </a>
         </h4>

</div>

<div class="modal-body">

<div class="panel panel-default">
    <div class="panel-heading">
        <b><i class="fa fa-list"></i> Indicateurs : {{ $groupe->libelle }} </b>
    </div>
    <div class="panel-body">

        <div class="row">

            <table  id="res_ind" class="table table-striped table-bordered table-responsive nowrap"  style="width:100%;{{ trans("text.text_right") }}">
                <thead>
                <tr >
                    <th>Indicateurs</th>
                    <th>Ajouter au carte</th>

                </tr>
                </thead>
            </table>

        </div>
    </div>
    </div>
    <div class="modal-footer">

        <script type="text/javascript">


             var id = '{{ $id }}';
             var niveau = '{{ $niveau }}';
             var module = '{{ $module }}';

            var oTable = $('#res_ind').DataTable({
                oLanguage: {
                    sUrl: racine+"vendor/datatables/js/datatable-{{ $lang }}.json",
                },
                "processing": true,
                "serverSide": true,
                "bDestroy": true,
                "responsive": true,
                "orderCellsTop": true,

                "ajax": racine+"getIndicateursGroupe_dt/"+id+"/"+niveau+"/"+module,
                "columns": [
                    {data: 'question', name: 'code'},
                    {data: 'action', name: 'actions'}

                ],
                "drawCallback": function() {
                    // init tooltips
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

        </script>


    </div>
</div>