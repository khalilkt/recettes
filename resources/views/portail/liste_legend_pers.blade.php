<?php
$libelle = trans("text.libelle_base"); ?>

<select class="form-control select2" name="legend" id="legend" onchange="getCount()" >
<?php

    if($id_f != null)
    {
    foreach($filtres as $filtre)
    {
    //$id_f=$id_f.$filtre->id.',';
    if (in_array($filtre->id, $params))
    {
        if($filtre->get_intervals->count() > 0)
    echo "<option value='".$filtre->id."'>".$filtre->$libelle."</option>";
    }

     }  }?>
</select>
