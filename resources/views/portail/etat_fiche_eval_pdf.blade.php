@extends('portail/layout_export')
@section('page-title')
    Export pdf
@endsection
@section('page-content')

    <div class="well well-lg">
        <div class="row">
            <div class="col-md-6">
                <div><i class="fa fa-globe"></i> <b>Commune: </b> {{ $commune->libelle }}</div>
                <div><b><i class="fa fa-users"></i> Population: </b> <?php echo  strrev(wordwrap(strrev($commune->nbr_habitans), 3, ' ', true));  ?> </div>
            </div>
            <div class="col-md-6">
                <div ><i class="fa fa-globe"></i> <b>Wilaya: {{ $wilaya }} </b> </div>
                <div><i class="fa fa-globe"></i> <b>Moughataa: </b> {{ $moughataa }}</div>
            </div>

        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
                 <h3 class="panel-title">    Evaluation rapide </h3>
        </div>
        <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            Nombre conditions minimales  remplies <span class="badge app_bgcolor pull-right"> {{ $nbr_cond_min }} /23 </span>
                        </h4>
                    </div>
                        <div class="panel-body">

                                @foreach($conds as $cond)

                                    <div class="panel panel-default">
                                        <div class="panel-heading" >
                                            <h4 class="panel-title">
                                                       {{ $cond["libelle"] }} <span class="badge app_bgcolor pull-right"> {{ $cond["nbr_rep"] }} / {{ $cond["nbr_ques"] }} </span>
                                            </h4>
                                        </div>

                                            <div class="panel-body">

                                            </div>
                                    </div>
                                @endforeach


                            <!-- Fin body condition mimimales-->
                        </div>
                    </div>

                <div class="panel panel-default">
                    <div class="panel-heading" >
                        <h4 class="panel-title">
                            Note de performance <span class="badge app_bgcolor pull-right"> {{ $note_perf }} / 3 </span>
                        </h4>
                    </div>

                        <div class="panel-body">

                                <!-- 1er niveau -->
                                @foreach($perfs as $cond)

                                    <div class="panel panel-default">
                                        <div class="panel-heading" >
                                            <h4 class="panel-title">
                                                {{  $cond["libelle"] }} ({{$cond["note"] }} / 3)

                                            </h4>
                                        </div>

                                            <div class="panel-body">
                                                <!- 2eme niveau -->

                                                    @foreach($cond["Sous_criteur"] as $cond1)

                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" >
                                                                <h6 class="panel-title">

                                                                    {{ $cond1["libelle"] }}   ( {{$cond1["note"] }} / 3)

                                                                </h6>
                                                            </div>

                                                                <div class="panel-body">
                                                                    <div id="res1_{{ $cond1["id"]  }}"> </div>
                                                                </div>

                                                        </div>
                                                    @endforeach



                                            </div>
                                    </div>
                                @endforeach






                        </div>

                </div>
            </div>

        </div>



@endsection
