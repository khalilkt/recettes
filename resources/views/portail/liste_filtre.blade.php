
<script>

    $('.souscond_pres').on('hidden.bs.collapse', toggleIcon);
    $('.souscond_pres').on('shown.bs.collapse', toggleIcon);
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);
    $('.status-check').bootstrapToggle({
        on: 'On',
        off: 'Off'
    });

    function toggleIcon(e) {
        $(e.target)
                .prev('.card-header')
                .find(".more-less")
                .toggleClass('fa-plus fa-minus');
    }
</script>

<?php
$text_right=trans("text.text_right");
$position=trans("text.position_right");
$direction =trans("text.direction");
$lib = trans("text.libelle_base");
?>
<div class="modal-header">

    <h4 class="modal-title"> <i class="fa fa-list-alt"></i>  <a > Personalisation des filtres</a></h4>
    <button type="button" style="color: #000" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

</div>

<div class="modal-body">


                    <form class="form-control" id="form_filtre" role="form" method="POST">
                       <!-- <input type="hidden" name="_token" value="sVM4tcGtpofPngHY6IWcCusCSZy5ep4WJ0Gk0gKj">-->
                       <?php
                        foreach($cat_filtre as $cat)
                            {

                        $filtres = $cat->get_filtres()->where('niveau_geo',$niveau)
                                                      ->where('etat',1)
                                                      ->whereIn('type_contenu',[$module,1])
                                                      ->get();

                               ?>
                               <div class="card  souscond_pres">
                                   <div class="card-header" role="tab" id="{{ $cat->id }}">
                                    <h4 class="cord-title">
                                        <a class="collapsed grcond" onclick="toggleIconeCostum(this)" rel="{{$cat->id  }}" role="button" data-toggle="collapse" data-parent="#condition" href="#collapsecond{{ $cat->id }}" aria-expanded="false" aria-controls="collapsecond{{ $cat->id }}">
                                            <i class="more-less fa fa-plus pull-left"></i>  {{ $cat->libelle }} <span class="badge app_bgcolor pull-right"> {{ count($filtres) }} </span>
                                        </a>
                                    </h4>
                                </div>

                                <div id="collapsecond{{ $cat->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ $cat->id }}">
                                    <div class="card-body">
                                        <div id="res_{{ $cat->id  }}">
                                            <table class="table">
                                                <tr>
                                                    <th>Filtre</th>
                                                    <th>Etat</th>
                                                </tr>
                                                <?php
                                                foreach($filtres as $filtre)
                                                {
                                                ?>
                                                <tr>
                                                    <td>{{ $filtre->$lib }}</td>
                                                    <td style="padding:4px!important;">
                                                        <input type="checkbox" class="status-check" value="{{ $filtre->id }}" <?php if (in_array($filtre->id, $params)) echo "checked"; if($filtre->type==3) { ?> disabled="disabled"  checked <?php } ?>   data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
                                                    </td>
                                                </tr>
                                                <?php }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                           <?php
                            }
                        ?>

                        <button id="valider_filtre"  class="btn btn-success pull-right loguser">Valider</button>
                    </form>


                    <div class="clearfix"></div>
                    <div id="form-errors"></div>



    <script>
       /* $(".status-check").on("change", function () {
            if ($(this).parent().hasClass("off")) {
                alert("off");
            }
        });s*/
    </script>
</div>
<div class="modal-footer">
   <script>
       $("#valider_filtre").on('click',function(){

           var arr = [];
           var module ="<?php echo $module ?>"
           $("#form_filtre input[type=checkbox]:checked").each(function () {
               if($(this).val() != null)
                arr.push( $(this).val() );
           });
          if(arr.length==0)
            arr='null'
           $.ajax({
               type: 'get',
               url: racine+'liste_filtre_pers/'+arr+'/'+module,
               cache: false,
               success: function(data)
               {

                   $("#filtre").html(data);
                   //ajout legend du filtre
                   var module ="<?php echo $module ?>";
                   $.ajax({
                       type: 'get',
                       url: racine+'liste_legend_pers/'+arr+'/'+module,
                       cache: false,
                       success: function(data)
                       {
                           $("#legend_pers").html(data);
                           // apel getCount pour actualiser la carte apres led cangement de legande
                           getCount();
                           resetInit();
                           $('#basicModal5').modal("hide");
                       },
                       error: function () {
                           //loading_hide();
                           //$meg="Un problème est survenu. veuillez réessayer plus tard";
                           //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                       }
                   });
               },
               error: function () {

                   //loading_hide();
                   //$meg="Un problème est survenu. veuillez réessayer plus tard";
                   //$.alert("Un problème est survenu. veuillez réessayer plus tard");
               }
           });

           //$("#filtre").html(arr);
           return false;
       })

   </script>

</div>
