@php
    use Carbon\Carbon;
@endphp
@extends('layout_export_excel')
@section('page-title')
    export
@endsection
@section('page-content')
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

        .exp_info {
            display: block;
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 6px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
            /*padding: 24px;
            border-radius: 6px;*/
        }


    </style>
    <div class="exp_info">
        <table border="1" >
            <tr>
                <td align="center" colspan="6"><b>{{trans('text_archive.liste_des_archives')}}</b></td>
            </tr>
        </table>
        <table class="table" border="1">
            <tr bgcolor="#add8e6">
                <th>{{ trans('text.libelle') }}</th>
                <th>{{ trans('text.date') }}</th>
                <th>{{ trans('text_archive.service') }}</th>
                <th>{{ trans('text_archive.type_archive') }}</th>
            </tr>
            @foreach($archives as $archive)
                <tr>
                    <td> {{ $archive->libelle }} </td>
                    <td> {{ Carbon::parse($archive->date_archivage)->format('d-m-Y') }} </td>
                    <td> {{ ($archive->service) ? $archive->service->libelle : '' }} </td>
                    <td> {{ ($archive->type_archive) ? $archive->type_archive->libelle : '' }} </td>
                </tr>
            @endforeach
        </table>

    </div>




@endsection

