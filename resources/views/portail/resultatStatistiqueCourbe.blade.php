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
            <hr>
            <div   id="container1" ></div>
        </div>
    </div>

@php
    $date_imp = date('d-m-Y');
    @endphp

    <script>
    Highcharts.chart('container', {

        chart: {
            type: 'line',
            /*events: {
                load: function(event) {this.renderer.image('{{ url("img/etarmr.png")  }}',250,0,100,100).add();
                }
            }*/
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
            useHTML: true,
            text: "{{ $libelle_commune }}",
           /* style: {
                color: '#FF00FF',
                fontWeight: 'bold',
                marginTop:'20px;'

            },*/
            y:100
        },
        subtitle: {
            text: 'Source: {{ $source }}',
            y:120
        },
        xAxis: {
            categories: [ <?php echo $cat ?>]
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [ <?php echo $data; ?> ],
        credits: {
			useHTML:true,
            text: 'Système d\'information du MEDD (www.si-medd.mr)',
            href: 'http://www.si-medd.mr'
        }

        /*exporting: {
            enabled: true,
            allowHTML: true,

            chartOptions:{
                chart:{
                    events:{
                        load:function(){
                            this.renderer.image('http://localhost/simedd/public/img/etarmr.png', 100, 100, 30, 30)
                                    .add();

                        }
                    }
                }
            }
           /* chartOptions:{
                chart:{
                    events:{
                        load:function(){
                            this.renderer.image('{{ url("img/etarmr.png")  }}', 160, 10, 30, 30)
                                    .add();

                        }
                    }
                }
            }*/
            /*chartOptions: {
                chart: {
                    events: {
                        load: function () {
                            var chart = this;
                            chart.renderer.image(
                                    '{{ url("img/etarmr.png")  }}',
                                    100,
                                    100,
                                    60,
                                    60
                                    )
                                    .add()
                                    .toFront();
                        }
                    }
                }
            }*/

        /*exporting: {
            scale: 1,
            sourceWidth: 1600,
            sourceHeight: 900,

            chartOptions: {
                rangeSelector: {
                    enabled: false
                },
            }
        }*/
    });


    Highcharts.chart('container1', {

        chart: {
            type: 'column',
            /*events: {
             load: function(event) {this.renderer.image('{{ url("img/etarmr.png")  }}',250,0,100,100).add();
             }
             }*/
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
            useHTML: true,
            text: "{{ $libelle_commune }}",
            /* style: {
             color: '#FF00FF',
             fontWeight: 'bold',
             marginTop:'20px;'

             },*/
            y:100
        },
        subtitle: {
            text: 'Source: {{ $source }}',
            y:120
        },
        xAxis: {
            categories: [ <?php echo $cat ?>]
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [ <?php echo $data; ?> ],
        credits: {
            useHTML:true,
            text: 'Système d\'information du MEDD (www.si-medd.mr)',
            href: 'http://www.si-medd.mr'
        }

    });
</script>
</div>


