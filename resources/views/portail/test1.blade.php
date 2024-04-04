

        <table  class="table table-hover table-condensed users" style="width:100%">
            <thead>
            <tr>
                <th>Id</th>
                <th>Libelle</th>
                <th>nbr_habitant</th>

            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">

        if (!$.fn.dataTable.isDataTable('.users')) {
            oTable = $('.users').DataTable({
                oLanguage: {
                    sUrl: "/vendor/datatables/js/datatable-fr.json",
                },
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('datatable.getposts') }}",
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'libelle', name: 'libelle'},
                    {data: 'nbr', name: 'libelle_ar'}

                ]
            });
        }

    </script>


