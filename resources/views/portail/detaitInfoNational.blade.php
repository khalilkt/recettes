<?php
$text_right=trans("text.text_right");
$position=trans("text.position_right");
$direction =trans("text.direction");
$lib = trans("text.libelle_base");
?>
<style>
    .datatable{
        width: 100%!important;
    }
</style>
<script>

    $(document).ready(function(){
        $("#verif_ens").hide();
        var date_ref ="<?php echo $date_ref?>";

        var module ="<?php echo $module ?>";

        $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
        //charger le fiche communale de la commune
        $.ajax({
            type: 'get',
            url: racine+'get_fiche_communale_ByDate_ref/k,'+date_ref+','+module+','+4,
            cache: false,
            success: function(data)
            {
                $('#result_fiche').empty();
                //loading_hide();
                $('#result_fiche').html(data);

            },
            error: function () {
                //  loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });

    })

    function OnButton1()
    {
        document.formst.action = "export_pdf";
        document.formst.target = "_blank";    // Open in a new window
        document.formst.submit();             // Submit the page

        return true;
    }

    function OnButton2()
    {
        document.formst_wil.action = "export_excel_wil"
        document.formst_wil.target = "_blank";    // Open in a new window

        document.formst_wil.submit();             // Submit the page

        return true;
    }

    $("#date_ref").on('change',function(){
        $("#result").empty();
        var ref =$(this).val()+'-12-31';
        $("#date_ref_wil").val(ref);

        var id_com ="k";
        var module ="<?php echo $module ?>";

        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);

        $('.souscond').on('hidden.bs.collapse', toggleIcon);
        $('.souscond').on('shown.bs.collapse', toggleIcon);

        $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
        //charger le fiche communale de la commune
        $.ajax({
            type: 'get',
            url: racine+'get_fiche_communale_ByDate_ref/'+id_com+','+ref+','+module+','+4,
            cache: false,
            success: function(data)
            {
                $('#result_fiche').empty();
                //loading_hide();
                $('#result_fiche').html(data);

            },
            error: function () {
                //  loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });

    })
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    function toggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('fa-plus fa-minus');
    }
    //charger la carte
    $("a#voir_cart").on('click',function(){

        var path = $(this).attr('rel');

        $("#resutClick4").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

        $.ajax({
            type: 'GET',
            url: racine+'affiche_carte/'+path+','+4 ,
            cache: false,
            success: function (data) {
                $('#basicModal4').modal('show');
                //$('#example').html(data.msg + 'popilation' + data.pp);
                //loading_hide();
                $("#resutClick4").html(data);

            },
            error: function () {
                //alert('La requête n\'a pas abouti');
                console.log('La requête n\'a pas abouti');
            }
        });

    })
    function format(x) {
        return isNaN(x)?"":x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>

<div class="modal-header">

    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" style="{{ $position }}" id="myModalLabel"><i class="fa fa-list-alt"></i> {{ trans("text.fiche_n") }} : <a > National </a></h4>
</div>

<div class="modal-body">


    <div class="well well-lg">
        <div class="row" style="{{ $text_right }}">
            <div class="col-md-6">
                <div><i class="fa fa-globe"></i> <b>{{ trans("text.nbr_wilaya") }}: </b> {{ $nbr_wilaya }}</div>
                <div ><b><i class="fa fa-users"></i> {{ trans("text.population") }}: </b>  <span class="direction"> <?php echo  strrev(wordwrap(strrev($pop), 3, ' ', true));  ?></span></div>
            </div>
            <div class="col-md-6">
                <div ><i class="fa fa-globe"></i> <b>{{ trans("text.nbr_moughataa") }}:</b> {{ $nbr_m }}  </div>
                <div><i class="fa fa-globe"></i> <b>{{ trans("text.nbr_commune") }} : </b> {{ $nbr_com }}</div>


            </div>

        </div>
    </div>

    <div class="row" style="{{ $text_right }}">
        <div class="form-group col-md-6" style="color:red">
            <label for="date_ref"> {{ trans("text.annee_ref") }}</label>
        <!--<input type="date" class="form-control" value="{{ $date_ref }}" name="date_ref" id="date_ref" placeholder="">-->
            <select class="form-control" name="date_ref" id='date_ref' >
                <?php
                $exp=explode('-',$date_ref);
                $annee=$exp[0];
                $date = date('Y');
                for($i=2013; $i<=$date; $i++ )
                {
                    if($i==$annee)
                        echo "<option selected='selected'>".$i."</option>";
                    else
                        echo "<option>".$i."</option>";
                }
                ?>
            </select>
        </div>

    </div>
    {{--}} @if($module==3)--}}
    <div  id="loading"></div>
    <div  id="result_fiche"></div>
    {{-- @endif --}}

    {{--}}@if($module==2)--}}


</div>

</div>
