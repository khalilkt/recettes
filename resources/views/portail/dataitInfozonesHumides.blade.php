<?php
$text_right = trans("text.text_right");
$position = trans("text.position_right");
$direction = trans("text.direction");
$libelle = trans("text.libelle_base");
$k = 0;
$R = 0;
$clas = '';
$class = '';
?>
<style>
    .datatable {
        width: 100% !important;
    }

</style>


<div class="modal-header">

    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title" style="{{ $position }}" id="myModalLabel"><i class="fa fa-list-alt"></i> {{ $type }} :
        <a>{{ $nom_type }} </a>
        <!-- <a href="{{ url('/download/manuel.docx') }}" target="_blank"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Plus d'information" ></i></a>-->
    </h4>

</div>

<div class="modal-body">

    <div class="well well-lg">
        <div class="row" style="{{ $text_right }}">
            <div class="col-md-6">
                <table>
                    <tr>
                        <td><i class="fa fa-globe"></i> <b> {{ $set_niveau }} : </b></td>
                        <td>{{ $nom_niveau }}</td>

                    </tr>

                    <tr>
                        <td><i class="fa fa-globe"></i> <b>{{ trans("text.surface") }} :</b></td>
                        <td><span class="direction"> {{ $superficie }}km&sup2; </span></td>
                    </tr>


                </table>

            </div>

            <div class="col-md-6">
                <table>
                    <tr>
                        <td><i class="fa fa-globe"></i> <b>Année de creation : </b></td>
                        <td> {{  $annee }}</td>
                    </tr>

                </table>

            </div>

        </div>
        <br>
    </div>
    @if($get_img != null && count($get_img) > 0)

        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">

                @foreach($get_img as $set_img)

                   <?php
                    $clas='';
                    if($R==0)
                        $clas="active";
                    ?>

                    <li data-target="#carousel-example-generic" data-slide-to="{{ $set_img->id }}"
                        class="{{ $clas }}"></li>
                    <?php
                        $R++;
                       ?>
                @endforeach
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                @foreach($get_img as $set_img)
                    <?php

                    $class='';
                    if($k==0)
                        $class="active";

                    $set_pik = $set_img->emplacement.'/'.$set_img->id.".".$set_img->extension;
                    ?>

                    <div class="item {{ $class }}">
                        <img src="{{ url($set_pik) }}" style="width: 100%" alt="...">

                        <div class="carousel-caption">
                            {{
                              $set_img->libelle
                            }}
                        </div>
                    </div>
                 <?php
                    $k++;
                        ?>
                @endforeach

            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    @endif


</div>