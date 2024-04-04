<?php
$text_right=trans("text.text_right");
$position=trans("text.position_right");
$direction =trans("text.direction");
?>
<div class="modal-header" style="{{ $direction }}">

    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" style="{{ $position }}" id="myModalLabel"><i class="fa fa-list-alt"></i> {{ $title }} <a href="#">  </a></h4>
</div>

<div class="modal-body">
<?php if(isset($heudeur)){
        $height=$heudeur;
        }else {
        $height="auto";
        }

    $date_imp = date('d-m-Y');

    ?>

    <div class="panel panel-default nonrtl">
        <!-- Default panel contents -->
        <div class="panel-heading">{{ trans("text.critere_recherche") }}</div>
        <div class="panel-body">
            <div class="row" style="{{ $text_right }}">
                <div class="col-md-6">
                    <div>{{ trans("text.date_ref") }} : <b>{{ $date_ref }}</b> </div>
                    <div> {{ trans("text.niveau") }} : <b>{{ $niveau }}</b>  </div>
                </div>
                <div class="col-md-6">
                    <div >{{ trans("text.categorie") }} : <b>{{ $groupe }} </b> </div>
                    <?php if(isset($nbr)){ ?>
                    <div>Nombre des {{ $niveau  }} :  <b> {{ $nbr }} </b> </div>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>

    <div id="type2"  style="width: 100%;  min-height:400px; height: <?php echo $height; ?>"></div>
    <?php
    if($chiff == 'true')
    {
    ?>
    <script>


        Highcharts.chart('type2', {
            colors: ['#1F497D','#910000','#8bbc21','#1aadce','#ff80ff','#4c4cef','#af4c21'],
            chart: {
                type: '<?php echo $type_pr ?>',
                events: {
                    load: function () {
                        var image = new Image();
                        var adresse_image = "{{ url('img/etarmr.png')  }}";
                        image.src = adresse_image;

                        if (image.width !== 0) {
                            var height_image = 60;
                            var width_image = 60;
                            var textX =  ((this.plotWidth) * 0.5);
                            var textY = this.plotTop + (this.plotHeight * 0.5);
                            var position_x = textX + width_image / 2;
                            var position_y = 0;

                            myElement = this.renderer.image(adresse_image, position_x, position_y, width_image, height_image)
                                    .css({
                                        position: 'relative',
                                        opacity: 1,
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
                        var height_image = 60;
                        var width_image = 60;
                        var textX = this.plotLeft + (this.plotWidth * 0.5);
                        var textY = this.plotTop + (this.plotHeight * 0.5);
                        var position_x = textX - width_image / 2;
                        //alert(position_x+'div'+$("#type2").height())
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
                text: '<?php echo addslashes($title); ?>',
                useHTML: Highcharts.hasBidiBug,
                y:100
            },
            subtitle: {
                text: 'Source: <a href="#">{{ isset($source) ? $source :''}}</a>',
                y:120
            },
            xAxis: {
               <?php echo $cat.',' ?>
                  crosshair: true
            },
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: '{{ $abrj }}'
                }
            },
            plotOptions: {
                '<?php echo $type_pr ?>': {
                    dataLabels: {
                        enabled: true,
                        formatter: function(){
                            return (this.y)+'<?php echo $pr?>';
                        }
                    }
                }
            },
            legend: {
                enabled: '<?php echo $leg ?>',
                useHTML: Highcharts.hasBidiBug
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0"><b>{series.name}</b>: </td>' +
                '<td style="padding:0"><b>{point.y} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            series: [<?php echo $pop ?>],
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
            },
        });
    </script>

<?php }
    else {
        ?>
    <script>


        Highcharts.chart('type2', {
            colors: ['#1F497D','#910000','#8bbc21','#1aadce','#ff80ff','#4c4cef','#af4c21'],
            chart: {
                type: '<?php echo $type_pr ?>'
            },
            title: {
                text: '<?php echo addslashes($title); ?>'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                <?php echo $cat.',' ?>
                crosshair: true

             },
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: '{{ $abrj }}'
                }
            },
            plotOptions: {
                '<?php echo $type_pr ?>': {

                    dataLabels: {
                        enabled: false,
                        formatter: function(){
                            return (this.y)+'<?php echo $pr?>';
                        }
                    }
                }
            },
            legend: {
                enabled: '<?php echo $leg ?>'
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0"><b>{series.name}</b>: </td>' +
                '<td style="padding:0"><b>{point.y} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            series: [<?php echo $pop ?>],
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
            },
        });
    </script>
    <?php
    }
    ?>
</div>

</div>

