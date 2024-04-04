<script>
    $(document).ready(function(e) {
        $('.selectpicker').selectpicker();
    });
</script>
<?php
$libelle = trans("text.libelle_base"); ?>


<select class="form-control select2" name="legend" id="legend" onchange="getCount()" >
    <?php
    foreach($filtres as $filtre)
    {
        if($filtre->get_intervals->count() > 0)
            echo "<option value='".$filtre->id."'>".$filtre->$libelle."</option>";
    } ?>

</select>
