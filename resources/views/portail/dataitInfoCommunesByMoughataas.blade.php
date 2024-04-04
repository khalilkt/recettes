<?php
$text_right=trans("text.text_right");
//$position=trans("text.position_right");
//$direction =trans("text.direction");
//$lib = trans("text.libelle_base");
?>
<script>

    $('td .fiche_com').on('click',function(){
        var id_fiche = $(this).attr('id_fiche');
        var id_com = $(this).attr('id_com');
        var date_ref = "<?php echo $date_ref ?>";
        var module = "<?php echo $module ?>";
        $("#resutClick2").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

        $.ajax({
            type: 'GET',
            url: racine+'detaitInfoCommune1/' + id_com +','+id_fiche+','+date_ref+','+module,
            cache: false,
            success: function (data) {
                $('#basicModal2').modal('show');
                //$('#example').html(data.msg + 'popilation' + data.pp);
                //loading_hide();
                $("#resutClick2").html(data);

            },
            error: function () {
                //alert('La requête n\'a pas abouti');
                console.log('La requête n\'a pas abouti');
            }
        });

    })
</script>
<div class="panel panel-default" >
    <!-- Default panel contents -->
    <div class="panel-heading" style="text-align: left; {{ $text_right }}"> {{ trans("text.communes_de") }}: <a >{{ $moughataa }}</a></div>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>{{ trans("text.commune") }}</th>
                <th style="text-align: right">{{ trans("text.population") }}</th>
                <th style="text-align: right">{{ trans("text.surface") }}.</th>
                <th style="text-align: right">{{ trans("text.nbr_village") }}</th>
            </tr>
        </thead>
        <tbody>

            @foreach($communes as $c)
                <tr>
                    <td>{{  $c["libelle"] }} </td>
                    <td style="text-align: right" ><span class="direction"> {{  $c["pop"] }} </span></td>
                    <td style="text-align: right" > <span class="direction"><?php echo  strrev(wordwrap(strrev($c["surface"]), 3, ' ', true));  ?> km&sup2; </span></td>
                    <td style="text-align: right" >{{  $c["nbr_loc"] }}</td>
                    @if($c["fiche"]  != false)
                        <td><a  class="btn fiche_com"  id_fiche ="{{ $c["fiche"] }}"  id_com="{{ $c["id"] }}" href="#" >{{ trans("text.fiche") }} </a></td>
                    @else
                        <td></td>
                   @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

