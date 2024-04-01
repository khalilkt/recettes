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
                type: '<?php echo $type_pr ?>'
            },
            title: {
                text: '<?php echo addslashes($title); ?>',
                useHTML: Highcharts.hasBidiBug
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
        });
    </script>
    <?php
    }
    ?>
</div>

</div>

