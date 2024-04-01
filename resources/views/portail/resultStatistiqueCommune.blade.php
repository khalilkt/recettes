<?php
if(isset($type_chart))
    {
        if($type_chart==1)
        {
            $chart="column";
        }
        elseif($type_chart==2)
        {
            $chart="pie";
        }
    }
else
    $chart="column";

?>
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

<div id="container" style="width: 100%;"></div>

    <script>
        $(function () {
            Highcharts.setOptions({
                global: {
                    useUTC: false,

                },
                lang: {
                    decimalPoint: '.',
                    thousandsSep: ' '
                }
            });

            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: [
                        <?php echo $lib ?>
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '<?php echo $abrj ?>'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0"><b>{series.name}</b>: </td>' +
                    '<td style="padding:0"><b>{point.y} </b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true,
                            formatter: function(){
                                return Highcharts.numberFormat(this.y,0)+'%';
                            }
                        }
                    }
                },
                series: [
                    <?php echo $pop;?>
                 ],
                credits: {
                    enabled: false
                },

                navigation: {
                    buttonOptions: {
                        theme: {
                            // Good old text links
                            style: {
                                color: '#039',
                                textDecoration: 'underline'
                            }
                        }
                    }
                },

                /* exporting: {
                 buttons: {
                 contextButton: {
                 enabled: false
                 },
                 exportButton: {
                 text: 'Telecharger',
                 // Use only the download related menu items from the default context button
                 menuItems: Highcharts.getOptions().exporting.buttons.contextButton.menuItems.splice(2)
                 },
                 printButton: {
                 text: 'Imprimer',
                 onclick: function () {
                 this.print();
                 }
                 }
                 }
                 }*/

                /*exporting: {
                    buttons: {
                        contextButton: {
                            menuItems: [{
                                textKey: 'downloadPNG',
                                onclick: function () {
                                    this.exportChartLocal();
                                }
                            }, {
                                textKey: 'downloadJPEG',
                                onclick: function () {
                                    this.exportChartLocal({
                                        type: 'image/jpeg'
                                    });
                                }
                            }]
                        }
                    },
                    scale: 3,
                    fallbackToExportServer: false
                }*/

            });
        });

    </script>


</div>
<div class="modal-footer">

    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Fermer</button>
    <button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o"></i> Exporter</button>
    <button type="button" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Imprimer</button>
</div>
</div>

