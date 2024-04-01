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
            <h3 class="panel-title"> <div class="checkbox" style="margin:0px; padding:0px">
                    <label>
                        Fiche communale
                    </label>
                </div></h3>
        </div>
    <div class="panel-body" id="fiche_com">
            <div class="panel panel-default">
                <div class="panel-heading" >
                    <h4 class="panel-title">
                         Information sur la commune
                    </h4>
                </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered datatable">
                            <thead>
                            <tr>
                                <th style="width: 10%;">Numéro</th>
                                <th  style="width: 10%;">Code</th>
                                <th colspan="2">Question</th>
                                <th>Réponse</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ques1 as $q1)

                                <tr>

                                    <td  style="width: 10%;">{{ $q1["num"] }}</td>
                                    <td  style="width: 10%;">{{ $q1["code"] }}</td>
                                    <td colspan="2">{{ $q1["question"] }}</td>
                                <?php  if($q1["type"]==2)
                                {
                                    $rep='';
                                    if($q1["reponse"] != '')
                                    {
                                        if($q1["unite"] != '')
                                        {
                                            $rep= strrev(wordwrap(strrev($q1["reponse"]), 3, ' ', true)).' '.$q1["unite"];
                                        }
                                        else
                                        {
                                            $rep= strrev(wordwrap(strrev($q1["reponse"]), 3, ' ', true));
                                        }
                                    }
                                    else {
                                        $rep=$q1["reponse"];
                                    }
                                    echo "<td style='text-align: right; width: 100px;'>$rep </td>";
                                }
                                else
                                {
                                    $rep=$q1["reponse"];
                                    echo "<td style='width: 100px;'>$rep</td>";
                                }



                                ?>

                            @endforeach

                            </tbody>
                        </table>
                    </div>


            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        Questionnaire Maire
                    </h4>
                </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered datatable">
                            <thead>
                            <tr>
                                <th  style="width: 10%;">Numéro</th>
                                <th  style="width: 10%;">Code</th>
                                <th colspan="2">Question</th>
                                <th>Réponse</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ques2 as $q2)

                                <tr>
                                    <td  style="width: 10%;">{{ $q2["num"] }}</td>
                                    <td  style="width: 10%;">{{ $q2["code"] }}</td>
                                    <td colspan="2">{{ $q2["question"] }}</td>
                                    <?php  if($q2["type"]==2)
                                    {
                                        $rep2='';
                                        if($q2["reponse"] != '')
                                        {
                                            if($q2["unite"] != '')
                                            {
                                                $rep2= strrev(wordwrap(strrev($q2["reponse"]), 3, ' ', true)).' '.$q2["unite"];
                                            }
                                            else
                                            {
                                                $rep2= strrev(wordwrap(strrev($q2["reponse"]), 3, ' ', true));
                                            }
                                        }
                                        else {
                                            $rep2=$q2["reponse"];
                                        }
                                        echo "<td style='text-align: right; width: 100px;'>$rep2 </td>";
                                    }
                                    else
                                    {
                                        $rep2=$q2["reponse"];
                                        echo "<td style='width: 100px;'>$rep2</td>";
                                    }



                                    ?>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

            </div>
            <div class="panel panel-default">
                <div class="panel-heading" >
                    <h4 class="panel-title">
                         Questionnaire SG et Administration Communale

                    </h4>
                </div>

                    <div class="panel-body">
                        <table class="table table-striped table-bordered datatable">
                            <thead>
                            <tr>
                                <th style="width: 10%;">Numéro</th>
                                <th style="width: 10%;">Code</th>
                                <th colspan="2">Question</th>
                                <th>Réponse</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ques3 as $q3)

                                <tr>
                                    <td style="width: 10%;">{{ $q3["num"] }}</td>
                                    <td style="width: 10%;">{{ $q3["code"] }}</td>
                                    <td colspan="2">{{ $q3["question"] }}</td>
                                    <?php  if($q3["type"]==2)
                                    {
                                        $rep3='';
                                        if($q3["reponse"] != '')
                                        {
                                            if($q3["unite"] != '')
                                            {
                                                $rep3= strrev(wordwrap(strrev($q3["reponse"]), 3, ' ', true)).' '.$q3["unite"];
                                            }
                                            else
                                            {
                                                $rep3= strrev(wordwrap(strrev($q3["reponse"]), 3, ' ', true));
                                            }
                                        }
                                        else {
                                            $rep3=$q3["reponse"];
                                        }
                                        echo "<td style='text-align: right; width: 100px;'>$rep3 </td>";
                                    }
                                    else
                                    {
                                        $rep3=$q3["reponse"];
                                        echo "<td style='width: 100px;'>$rep3</td>";
                                    }



                                    ?>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" >
                    <h4 class="panel-title">
                          Questionnaire SC

                    </h4>
                </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered datatable">
                            <thead>
                            <tr>
                                <th style="width: 10%;">Numéro</th>
                                <th style="width: 10%;">Code</th>
                                <th colspan="2">Question</th>
                                <th>Réponse</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ques4 as $q4)

                                <tr>
                                    <td style="width: 10%;">{{ $q4["num"] }}</td>
                                    <td style="width: 10%;">{{ $q4["code"] }}</td>
                                    <td colspan="2">{{ $q4["question"] }}</td>
                                    <?php  if($q4["type"]==2)
                                    {
                                        $rep4='';
                                        if($q4["reponse"] != '')
                                        {
                                            if($q4["unite"] != '')
                                            {
                                                $rep4= strrev(wordwrap(strrev($q4["reponse"]), 3, ' ', true)).' '.$q4["unite"];
                                            }
                                            else
                                            {
                                                $rep4= strrev(wordwrap(strrev($q4["reponse"]), 3, ' ', true));
                                            }
                                        }
                                        else {
                                            $q4["reponse"];
                                        }
                                        echo "<td style='text-align: right; width: 100px;'>$rep4 </td>";
                                    }
                                    else
                                    {
                                        $rep4=$q4["reponse"];
                                        echo "<td style='width: 100px;'>$rep4</td>";
                                    }



                                    ?>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

            </div>


    </div>
</div>


@endsection
