
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-list-alt"></i> {{ $title }} <a href="#">  </a></h4>
</div>

<div class="modal-body">
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Critéres de recherche</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div>Date de reférence : <b>{{ $date_ref }}</b> </div>
                    <div> Niveau : <b>{{ $niveau }}</b>  </div>
                </div>
                <div class="col-md-6">
                    <div >Groupe de données : <b>{{ $groupe }} </b> </div>
                    <?php if(isset($nbr)){ ?>
                    <div>Nombre des {{ $niveau  }} :  <b> {{ $nbr }} </b> </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div id="container" style="width: 100%"></div>

    <script>
        Highcharts.chart('container', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<?php echo $title?>'
            },
            subtitle: {
            text: 'Source: <a href="#">DGCT</a>'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: [<?php echo $type1 ?>]
            }],
            credits: {
            enabled: false
            }
        });

    </script>


</div>

</div>

