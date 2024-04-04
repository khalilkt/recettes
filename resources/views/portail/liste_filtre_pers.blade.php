<script>
    $(document).ready(function(e) {
        $('.selectpicker').selectpicker();
    });
</script>
<?php
$libelle = trans("text.libelle_base");
//$id_f='';
if($id_f != null)
 {
foreach($filtres as $filtre)
{
if($filtre->type != 3)
        {
//$id_f=$id_f.$filtre->id.',';
if (in_array($filtre->id, $params))
  {
?>
<div class="form-group">
    <label for="libelle"><?php echo  $filtre->$libelle ?></label>
    <select  multiple="multiple"  class="form-control select2" title="{{ trans('text.select') }}" onchange="getCount()">
        <!--<option value="0">{{ trans('text.all') }}</option>-->
        <?php
        $intervals = $filtre->get_intervals;
        $id_ff=$filtre->id;
        if(count($intervals) > 0)
        {
            foreach($intervals as $leg)
            {
                if($leg->valeur_min !=0 || $leg->valeur_max != 0)
                    echo "<option value='".$leg->id."'>".$leg->$libelle."</option>";
            }
        }
        else{
            if($filtre->type==0)
            {
                $set_question=$filtre->get_question;
                if($set_question->nature_question==5)
                {
                    $valeurs=explode(',',$set_question->valeurs);
                    $i=0;
                    foreach($valeurs as $val)
                    {
                        echo "<option value='".htmlentities($val)."+$id_ff'>".htmlentities($val)."</option>";
                    }
                }
                elseif($set_question->nature_question==1)
                {

                    echo "<option value='1+$id_ff'>".trans('text.oui')."</option>";
                    echo "<option value='0+$id_ff'>".trans('text.non')."</option>";
                }
            }
        }
        ?>
    </select>
</div>
<?php }  } }?>
<input type='hidden' id="id_fil",name="id_fil" value="<?php echo $id_f?>" />
<?php } else echo "Aucune filtre" ?>
