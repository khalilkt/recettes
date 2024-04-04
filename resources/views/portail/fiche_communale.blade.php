<?php
$lang = App::getLocale();
?>
<script>
    $(document).ready(function () {
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);

        $('.souscond').on('hidden.bs.collapse', toggleIcon);
        $('.souscond').on('shown.bs.collapse', toggleIcon);
        $('#date_evaluations').hide();
        var date_v = '<?php echo $date_ev ?>';
        var fiche = '<?php echo $id_fiche ?>';
        var module = '<?php echo $module ?>';
        $("#id_fiche_com").val(fiche);
        $("#date_eva").val(date_v)
        var nbr_eval = '<?php echo $nbr_ens_eval ?>';
        if (nbr_eval > 0) {
            $("#comp").removeAttr('disabled');
            $("#comp_cl").removeClass("disabled");
            $("#verif_ens").show();

        }
        else {
            $("#comp").attr('disabled', 'disabled');
            $("#comp_cl").addClass("disabled");
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

    $("a.grcond").on('click', function () {
        if ($(this).find('.fa-plus').length) {
            var items = [];
            var id = $(this).attr('rel');
            var id_fiche = $(this).attr('id_fiche');
            var comp = $(this).attr('com');
            var niveau = $(this).attr('niveau');
            var id_obj = $(this).attr('id_obj');
            var date_rep = $(this).attr('date_rep');
            var module = '<?php echo $module ?>';

            $.ajax({
                type: 'get',
                url: racine + 'reponseQuestionsByGroupes/' + id + '+' + id_fiche + '+' + comp + '+' + niveau + '+' + id_obj + '+' + date_rep + '+' + module,
                //('tyru')->result,
                cache: false,
                success: function (data) {
                    $("#res_" + id).html('');
                    $("#res_" + id).html(data);
                },
                error: function () {
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert("Un problème est survenu. veuillez réessayer plus tard");
                }
            });
        }

    });

    $(".exp").on('change', function () {
        var l = '<?php echo $lang ?>'
        var items = [];
        $("input[name='id_groupe[]']").val('');

        $("input[name='export[]']:checked").each(function () {
            items.push($(this).val());
        });
        if (items.length > 0) {
            $(".exp_fiche_excel").removeAttr('disabled');
            //if(l != 'ar')
            $(".imp_fiche").removeAttr('disabled');
        }
        else {
            $(".imp_fiche").attr('disabled', 'disabled');
            $(".exp_fiche_excel").attr('disabled', 'disabled');

        }

        $("input[name='id_groupe[]']").val(items);
    });

    $('.sp').tooltip();


</script>


<div class="row">

    <div class="col-md-12">

        <input type="hidden" id="nbr_ens_eval" value="<?php echo $nbr_ens_eval ?>">

        <div id="resultat_fiche">
            <!--<form  id='export_data' class=""  method='post'>-->
        {!!$data!!}
        <!-- </form> -->

        </div>


        @if($module==3 && $niveau==1)
            @php
                $lib = trans("text.libelle_base");
                $text_right = trans("text.text_right");
                $pul = trans("text.pul");
                $title_export = trans("text.titre_export");
                $title_st = trans("text.title_st");

            @endphp


            <div class="panel panel-default souscond" style="text-align: left; ">
                <div class="panel-heading" role="tab" id="headingOne">

                    <h4 class="panel-title">
                        <div style=" width: 25px; display: inline; margin-right:5px;" class="exp"><input style=""
                                                                                                         type="checkbox"
                                                                                                         disabled="disabled"
                                                                                                         data-toggle="tooltip"
                                                                                                         data-placement="bottom"
                                                                                                         title=""
                                                                                                         value="50"
                                                                                                         name="export[]"
                                                                                                         data-original-title="Export pdf/excel">
                        </div>
                        <a class="collapsed grcond" role="button" data-toggle="collapse" rel="compdec"
                           id_fiche="compdec" com="{{ $commune->id }}" module="3" data-parent="#0" href="#compdec"
                           aria-expanded="false" aria-controls="compdec">
                            <i class="more-less fa fa-plus "></i> {{ trans("text.equipe_communale") }}
                        </a>
                    </h4>
                </div>
                <div id="compdec" class="panel-collapse collapse" role="tabpanel" aria-labelledby="50">
                    <div class="panel-body">
                        <div class="panel-group" id="compdec" role="tablist" aria-multiselectable="true">
                            <div id="res_compdec">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif


    </div>


</div>
