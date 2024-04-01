
<div class="modal-header">

    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-bar-chart"></i> {{ $title }} <a href="#">  </a></h4>
</div>

<div class="modal-body">

<div class="panel panel-default" >
    <!-- Default panel contents -->
    <div class="panel-heading"></div>
        <div class="panel-body">
            <div   id="container" ></div>
        </div>
</div>

    @php
    $date_imp = date('d-m-Y');
    @endphp

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
                type: 'column',
                width:550,
                events: {
                    load: function () {
                        var image = new Image();
                        var adresse_image = "{{ url('img/etarmr.png')  }}";
                        image.src = adresse_image;

                        if (image.width !== 0) {
                            var height_image = 60;
                            var width_image = 60;
                            var textX = this.plotLeft + (this.plotWidth * 0.5);
                            var textY = this.plotTop + (this.plotHeight * 0.5);
                            var position_x = textX - width_image / 2;
                            var position_y = 0;
                            myElement = this.renderer.image(adresse_image, position_x, position_y, width_image, height_image)
                                    .css({
                                        position: 'relative',
                                        display:'block',
                                        opacity: 1
                                    })
                                    .attr({
                                        zIndex: 1
                                    })
                                    .add();
                        }
                        var label = this.renderer.label("Ministére de l\'Environnement et du Dévéloppement Durable (MEDD)")
                                .css({
                                    width: '450px',
                                    fontSize: '12px',
                                    fontWeight:'bold',
                                })
                                .attr({
                                    'stroke': 'silver',
                                    'stroke-width': 1,
                                    'r': 2,
                                    'padding': 5
                                })
                                .add();

                        label.align(Highcharts.extend(label.getBBox(), {
                            align: 'center',
                            x: 20, // offset
                            verticalAlign: 'top',
                            y: 50 // offset
                        }), null, 'spacingBox');

                        var label_date = this.renderer.label(" Date d\'impression : {{ $date_imp }}")
                                .css({
                                    align: 'left',
                                    verticalAlign: 'bottom',
                                    "cursor": "pointer", "color": "#999999", "fontSize": "10px",


                                })
                                .add();

                        label_date.align(Highcharts.extend(label.getBBox(), {
                            align: 'left',
                            x: 0, // offset
                            verticalAlign: 'bottom',
                            y: 25 // offset
                        }), null, 'spacingBox');

                    }
                    ,
                    redraw: function () {
                        var height_image = 100;
                        var width_image = 100;
                        var textX = this.plotLeft + (this.plotWidth * 0.5);
                        var textY = this.plotTop + (this.plotHeight * 0.5);
                        var position_x = textX - width_image / 2;
                        var position_y = 0;
                        myElement.attr({
                            x: position_x,
                            y: position_y,
                        });
                    },

                },
                marginTop: 160
            },
            title: {
                text: '<?php echo $libelle_commune ?>',
                y:100
            },
            subtitle: {
                text: 'Source : {{ $source }}',
                y:120
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
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [
                <?php echo $pop;?>
        ],

            credits: {
                text: 'Système d\'information du MEDD (www.si-medd.mr)',
                href: 'http://www.si-medd.mr'
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
            }

        });

    });

</script>

</div>