
<div class="modal-header">
    <h5 class="modal-title">Ajouter au programme du jour</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@php

@endphp
<div class="card">

                    <div class="card-body">
                     <div class="row">
                        <div id = "asdsasdas_id" class="col-md-6 form-group">
                            <label for="">Programmes<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select id="programmes"  name="programmes" data-live-search="true" class="selectpicker form-control" onchange=""  >
                            @foreach($all_programmes as $programme)
                            {{-- there is varibales named prorammes_in if this programme is in it show the text green --}}
                                <option 
                                @if(in_array($programme->id,$programmes_in))
                                   
                                    class = "text-success"
                                @endif  
                                    
                                 value="{{ $programme->id }}">{{ $programme->libelle }}</option>

                            @endforeach
                        </select>
                       
            </div>
        </div>
    <button class="btn btn-success btn-icon-split " onclick="addContrToProgramme('{{$contribuable->id}}')" container="savePayement">
        
            <span class="text">Ajouter</span>
        </button>
    </div>

</div>
