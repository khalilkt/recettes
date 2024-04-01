@extends('../layout')
@section('page-title')
    Accueil
@endsection
@section('page-content')

    <script>
        $(document).ready(function(){
            $('.panel-group').on('hidden.bs.collapse', toggleIcon);
            $('.panel-group').on('shown.bs.collapse', toggleIcon);

            $('.souscond').on('hidden.bs.collapse', toggleIcon);
            $('.souscond').on('shown.bs.collapse', toggleIcon);
            $('.indicateur').on('hidden.bs.collapse', toggleIcon);
            $('.indicateur').on('shown.bs.collapse', toggleIcon);
            $('.sousindicateur').on('hidden.bs.collapse', toggleIcon);
            $('.sousindicateur').on('shown.bs.collapse', toggleIcon);

            $('.datatable').DataTable({
                "oLanguage": {"sUrl": "vendor/datatables/js/datatable-fr.json"},
                "iDisplayLength": 10,

            });

            $("#fiche_com").hide();
            $("#fiche_ev").hide();


        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
        function toggleIcon(e) {

            $(e.target)
                    .prev('.panel-heading')
                    .find(".more-less")
                    .toggleClass('fa-plus fa-minus');
        }



    </script>
    {!!$data!!}


@endsection