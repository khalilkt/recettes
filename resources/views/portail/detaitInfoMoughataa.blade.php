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

        var id_com ="<?php echo $moughataa->id ?>";
        var module ="<?php echo $module ?>";
        $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
        //charger le fiche communale de la commune
        $.ajax({
            type: 'get',
            url: racine+'get_fiche_communale_ByDate_ref/'+id_com+','+date_ref+','+module+','+2,
            cache: false,
            success: function(data)
            {
                $('#result_fiche_moug').empty();
                //loading_hide();
                $('#result_fiche_moug').html(data);


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
        document.formst.action = "export_pdf"
        document.formst.target = "_blank";    // Open in a new window

        document.formst.submit();             // Submit the page

        return true;
    }


    function OnButton2()
    {
        document.formst_m.action = "export_excel_moug"
        document.formst_m.target = "_blank";    // Open in a new window

        document.formst_m.submit();             // Submit the page

        return true;
    }

   function fiches_commune(id,ref,module,resultat='resutClick2',modal='basicModal2')
   {

   }
    $('td .fiche_com').on('click',function(){
        var id_fiche = $(this).attr('id_fiche');
        var id_com = $(this).attr('id_com');
        var date_ref = "<?php echo $date_ref ?>";
        var module = "<?php echo $module ?>";

        $("#resutClick2").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

        $.ajax({
            type: 'GET',
            url: racine+'detaitInfoCommune/' + id_com+','+date_ref+','+module,
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
    $("#date_ref_mg").on('change',function() {
        // event.preventDefault();
        var id = "<?php echo  $moughataa->id ?>"
        var module = "<?php echo  $module ?>"
        var ref =$(this).val();
        var date_ref =$(this).val()+'-12-31';
        $("#date_ref_m").val(ref);
        $('#communeMoughataas').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');

        $.ajax({
            type: 'GET',
            url: racine+'get_infoCommunesByMoughataas/'+id+','+ref+'-12-31'+','+module,
            cache: false,
            success: function (data) {
                //$('#example').html(data.msg + 'popilation' + data.pp);
                //loading_hide();
                $("#communeMoughataas").empty();
                $("#communeMoughataas").html(data);


            },
            error: function () {
                //alert('La requête n\'a pas abouti');
                console.log('La requête n\'a pas abouti');
            }
        });

        $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
        //charger le fiche communale de la commune
        $.ajax({
            type: 'get',
            url: racine+'get_fiche_communale_ByDate_ref/'+id+','+date_ref+','+module+','+2,
            cache: false,
            success: function(data)
            {
                $('#result_fiche_moug').empty();
                //loading_hide();
                $('#result_fiche_moug').html(data);


            },
            error: function () {
                //  loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });

        return false;

    });
    //charger la carte
    $("a#voir_cart").on('click',function(){

        var path = $(this).attr('rel');

        $("#resutClick4").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

        $.ajax({
            type: 'GET',
            url: racine+'affiche_carte/'+path+','+2 ,
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
</script>
<div class="modal-header">
    <h4 class="modal-title" style="{{ $position }}" id="myModalLabel"><i class="fa fa-list-alt"></i> {{ trans("text.fiche_m") }}  : <a > {{ $moughataa->$lib }} </a></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

<div class="modal-body">


    <div class="well well-lg">
        <div class="row" style="{{ $text_right }}">
            <div class="col-md-6">

                <div><i class="fa fa-globe"></i> <b>{{ trans("text.moughataa") }}: </b> {{ $moughataa->$lib }}</div>
                <div ><b><i class="fa fa-users"></i> {{ trans("text.population") }} : </b> <span class="direction"> <?php echo  strrev(wordwrap(strrev($moughataa->nbr_habitants), 3, ' ', true));  ?></span></div>
            </div>
            <div class="col-md-6">
                <div ><i class="fa fa-globe"></i> <b>{{ trans("text.wilaya") }}:</b> {{ $moughataa->get_wilaya->$lib }}  </div>
            </div>
            <div class="col-md-6">
                <div ><i class="fa fa-globe"></i> <b>{{ trans("text.nbr_commune") }}:  </b> {{ $nbr_com }} </div>
                <div>
                    <i class="fa fa-eye"></i> <b><a id="voir_cart" rel="<?php echo $moughataa->id;?>" href="#">{{ trans("text.v_carte") }}</a>  </b>

                </div>
            </div>


        </div>
    </div>

    <div class="row" style="{{ $text_right }}">
        <div class="form-group col-md-6" style="color:red">
            <label for="date_ref"> {{ trans("text.annee_ref") }} </label>
           <!-- <input type="date" class="form-control" value="{{ $date_ref }}" id="date_ref_mg" name="date_ref_mg" placeholder="">-->
            <select class="form-control" name="date_ref_mg" id='date_ref_mg' >
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
  {{--}}@if($module==3)--}}
    <div  id="loading"></div>
    <div  id="result_fiche_moug"></div>
    {{--@endif--}}


</div>
<div class="modal-footer">
    <form role="form"  id="formst" name="formst" class=""  method="post" >
        {{ csrf_field() }}
        <input type="hidden" name="exp_fiche" id="exp_fiche" />
        <input type="hidden" name="exp_eval" id="exp_eval"/>
        <input type="hidden" name="id_fiche" value="{{ $moughataa->id }}" />
        <input type="hidden" name="fiche_com" id="fiche_com" />
        <input type="hidden" name="date_ref" value="{{ $date_ref }}" />
        <input type="hidden" name="module" value="{{ $module }}" />
        <input type="hidden" name="id_groupe[]"  id="id_groupe[]" />
        <input type="hidden" name="niveau_exp"  id="niveau_exp" value="2" />


        <button type="button" class="btn btn-dark pull-left text-white" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans("text.fermer") }}</button>
        {{--<button type="button"  disabled="disabled" class="btn btn-success pull-right exp_fiche_excel text-white"><i class="fa fa-file-excel-o exp_excel"></i> {{ trans("text.export") }}</button>
        --}}
        <button type="button" onclick="OnButton1();" disabled="disabled" class="btn btn-primary pull-right imp_fiche text-white" > <i class="fa fa-print"></i> {{ trans("text.print") }} </button>

    </form>

</div>

