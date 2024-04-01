@extends('portail/layout_export')
@section('page-title')
    Export pdf
@endsection
@section('page-content')

    @if(App::isLocale('ar'))
        <link href="{{ URL::asset('css/test.css') }}" rel="stylesheet">
    @endif

    <div class="exp_info">
        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    <table>
                        <tr>
                            <td> <b>{{ trans("text.moughataa") }} : </b></td>
                            <td> {{ $libelle }}</td>
                        </tr>
                        <tr><td><b>{{ trans("text.wilaya") }} : </b></td>
                            <td>{{ $wilaya }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ trans("text.population") }} : </b></td>
                            <td><?php echo  $population  ?></td>
                        </tr>
                        <tr>
                            <td><b>{{ trans("text.surface") }} : </b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>{{ trans("text.nbr_commune") }}: </b></td>
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
                    </table>
                </td>
                <td style="width: 50%;">
                    <?php if($path_cart != null)
                    { ?>
                    <img  style="width:350px;margin-left:-15px; height: 300px" src="{{ url($path_cart)  }}">
                    <?php } ?>
                </td>
            </tr>
            </table>



    </div>


    <div class="row">

        <div class="col-md-12">

            <div>
                {!!$data!!}
            </div>



        </div>


    </div>


@endsection
