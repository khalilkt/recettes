<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th width="30px"></th>
        <th>{{ trans('text.libelle') }}</th>
        <th>{{ trans('text_me.code_equipement') }}</th>
        <th>{{ trans('text_me.date_equipement') }}</th>
        <th>{{ trans('text_me.type_equipement') }}</th>
        <th>{{ trans('text_me.secteur') }}</th>
        <th>{{ trans('text_me.localite') }}</th>
        <th width="50px">{{ trans('text_me.publique') }}</th>

    </tr>
    </thead>
    <tbody>
    @foreach($equipements as $quipement)

        @php
            $cord = $quipement->get_coordonnees_equipements_geo;

            //var_dump("$cord");
        @endphp

        @if($cord != null &&  $cord->lat != null && $cord->lng != null )
            <tr>
                <td>{{ $quipement->id }}</td>
                <td>{{ $quipement->$lib }}</td>
                <td>{{ $quipement->code }}</td>
                <td>{{ $quipement->date_acquisition }}</td>
                <td>{{ $quipement->ref_types_equipement->$lib }}</td>
                <td>{{ $quipement->secteur->$lib }}</td>
                <td>{{ $quipement->localite->$lib }}</td>
                <td>{{ $quipement->patrimoine_public }}</td>
            </tr>
        @endif

    @endforeach
    </tbody>
</table>
