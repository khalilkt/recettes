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

        var id_com ="<?php echo $wilaya->id ?>";
        var module ="<?php echo $module ?>";

        $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
        //charger le fiche communale de la commune
        $.ajax({
            type: 'get',
            url: racine+'get_fiche_communale_ByDate_ref/'+id_com+','+date_ref+','+module+','+3,
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
        document.formst.action = "export_pdf"
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

    //recuperation du questionaire du groupe
        $("a.moug").on('click',function() {
           // event.preventDefault();
            var id =  $(this).attr('rel');
            var ref =$("#date_ref").val()+'-12-31';
            var module ='<?php echo $module ?>';

              //alert(id);
            $.ajax({
                type: 'GET',
                url: racine+'get_infoCommunesByMoughataas/'+id+','+ref+','+module,
                cache: false,
                success: function (data) {
                    //$('#example').html(data.msg + 'popilation' + data.pp);
                    //loading_hide();
                    $("#result").html(data);

                    var position = $("#result").offset().top;
                   // $("#basicModal").scrollTop(position);
                    $("#basicModal").animate({ scrollTop: position }, position);
                    //$("#basicModal").scrollspy({ target: 'position' })
                },
                error: function () {
                    //alert('La requête n\'a pas abouti');
                    console.log('La requête n\'a pas abouti');
                }
            });
            return false;

        });

    $("#date_ref").on('change',function(){
        $("#result").empty();
        var ref =$(this).val()+'-12-31';
        $("#date_ref_wil").val(ref);

        var id_com ="<?php echo $wilaya->id ?>";
        var module ="<?php echo $module ?>";


        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);

        $('.souscond').on('hidden.bs.collapse', toggleIcon);
        $('.souscond').on('shown.bs.collapse', toggleIcon);

        $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
        //charger le fiche communale de la commune
        $.ajax({
            type: 'get',
            url: racine+'get_fiche_communale_ByDate_ref/'+id_com+','+ref+','+module+','+3,
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
            url: racine+'affiche_carte/'+path+','+3 ,
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

    <h4 class="modal-title" style="{{ $position }}" id="myModalLabel"><i class="fa fa-list-alt"></i> {{ trans("text.fiche_w") }} : <a > {{ $wilaya->$lib }} </a></h4>
    <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

<div class="modal-body">


    <div class="well well-lg">
        <div class="row" style="{{ $text_right }}">
            <div class="col-md-6">
                <div><i class="fa fa-globe"></i> <b>{{ trans("text.wilaya") }}: </b> {{ $wilaya->$lib }}</div>
                <div ><b><i class="fa fa-users"></i> {{ trans("text.population") }}: </b>  <span class="direction"> <?php echo  strrev(wordwrap(strrev($wilaya->nbr_habitants), 3, ' ', true));  ?></span></div>
            </div>
            <div class="col-md-6">
                <div ><i class="fa fa-globe"></i> <b>{{ trans("text.nbr_moughataa") }}:</b> {{ $nbr_m }}  </div>
                <div><i class="fa fa-globe"></i> <b>{{ trans("text.nbr_commune") }} : </b> {{ $nbr_com }}</div>
                <div>
                    <i class="fa fa-eye"></i> <b><a id="voir_cart" rel="<?php echo $wilaya->id;?>" href="#">{{ trans("text.v_carte") }}</a>  </b>

                </div>

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

    @if($type_objet != ''  && count($objets) >0)

        <div class="panel panel-default souscond" style="text-align: left; ">
            <div class="panel-heading" role="tab" id="headingOne">

                <h4 class="panel-title">
                    <div style=" width: 25px; display: inline; margin-right:5px;" class="exp"><input style="" type="checkbox" disabled="disabled" data-toggle="tooltip" data-placement="bottom" title="" value="50" name="export[]" data-original-title="Export pdf/excel"></div>
                    <a class="collapsed grcond_wl" role="button" data-toggle="collapse" rel="type_obj" id_fiche="type_obj"  com="{{ $couche }}" module="3" data-parent="#0" href="#type_obj" aria-expanded="false" aria-controls="type_obj">
                        <i class="more-less fa fa-plus "></i>   {{ $type_objet->libelle }}
                    </a>
                </h4>
            </div> <div id="type_obj" class="panel-collapse collapse" role="tabpanel" aria-labelledby="50">
                <div class="panel-body">  <div class="panel-group" id="type_obj" role="tablist" aria-multiselectable="true">
                        <div id="res_type_objet">

                        </div></div></div></div>
        </div>


    @endif

        {{--}}@endif--}}

</div>
<div class="modal-footer">
    <form role="form"  id="formst" name="formst" class=""  method="post" >
        {{ csrf_field() }}
        <input type="hidden" name="exp_fiche" id="exp_fiche" />
        <input type="hidden" name="exp_eval" id="exp_eval"/>
        <input type="hidden" name="id_fiche" value="{{ $wilaya->id }}" />
        <input type="hidden" name="fiche_com" id="fiche_com" />
        <input type="hidden" name="date_ref" value="{{ $date_ref }}" />
        <input type="hidden" name="module" value="{{ $module }}" />
        <input type="hidden" name="id_groupe[]"  id="id_groupe[]" />
        <input type="hidden" name="niveau_exp"  id="niveau_exp" value="3" />


        <button type="button" class="btn btn-dark pull-left text-white" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans("text.fermer") }}</button>
        {{--<button type="button"  disabled="disabled" class="btn btn-success pull-right exp_fiche_excel text-white"><i class="fa fa-file-excel-o exp_excel"></i> {{ trans("text.export") }}</button>
        --}}
        <button type="button" onclick="OnButton1();" disabled="disabled" class="btn btn-primary pull-right imp_fiche text-white" > <i class="fa fa-print"></i> {{ trans("text.print") }} </button>

    </form>
    {{--}}<form role="form"  id="formst_wil" name="formst_wil" class=""  method="post" >
        {{ csrf_field() }}

        <input type="hidden" name="id_wil" id="id_wil" value="{{ $wilaya->id }}" />
        <input type="hidden" name="date_ref_wil" id="date_ref_wil" value="{{ $date_ref }}" />

        <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans("text.fermer") }}</button>
        <button type="button" onclick="OnButton2();"   class="btn btn-success pull-right "><i class="fa fa-file-excel-o exp_excel"></i> {{ trans("text.export") }}</button>
        <button type="button" onclick="OnButton1();"  class="btn btn-primary pull-right " > <i class="fa fa-print"></i> {{ trans("text.print") }} </button>

    </form>--}}
</div>
</div>
