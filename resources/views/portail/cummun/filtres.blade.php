<!--les actions  -->
        <form method="get" action="#">
            <div class="row " >
                @if(!env('DCS_APP'))
                    @if($module==2)
                        <div class="col-md-3 left-side-bloc" style="padding-left:0px;">
                            <a href="{{ url('compdec') }}" class="btn btn-success btn-block">{{ trans('text.compdec') }}</a>
                        </div>
                    @elseif($module==3)
                        <div class="col-md-3 left-side-bloc" style="padding-left:0px;">
                            <a href="{{ url('/') }}" class="btn btn-success btn-block">{{ trans('text.mdp') }}</a>
                        </div>
                    @endif
                    <div class="left-side-bloc-t">
                        <div class="card  souscond_t" style="margin-bottom: 0px;!important;">
                            <div class="card-header" role="tab" id="headingOne" style="padding: 10px!important;">
                                <h4 class="card-title">
                                    <a class="collapsed trouvecom" role="button" data-toggle="collapse" data-parent="#pr"
                                    href="#fs" aria-expanded="false" aria-controls="fs" style="font-size:12px">
                                        <i class="more-less fa fa-plus "></i> {{ trans('text.trouve_commune') }}
                                    </a>
                                </h4>
                            </div>
                            <div id="fs" class="card-collapse collapse" role="tabpanel" aria-labelledby="fs">
                                <div class="card-body" style="padding: 3px!important;">
                                    <div class="panel-group" id="fs" role="tablist" aria-multiselectable="true"
                                        style="margin-bottom: 0px;">
                                        <div class="left-side-bloc-t">
                                            <h6>{{ trans('text.wilaya') }}</h6>
                                            <select class="form-control" id="wilaya_t" name="wilaya_t"
                                                    style="margin-bottom: 10px;"/>

                                            </select>
                                        </div>
                                        <div class="left-side-bloc-t" id="block_moug">
                                            <h6>{{ trans('text.moughataa') }}</h6>
                                            <select class="form-control" id="moughataa_t" name="moughataa_t"
                                                    style="margin-bottom: 10px;"/>

                                            </select>
                                        </div>
                                        <div class="left-side-bloc-t" id="block_com">
                                            <h6>{{ trans('text.commune') }}</h6>
                                            <select class="form-control" id="commune_t" name="commune_t"
                                                    style="margin-bottom: 10px;"/>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-3">
                    <div class="  left-side-bloc " >
                        <h6><i class="fa fa-tasks"></i> {{ trans('text.niveau') }}</h6>
                        <select   class="form-control" id="niveau_geo" name="niveau_geo" onchange="getCount()">
                            <option value="4"> {{ trans('text.vu_national') }}</option>
                            <option value="3" selected="selected">{{ trans('text.vu_wilaya') }}</option>
                            <option value="2">{{ trans('text.vu_moughataa') }}</option>
                            <option value="1" >{{ trans('text.vu_commune') }}</option>


                        </select>
                        <input type='hidden' id="ap_niveau" ,name="ap_niveau"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="left-side-bloc">
                        <h6><i class="fa fa-calendar"></i> {{ trans('text.annee_ref') }}</h6>
                        <select class="form-control select2" name="ref" id="ref" onchange="getCount()">
                            <?php
                            $date = date('Y');
                            for ($i = 2016; $i <= $date; $i++) {
                                if ($i == $date)
                                    echo "<option selected='selected'>" . $i . "</option>";
                                else
                                    echo "<option>" . $i . "</option>";
                            }
                            ?>
                        </select>
                        <!-- <input type="date" class="form-control" name="ref" id='ref' value="<?php echo date('Y-m-d');?>" onchange="filter(niveau_geo.value,this.value,legend.value,filtre_pop.value,filtre_notp.value,filtre_cond.value,filtre_pop1.value,filtre_pop2.value)" />-->
                    </div>
                </div>
                <div class="col-md-3">
                    <div class=" left-side-bloc">
                        <h6><i class="fa fa-eye"></i> {{ trans('text.legend') }}</h6>

                        <div id="legend_pers">


                        </div>
                    </div>
                </div>
                <div class=" left-side-bloc-t col-md-3">

                   {{--}} <a class="btn btn-info btn-block btn-rqt " id="costum">   {{ trans('text.requete_pers') }}</a>--}}

                    <a href="{{ url('activites') }}" class="btn btn-info btn-block ">{{ trans('text_me.activites') }}</a>
                    <a href='{{ url("recherche/$d_module") }}' class="btn btn-stat d-none"  >{{ trans('text.statistique') }}</a>
                </div>

                <!-- Modal Filtres -->
                <div class="modal fade" id="modal-filtres" tabindex="-1" role="dialog" aria-labelledby="modal-filtres-Label">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modal-filtres-Label">Filtres</h4>
                        </div>
                        <div class="modal-body">

                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
