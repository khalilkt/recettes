<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <b>{{ trans('text_me.contribuablenonPr') }}</b>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table link="{{url('programmes/getDroitsDT/'.$programme->id)}}" colonnes="libelle,actions" class="table table-bordered datatableshow2">
                            <thead>
                            <tr>
                                <th>{{ trans('text_me.Articlecontribuable') }} }}</th>
                                <th width="40px">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <b>{{ trans('text_me.contribuableSelectionner') }} :</b> "{{$programme->libelle }} / {{$programme->date }}"
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="group-elements sortable list-group" lien="programmes/updatedroits" count="0" datatable-source=".datatableshow2" idgroup="{{ $programme->id }}">
                            @foreach($contribuables as $contribuab)
                                <li id="{{ $contribuab->contribuable_id }}" class="list-group-item">
                                    {{ $contribuab->contribuable->libelle }}
                                    <button type="button"
                                            idelt="{{ $contribuab->id }}"
                                            libelle="{{ $contribuab->contribuable->libelle}}"
                                            class="close"
                                            aria-hidden="true"
                                            onclick="updateGroupeElements(this)"
                                    >&times;</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
