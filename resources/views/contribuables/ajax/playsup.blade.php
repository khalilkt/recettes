<div class="modal-header">
    @php
        $lib=trans('text_me.lib');
    @endphp
    <h5 class="modal-title">{{ trans('text_me.supension') }}  {{$contribuable->$lib}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="card mb-3 col-md-12" id="saveSuspension">
    <div class="card-header">{{ trans('text_me.supension') }} / {{$contribuable->$lib}}</div>
    <div class="card-body col-md-12">
           <div id="divContenueplaysup">
               @include('contribuables.ajax.contenuPlaysup',['contribuable' => $contribuable,'mois' => $mois, 'moisSusp' => $moisSusp])
           </div>
    </div>
</div>

