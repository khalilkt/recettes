<label for="libelle"> Note de performances</label>
<select class="selectpicker form-control"  name="filtre_notp"  id="filtre_notp" onchange="filter(niveau_geo.value,ref.value,legend.value,filtre_pop.value,this.value,filtre_cond.value)">

    <option value="0">Tous</option>
    <div id="group_note">
        @foreach($filter_note as $n)

            <option value="{{ $n->id }}">{{ $n->libelle }}</option>
        @endforeach
    </div>
</select>