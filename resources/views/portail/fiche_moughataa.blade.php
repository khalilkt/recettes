<?php
$lang = App::getLocale();
?>
<script>
    $(document).ready(function(){
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);

        $('.souscond').on('hidden.bs.collapse', toggleIcon);
        $('.souscond').on('shown.bs.collapse', toggleIcon);
        $('#date_evaluations').hide();
        var date_v ='<?php echo $date_ev ?>';
        var fiche ='<?php echo $id_fiche ?>' ;
        $("#id_fiche_com").val(fiche);
        $("#date_eva").val(date_v)
        var nbr_eval ='<?php echo $nbr_ens_eval ?>';
        if( nbr_eval > 0)
        {
            $("#comp").removeAttr('disabled');
            $("#comp_cl").removeClass( "disabled" );
            $("#verif_ens").show();

        }
        else {
            $("#comp").attr('disabled','disabled');
            $("#comp_cl").addClass( "disabled" );
            $("#verif_ens").hide();
        }
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
    $("a.grcond").on('click',function() {
        if($(this).find('.fa-plus').length)
        {
            var items =[];
            var id_moug =  ' {{ $id }}';
            var id =  $(this).attr('rel');
            var date_ref ='{{ $date_ref }}';
            var comp=$(this).attr('com');
            var module=$(this).attr('module');
            var niveau=$(this).attr('niveau');

            $.ajax({
                type: 'get',
                url: racine+'reponseQuestionsByGroupes_agregation/'+id+'+'+id_moug+'+'+date_ref+'+'+comp+'+'+module+'+'+niveau,
                //('tyru')->result,
                cache: false,
                success: function(data)
                {
                    $("#res_"+id).html('');
                    $("#res_"+id).html(data);
                },
                error: function () {
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert("Un problème est survenu. veuillez réessayer plus tard");
                }
            });
        }
    });
    $(".exp").on('change',function(){
        var l = '<?php echo $lang ?>'
        var items = [];
        $("input[name='id_groupe[]']").val('');

        $("input[name='export[]']:checked").each(function(){
            items.push($(this).val());
        });
        if(items.length > 0)
        {
            $(".exp_fiche_excel").removeAttr('disabled');
            //if(l != 'ar')
            $(".imp_fiche").removeAttr('disabled');
        }
        else {
            $(".imp_fiche").attr('disabled','disabled');
            $(".exp_fiche_excel").attr('disabled','disabled');

        }
        $("input[name='id_groupe[]']").val(items);
    });

    $('.sp').tooltip();


</script>



<div class="row">

    <div class="col-md-12">

        <input type="hidden" id="nbr_ens_eval" value="<?php echo $nbr_ens_eval ?>">

        <div id="resultat_fiche_agr" >
            <!--<form  id='export_data' class=""  method='post'>-->
            {!!$data!!}
                    <!-- </form> -->

        </div>



    </div>


</div>
