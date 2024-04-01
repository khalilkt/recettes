<?php
$text_right=trans("text.text_right");
$position=trans("text.position_right");
$direction =trans("text.direction");
$lib = trans("text.libelle_base");
?>
<div class="row">
    <div class="col-md-12">


            <table class="table table-striped table-bordered">

                <tbody>
                @foreach($objets as $ob)

                    <tr>
                        <td>{{ $ob->$lib }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>


    </div>
</div>