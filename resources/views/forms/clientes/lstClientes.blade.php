@extends('layouts2.app')
@section('titulo','Lista de usuarios')

@section('main-content')
<br>
<div class="row">
  <div class="col s12 m12 l12">
                <div class="card">
                  <div class="card-header">                    
                    <i class="fa fa-table fa-lg material-icons">receipt</i>
                    <h2>LISTA DE USUARIOS</h2>
                  </div>
                 
                  <div class="card-header" style="height: 50px; padding-top: 5px; background-color: #f6f6f6">
                        <div class="col s12 m12">
                          <a class="btn-floating waves-effect waves-light grey lighten-5 tooltipped" href="{{ url('/cliente/nuevo') }}" data-position="top" data-delay="500" data-tooltip="Nuevo">
                            <i class="material-icons" style="color: #03a9f4">add</i>
                          </a>
                          <a style="margin-left: 6px"></a>                          
                                                         
                        </div>  
                        @include('forms.scripts.modalInformacion')         
                  </div>
                                    
                  <div class="row cuerpo">
                    <?php 

                      $bandera = false;

                      if (count($clientes) > 0) {
                        # code...
                        $bandera = true;
                        $i = 0;
                      }

                    ?>

                  <br>
                  <div class="row">
                    <div class="col s12 m12 l12">
                      
                        <div class="card-content">
                          Existen <?php echo ($bandera)? count($clientes) : 0; ?> registros. <br><br>
                          <table id={{ ($bandera)? "data-table-simple" : "" }} class="responsive-table display" cellspacing="0">
                               <thead>
                                  <tr>
                                     <th>#</th>
                                     <th>Nombre</th>
                                     <th>Usuario</th>
                                     <th>Email</th>
                                     <th>Fecha creación</th>
                                     <th>Estado</th>
                                     <th>Acción</th>
                                  </tr>
                               </thead>
                               <?php
                                    if($bandera){                                                           
                                ?>
                               <tfoot>
                                  <tr>
                                     <th>#</th>
                                     <th>Nombre</th>
                                     <th>Usuario</th>
                                     <th>Email</th>
                                     <th>Fecha creación</th>
                                     <th>Estado</th>
                                     <th>Acción</th>
                                  </tr>
                                </tfoot>
                                <?php 
                                  foreach ($clientes as $datos) {
                                    $i++;
                                ?>
                               <tbody>
                                <tr id="tr{{$datos->idcliente}}">                                  
                                     <td><?php echo $i; ?></td>
                                     <td><?php echo $datos->nombres.' '.$datos->apaterno.' '.$datos->amaterno ?></td>
                                     <td><?php echo $datos->usuario_cpanel ?></td>
                                     <td><?php echo $datos->correo ?></td>
                                     <td><?php echo $datos->fecha_creacion ?></td>
                                     <td style="text-align: center;">
                                        @if($datos->estado == 2)
                                        <div id="estado" class="badge grey darken-2 white-text text-accent-5" >
                                            <b>NO DISPONIBLE</b>
                                          <i class="material-icons"></i>
                                        </div>
                                      @else
                                        <div id="estado2" class="badge green lighten-5 green-text text-accent-4" >
                                          <b>ACTIVO</b>
                                          <i class="material-icons"></i>
                                        </div>
                                      @endif
                                     </td>
                                     <td class="center" style="width: 9rem">
                                       <a href="{{ url('/cliente/mostrar') }}/{{$datos->idcliente}}" class="tooltipped" data-position="top" data-delay="500" data-tooltip="Ver">
                                        <i class="material-icons" style="color: #7986cb ">visibility</i>
                                      </a>                                       
                                       <a href="#confirmacion{{$datos->idcliente}}" class="tooltipped modal-trigger" data-position="top" data-delay="500" data-tooltip="Eliminar">
                                        <i class="material-icons" style="color: #dd2c00">remove</i>
                                      </a>
                                      @if($datos->estado == 1)                                      
                                       <a href="#confirmacion2{{$datos->idcliente}}" class="tooltipped modal-trigger" data-position="top" data-delay="500" data-tooltip="Desabilitar">
                                        <i class="material-icons" style="color: #757575 ">clear</i></a>
                                       @else
                                       <a href="#confirmacion3{{$datos->idcliente}}" class="tooltipped modal-trigger" data-position="top" data-delay="500" data-tooltip="Habilitar">
                                        <i class="material-icons" style="color: #2e7d32 ">check</i></a>
                                       @endif
                                     </td>
                                  </tr>
                                    @include('forms.usuarios.scripts.alertaConfirmacion')
                                    @include('forms.usuarios.scripts.alertaConfirmacion2')
                                    @include('forms.usuarios.scripts.alertaConfirmacion3')
                                  <?php }} ?>
                               </tbody>
                            </table>
                          </div>
                    
                  </div>

                  </div>
                </div>
              </div>
</div>

@endsection
@include('forms.scripts.toast')

@section('script')

  {{-- @include('forms.usuarios.scripts.desabilitar')
  @include('forms.usuarios.scripts.habilitar') --}}

@endsection
