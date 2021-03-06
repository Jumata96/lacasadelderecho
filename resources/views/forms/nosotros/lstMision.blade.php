@extends('layouts2.app')
@section('titulo','Sección 02')

@section('main-content')
<br>
@foreach($mision as $datos)
<div class="row">
  <div class="col s12 m12 l12">
                <div class="card">
                  <div class="card-header">                    
                    <i class="fa fa-table fa-lg material-icons">receipt</i>
                    <h2>Mantenedor Sección 02</h2>
                  </div>
                 <form class="formValidate right-alert" id="formMision" method="POST" action="{{ url('/carrusel/grabar') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                  <div class="card-header" style="height: 50px; padding-top: 5px; background-color: #f6f6f6">
                        <div class="col s12 m12">
                          <a id="update" class="btn-floating waves-effect waves-light grey lighten-5 tooltipped" name="action" data-position="top" data-delay="500" data-tooltip="Guardar">
                            <i class="material-icons" style="color: #2E7D32">check</i>
                          </a>   
                          <a style="margin-left: 6px"></a>                          
                                                         
                        </div>  
                        @include('forms.scripts.modalInformacion')                               
                  </div>
                  
                  <br>                  
                  <div class="row cuerpo">
                   <input type="hidden" name="_token" value="{{ csrf_token() }}">
                   <input type="hidden" name="id" value="{{ $datos->id }}">
                    <div class="card white">
                        <div class="card-content">
                            <span class="card-title">Datos Generales</span>

                            <div class="row">
                              
                              <div class="input-field col s12 m6 l6">
                                <i class="material-icons prefix">clear_all</i>
                                <input id="titulo" name="titulo" type="text" data-error=".errorTxt2" maxlength="200" value="{{$datos->titulo}}">
                                <label for="titulo">Título</label>
                              </div>
                              <div class="input-field col s12 m6 l6">
                                <i class="material-icons prefix">mode_edit</i>
                                <textarea id="descripcion" name="descripcion" class="materialize-textarea" lenght="200" style="height: 80px">{{$datos->descripcion}}</textarea>
                                <label for="descripcion" class="">Descripción</label>
                              </div>                          
                            </div>

                        </div>
                    </div>
                 
                </div>
              </form>
              </div>
  </div>
</div>
@endforeach

<div class="row">
  <div class="col s12 m12 l12">
                <div class="card">
                  <div class="card-header">                    
                    <i class="fa fa-table fa-lg material-icons">receipt</i>
                    <h2>Detalle</h2>
                  </div>
                 
                  <div class="card-header" style="height: 50px; padding-top: 5px; background-color: #f6f6f6">
                        <div class="col s12 m12">
                          <a href="#addDMision" id="limpiar" class="btn-floating waves-effect waves-light grey lighten-5 modal-trigger">
                            <i class="material-icons" style="color: #03a9f4">add</i>
                          </a>
                          <a style="margin-left: 6px"></a>                          
                                                          
                        </div>  
                        @include('forms.scripts.modalInformacion')    
                        @include('forms.nosotros.addDMision')         
                        @include('forms.nosotros.updDMision')         
                  </div>
                                    
                  <div class="row">
                   <br>
                   @foreach($dmision as $datos)
                   <div class="col s12 m12 l3 item">
                    <div class="card" style="overflow: hidden;">
                      @if($datos->estado == 1)
                      <div class="card-image waves-effect waves-block waves-light center gradient-45deg-indigo-purple" style="height: 15.3rem;  background: #73BEC4">
                      @else
                      <div class="card-image waves-effect waves-block waves-light center gradient-45deg-indigo-purple" style="height: 15.3rem;  background: #5A6374">
                      @endif
                        @if($datos->tipo_img_icon == 1)
                        <img src="{{asset('/')}}{{$datos->url_imagen}}" alt="" class="z-depth-1" style="height: 100%; width: 100%">
                        @else
                        <i class="material-icons" style="color:white; font-size: 10rem; padding-top: 20px">{{$datos->icono}}</i>
                        @endif
                        <h4 class="card-title text-shadow m-0" style="padding-bottom: 35px">{{$datos->titulo}}</h4>
                      </div>
                      <ul class="card-action-buttons">
                        <li style="margin: 0px; padding: 0px">
                          <a href="#updDMision" id="upd_DMision{{$datos->id}}" class="btn-floating waves-effect waves-light indigo accent-2 tooltipped modal-trigger" data-position="top" data-delay="500" data-tooltip="Ver" data-id="{{$datos->id}}" data-imagen="{{$datos->imagen}}" data-titulo="{{$datos->titulo}}" data-descripcion="{{$datos->descripcion}}"  data-estado="{{$datos->estado}}" data-fila="{{$datos->fila}}" data-tipo="{{$datos->tipo_img_icon}}" data-idtipo="{{$datos->idtipo}}" data-icono="{{$datos->icono}}" >
                            <i class="material-icons">visibility</i>
                          </a>      
                        </li>
                        <li style="margin: 0px; padding: 0px">
                          <a href="#confirmacion{{$datos->id}}" class="btn-floating waves-effect waves-light  red accent-2 tooltipped modal-trigger" data-position="top" data-delay="500" data-tooltip="Eliminar">
                            <i class="material-icons">remove</i>
                          </a>
                        </li>
                        <li style="margin: 0px; padding: 0px">
                          @if($datos->estado == 1)                                      
                            <a href="#confirmacion2{{$datos->id}}" class="btn-floating waves-effect waves-light grey tooltipped modal-trigger" data-position="top" data-delay="500" data-tooltip="Desabilitar">
                              <i class="material-icons">clear</i></a>
                            @else
                            <a href="#confirmacion3{{$datos->id}}" class="btn-floating waves-effect waves-light green accent-4 tooltipped modal-trigger" data-position="top" data-delay="500" data-tooltip="Habilitar">
                              <i class="material-icons">check</i></a>
                            @endif
                        </li>
                        <li style="margin: 0px; padding: 0px">
                          <a class="btn-floating waves-effect waves-light light-blue accent-2">
                            <i class="material-icons activator">info_outline</i>
                          </a>
                        </li>
                      </ul>
                      <div class="card-content" style="padding-top: 15px; padding-right: 15px;">
                        <a href="#!"></a>
                        <p class="row mb-1" style="padding-bottom: 10px">
                          <small class="right">
                            <a href="#!">
                              @if($datos->estado == 1)
                                <span class="new badge teal accent-4 m-0" data-badge-caption="ACTIVO"></span>
                              @else
                                <span class="new badge grey m-0" data-badge-caption="NO DISPONIBLE"></span>
                              @endif
                            </a>
                          </small>
                          <small class="left" style="padding-top: 4px">{{$datos->fecha_creacion}}</small>
                        </p>
                        <p class="item-post-content">{{substr($datos->descripcion,0,85)}}...</p>
                      </div>
                      <div class="card-reveal" style="display: none; transform: translateY(0px);">
                        <span class="card-title grey-text text-darken-4">
                          <i class="material-icons right">close</i> {{$datos->titulo}}</span>
                        <p>{{$datos->descripcion}}</p>
                      </div>
                    </div>
                    
                    @include('forms.nosotros.scripts.mision.alertaConfirmacion')  
                    @include('forms.nosotros.scripts.mision.alertaConfirmacion2')  
                    @include('forms.nosotros.scripts.mision.alertaConfirmacion3')  
                  </div>
                  @endforeach

                </div>
              </div>
  </div>
</div>

@endsection

@include('forms.scripts.toast')
  
@section('script')
  @include('forms.nosotros.scripts.mision.mision')
  @include('forms.nosotros.scripts.mision.updMision')   
  @include('forms.nosotros.scripts.mision.addDMision')   
  @include('forms.nosotros.scripts.mision.updDMision')   
  @include('forms.nosotros.scripts.mision.habilitar')   
  @include('forms.nosotros.scripts.mision.desabilitar')   
  @include('forms.nosotros.scripts.mision.delDMision')   
@endsection

