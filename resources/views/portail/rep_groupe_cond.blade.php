
<script>
    $(document).ready(function() {
        $id="<?php echo $id_g; ?>";
        $('#'+id).DataTable({
            responsive: true,
            "oLanguage": {"sUrl": "/vendor/datatables/js/datatable-fr.json"},
            "iDisplayLength": 5,

        });
            });
    </script>
        <table class="table table-striped table-bordered " id="{{ $id_g }}">
    <thead>
    <tr>
        <th>Code</th>
        <th>Question</th>
        <th>Réponse</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rep_question as $q1)

        <tr>

            <td>{{ $q1["num"] }}</td>
            <td>{{ $q1["question"] }}</td>
            <td>{{ $q1["reponse"] }}</td>
        </tr>
    @endforeach

    </tbody>
</table>