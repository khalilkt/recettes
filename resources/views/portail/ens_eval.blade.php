@php
    use Carbon\Carbon;
@endphp
<script>
    $('#ens_date_evaluations').change(function () {
        var nb = $("#ens_date_evaluations :selected").length;
        if (nb > 0) {
            $(".valid_com").removeAttr('disabled');
        }

    });
    $('.selectpicker').selectpicker(
        {
            size: 10
        });
</script>
<div class="col-md-9">
    <select id="ens_date_evaluations" name="ens_date_evaluations[]" class="form-control selectpicker"
            title="Choisissez une date" multiple>

        @foreach($date_eval as $date)

            <option value="{{ $date }}">{{  Carbon::parse($date)->format('d-m-Y') }} </option>';

        @endforeach
    </select>
</div>
<div class="col-md-3">
    <button type="button" onclick="result_eval()" disabled="disabled" class="btn btn-primary valid_com">Comparer
    </button>
</div>


