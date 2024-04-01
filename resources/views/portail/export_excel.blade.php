@extends('portail/layout_export_excel')
@section('page-title')
    {{ $libelle1 }}
@endsection
@section('page-content')
    <h1>{{ utf8_decode(htmlentities(trans("text.header_titre1"))) }} </h1>
    <h2>{{ utf8_decode(htmlentities(trans("text.header_titre2"))) }}</h2>
 <style>

     .table {
         width: 100%;
         max-width: 100%;
         margin-bottom: 20px;
     }
     .table > thead > tr > th,
     .table > tbody > tr > th,
     .table > tfoot > tr > th,
     .table > thead > tr > td,
     .table > tbody > tr > td,
     .table > tfoot > tr > td {
         padding: 8px;
         line-height: 1.42857143;
         vertical-align: top;
         border-top: 1px solid #dddddd;
     }
     .table > thead > tr > th {
         vertical-align: bottom;
         border-bottom: 2px solid #dddddd;
     }
     .table > caption + thead > tr:first-child > th,
     .table > colgroup + thead > tr:first-child > th,
     .table > thead:first-child > tr:first-child > th,
     .table > caption + thead > tr:first-child > td,
     .table > colgroup + thead > tr:first-child > td,
     .table > thead:first-child > tr:first-child > td {
         border-top: 0;
     }
     .table > tbody + tbody {
         border-top: 2px solid #dddddd;
     }
     .table .table {
         background-color: #ffffff;
     }
     .table-condensed > thead > tr > th,
     .table-condensed > tbody > tr > th,
     .table-condensed > tfoot > tr > th,
     .table-condensed > thead > tr > td,
     .table-condensed > tbody > tr > td,
     .table-condensed > tfoot > tr > td {
         padding: 5px;
     }
     .table-bordered {
         border: 1px solid #dddddd;
     }
     .table-bordered > thead > tr > th,
     .table-bordered > tbody > tr > th,
     .table-bordered > tfoot > tr > th,
     .table-bordered > thead > tr > td,
     .table-bordered > tbody > tr > td,
     .table-bordered > tfoot > tr > td {
         border: 1px solid #dddddd;
     }
     .table-bordered > thead > tr > th,
     .table-bordered > thead > tr > td {
         border-bottom-width: 2px;
     }
     .table-striped > tbody > tr:nth-of-type(odd) {
         background-color: #f9f9f9;
     }
     .table-hover > tbody > tr:hover {
         background-color: #f5f5f5;
     }
     table col[class*="col-"] {
         position: static;
         float: none;
         display: table-column;
     }
     table td[class*="col-"],
     table th[class*="col-"] {
         position: static;
         float: none;
         display: table-cell;
     }


     .exp_info
     {
         display: block;
         min-height: 20px;
         padding: 19px;
         margin-bottom: 20px;
         background-color: #f5f5f5;
         border: 1px solid #e3e3e3;
         border-radius: 6px;
         -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
         box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
         /*padding: 24px;
         border-radius: 6px;*/
     }

<?php
 $lib = trans("text.libelle_base");
 ?>
 </style>
    <div class="exp_info">
        @if((App::isLocale('ar')))
            <table style="width: 100%" align="right">

                <tr > <td>{{ $commune->$lib }}</td> <td><b>{{ trans("text.commune") }} : </b></td> </tr>
                <tr><td>{{ $moug }}</td> <td><b>{{ trans("text.moughataa") }} : </b></td></tr>
                <tr><td>{{ $wil }} </td> <td><b>{{ trans("text.wilaya") }} : </b> </td></tr>
                <tr><td><?php echo  strrev(wordwrap(strrev($pop), 3, ' ', true));  ?></td> <td><b>{{ trans("text.population") }} : </b></td></tr>

                <tr>

                    <td>{{ $commune->surface }} km&sup2;</td>
                    <td> <b>{{ trans("text.surface") }} :</b></td>
                </tr>
                <tr>
                    <td>{{ $commune->nbr_villages_localites }}</td>
                    <td> <b>{{ trans("text.nbr_village") }} :</b></td>

                </tr>
                <tr>
                    <td>{{ $commune->nbr_conseillers_municipaux }}</td>
                    <td> <b>{{ trans("text.nbr_cons") }}: </b></td>

                    <td></td>
                </tr>
                <tr>
                    <td> {{  $commune->nbr_employes_municipaux_permanents }}</td>
                    <td><i ></i> <b>{{ trans("text.nbr_emp_per") }} : </b></td>

                </tr>
                <tr>
                    <td><?php if($commune->pnidlle==1) echo trans("text.oui"); else echo trans("text.non") ?></td>
                    <td><i ></i> <b> {{ trans("text.pndidlle") }} :  </b></td>


                </tr>
                <tr>
                    <td> {{ $commune->decret_de_creation }}</td>
                    <td><b> {{ trans("text.decret") }} : </b></td>

                </tr>

            </table>
        @else
            <table style="width: 100%" align="right">

                <tr ><td><b>{{ trans("text.commune") }} : </b></td>  <td>{{ $commune->$lib }}</td></tr>
                <tr><td><b>{{ trans("text.moughataa") }} : </b></td><td>{{ $moug }}</td> </tr>
                <tr><td><b>{{ trans("text.wilaya") }} : </b> </td><td>{{ $wil }} </td></tr>
                <tr><td><b>{{ trans("text.population") }} : </b></td><td><?php echo  strrev(wordwrap(strrev($pop), 3, ' ', true));  ?></td> </tr>

                <tr>
                    <td> <b>{{ trans("text.surface") }} :</b></td>
                    <td>{{ $commune->surface }} km&sup2;</td>
                </tr>
                <tr>
                    <td> <b>{{ trans("text.nbr_village") }} :</b></td>
                    <td>{{ $commune->nbr_villages_localites }}</td>
                </tr>
                <tr>
                    <td> <b>{{ trans("text.nbr_cons") }}: </b></td>
                    <td>{{ $commune->nbr_conseillers_municipaux }}</td>

                    <td></td>
                </tr>
                <tr>
                    <td><i ></i> <b>{{ trans("text.nbr_emp_per") }} : </b></td>
                    <td> {{  $commune->nbr_employes_municipaux_permanents }}</td>
                </tr>
                <tr>
                    <td><i ></i> <b> {{ trans("text.pndidlle") }} :  </b></td>
                    <td><?php if($commune->pnidlle==1) echo trans("text.oui"); else echo trans("text.non") ?></td>

                </tr>
                <tr>
                    <td><b> {{ trans("text.decret") }} : </b></td>
                    <td> {{ $commune->decret_de_creation }}</td>
                </tr>

            </table>
        @endif

    </div>

    <div class="row">

        <div class="col-md-12">

            <div>
                {!!$data!!}
            </div>




        </div>


    </div>


@endsection

