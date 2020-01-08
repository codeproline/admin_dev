@extends('app.admin.layouts.default')


{{-- Web site Title --}}
@section('title') {{{ $title }}} :: @parent @stop


{{-- Content --}}
@section('main')

<div class="panel panel-flat" >
                        <div class="panel-heading">
                           
                            <h4 class="panel-title">{{trans('settings.commission_settings')}}</h4>
<div class="heading-elements">
                            <button id="enable_settings" class="btn btn-primary">{{ trans('settings.enable_edit_mode') }}</button>
</div>


                            
                        </div>

                        <div class="panel-body"> 
                          <form id="settings">                             
                        <legend>{{trans('settings.commission_settings')}}</legend>
                                       
                                <fieldset>                                   
                                   

                                      <div class="form-group">
                                         <div class="row">
                                            <div class="col-sm-6">
                                                <label for="">{{trans('ticket_config.direct_referral')}} (%):</label>
                                            </div>
                                            <div class="col-sm-4">
                                                 <a class="settings form-control"  id="direct_referral" data-type='text' data-pk="{{$settings->id}}" data-title='Direct Referral' data-name="direct_referral">
                                                 {{ $settings->direct_referral}}
                                                </a>


                                            </div>
                                           
                                        </div>
                                    </div>   

                                 
                              

                                </fieldset>                            
                 
                     
                    </form>
                        
                        
                       
                </div>
              

            </div>
                  

            
@endsection
