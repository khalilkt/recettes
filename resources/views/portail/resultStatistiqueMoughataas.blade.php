
<div class="modal-header">

    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-list-alt"></i>' {{ $title }} <a href="#">  </a></h4>
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


    <!-- type1 tous les wilayas sont selectionne -->
    @if(isset($type1))
        <div id="type1" style="width: 100%"></div>

        <script>


            Highcharts.chart('type1', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: 'Source: <a href="#">DGCT</a>'
                },
                legend: {
                    enabled: true
                },
                tooltip: {
                    pointFormat: '{{ $tooltip }}:{point.nbr}<br>{series.name}:<b>{point.pr:.2f}%</b>'
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
                    name: 'Pourcentage',
                    colorByPoint: true,
                    data: [<?php echo $type1 ?> ]
                }],
                credits: {
                    enabled: false
                },
            });
        </script>


        <div id="type2" ></div>


        <script>

            Highcharts.chart('type2', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: 'Source: <a href="#">DGCT</a>'
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -45,
                        style: {
                            fontSize: '11px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: '{{ $abrj }}'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{{$tooltip}} :{point.nbr} <br> Pourcentage :<b>{point.pr:.2f}% </b>'
                },
                series: [{
                    name: 'Population',
                    data: [
                        <?php echo $type1 ?>
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.pr:.2f}%', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '9px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }],
                credits: {
                    enabled: false
                }
            });
        </script>
    @endif
    @if(isset($type2))
        <div id="type2" style="width: 100%"></div>

        <script>

            Highcharts.chart('type2', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '{{ $title }}'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -45,
                        style: {
                            fontSize: '11px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: '{{ $abrj }}'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{{ $tooltip }} :{point.nbr} <br> Pourcentage :<b>{point.pr:.2f}% </b>'
                },
                series: [{
                    name: 'Population',
                    data: [
                        <?php echo $type2 ?>
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.nbr}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '9px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }],
                credits: {
                    enabled: false
                }
            });
        </script>
    @endif

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Fermer</button>
    <button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o"></i> Exporter</button>
    <button type="button" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Imprimer</button>
</div>
</div>



