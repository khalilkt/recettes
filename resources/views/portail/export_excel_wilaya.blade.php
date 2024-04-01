@extends('portail/layout_export_excel')
@section('page-title')
    export
@endsection
@section('page-content')
    <h1>{{ trans("text.header_titre1") }} </h1>
    <h2>{{ trans("text.header_titre2") }}</h2>
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


    </style>
    <div class="exp_info">
        @if((App::isLocale('ar')))
            <table style="width: 100%">
                <tr>

                    <td>{{ $libelle }}</td>
                    <td> <b>{{ trans("text.wilaya") }} : </b> </td>

                </tr>
                <tr>

                    <td>{{ $nbr }}</td>
                    <td><b>{{ trans("text.nbr_moughataa") }} : </b>  </td>
                </tr>
                <tr>
                    <td>{{ $population }}</td>
                    <td><b>{{ trans("text.population") }} : </b> </td>
                </tr>
                <tr>
                    <td>{{ $nbr_com }}</td>
                    <td><b>{{ trans("text.nbr_commune") }} : </b> </td>
                </tr>
                <tr>
                    <td></td>
                    <td><b>{{ trans("text.nbr_village") }}: </b></td>
                </tr>
                <tr>
                    <td></td>
                    <td><b>{{ trans("text.nbr_cons") }}: </b></td>
                </tr>
                <tr>
                    <td></td>
                    <td>{{ trans("text.nbr_emp_per") }}</td>
                </tr>

                <tr>
                    <td></td>
                    <td><b>{{ trans("text.decret") }}: </b></td>
                </tr>
                <tr>
                    <td>{{ $date_ref }}</td>
                    <td><b>{{ trans("text.date_ref") }} : </b> </td>
                </tr>
            </table>
        @else
            <table style="width: 100%">
                <tr>
                    <td> <b>{{ trans("text.wilaya") }} : </b> </td>
                    <td>{{ $libelle }}</td>

                </tr>
                <tr>
                    <td><b>{{ trans("text.nbr_moughataa") }} : </b>  </td>
                    <td>{{ $nbr }}</td>
                </tr>
                <tr>
                    <td><b>{{ trans("text.population") }} : </b> </td>
                    <td>{{ $population }}</td>
                </tr>
                <tr>
                    <td><b>{{ trans("text.nbr_commune") }} : </b> </td>
                    <td>{{ $nbr_com }}</td>
                </tr>
                <tr>
                    <td><b>{{ trans("text.nbr_village") }}: </b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>{{ trans("text.nbr_cons") }}: </b></td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ trans("text.nbr_emp_per") }}</td>
                    <td></td>
                </tr>

                <tr>
                    <td><b>{{ trans("text.decret") }}: </b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>{{ trans("text.date_ref") }} : </b> </td>
                    <td>{{ $date_ref }}</td>
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

