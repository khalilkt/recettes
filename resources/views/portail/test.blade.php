
@extends('../layout')
@section('page-title')
    Accueil
@endsection
@section('page-content')

    <script>
        $(document).ready(function () {
            $("#d").on("click",function(){
                 $.ajax({
                 type: 'get',
                 url: 'datatable',
                 cache: false,
                 success: function(data)
                 {
                 $('#result').empty();
                 $('#result').html(data);

                 },
                 error: function () {
                 //$meg="Un problème est survenu. veuillez réessayer plus tard";
                 $.alert("Un problème est survenu. veuillez réessayer plus tard");
                 }
                 });
                return false;
            })
        });

    </script>
 <div id="result"></div>
    <div class="container">
        <a href="#" id="d">
            Charger la liste
        </a>
       </div>

@endsection

