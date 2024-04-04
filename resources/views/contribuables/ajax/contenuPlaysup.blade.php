<div class="form-row">
    @php
        $lib=trans('text_me.lib');
    @endphp
    <div class="col-md-6 form-group">
        <div class="card">
            <div class="card-header">
                {{ trans('text_me.mois_suspendus') }}
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach($moisSusp as $m)
                        <li class="list-group-item">
                            <div class="form-row">
                                <div class="col-md-10">
                                    {{ $m->mois->$lib }}
                                </div>
                                <div class="col-md-2 text-right">
                                    <button class="btn-dark text-white" onclick="reprendrePayement({{$contribuable->id}},{{$m->id}})">
                                        <i class="fas fa-arrow-right text-right" ></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6 form-group">
        <div class="card">
            <div class="card-header">
                {{ trans('text_me.mois_non_payes') }}
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach($mois as $mm)
                        <li class="list-group-item">
                            <div class="form-row">
                                <div class="col-md-2 ">
                                    <button class="btn-dark text-white" onclick="suspendrePayement({{$contribuable->id}},{{$mm->id}})">
                                        <i class="fas fa-arrow-left text-left" ></i>
                                    </button>
                                </div>
                                <div class="col-md-10">
                                    {{ $mm->libelle }}
                                </div>

                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>


</div>
