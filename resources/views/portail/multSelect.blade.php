
<?php
  $lib=trans("text.libelle_base");
?>

    <label> * {{ trans("text.$libelle") }} </label>
    <select  id="ms" multiple="multiple" name="communes[]" required>
        @foreach($liste as $commune)

        <option id="{{ $commune['id']  }}" >{{ $commune[$lib]}}</option>
        @endforeach
    </select>
</div>



<script>
    $(function () {
        $('#ms').multiselect({
            includeSelectAllOption: true
        });
        $('#ms').on('change',function(){
            $('.multiselect').css('border-color','#ccc');
            $( '#form-errors' ).hide();
        });
        $('#date_ref').on('change',function(){
            $('#date_ref').css('border-color','#ccc');
            $( '#form-errors' ).hide();
        });
        $('#categorie_donnee').on('change',function(){
            $('#grp_donnee').css('border-color','#ccc');
            $( '#form-errors' ).hide();
        });
    });


</script>
