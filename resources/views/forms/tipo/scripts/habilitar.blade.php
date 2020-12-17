<script type="text/javascript">
	 //----JPaiva-07-08-2018------------------HABILITAR---------------------------
    @foreach ($tipo as $val)
        $('#ha{{$val->idtipo}}').click(function(e){
          e.preventDefault();

          id = $(this).data('id');
          console.log(id);

          $.ajax({
                url: "{{ url('/tipo/habilitar') }}",
                type:"POST",
                beforeSend: function (xhr) {
                    var token = $('meta[name="csrf-token"]').attr('content');

                    if (token) {
                          return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
               type:'POST',
               url:"{{ url('/tipo/habilitar') }}",
               data:{
                  id:id
               },

               success: function(data){

              if ( data[0] == "error") {
                ( typeof data.descripcion != "undefined" )? $('#u_error2').text(data.descripcion) : null;
              } else {  
                var obj = $.parseJSON(data); 

                $('#tr'+obj[0]['idtipo']).replaceWith(
                "<td>"+ obj[0]['idgrupo'] +"</td>"+
                "<td>"+ obj[0]['idgrupo'] +"</td>"+
                "<td>"+ obj[0]['idtipo'] +"</td>"+
                "<td>"+ obj[0]['descripcion'] +"</td>"+
                "<td class='center'>"+
                    "<div id='estado2' class='badge green lighten-5 green-text text-accent-4' style='width: 70%'>"+
                      "<b>ACTIVO</b>"+
                      "<i class='material-icons'></i>"+
                    "</div>"+
                "</td>"+
                "<td class='center' style='width: 9rem'>"+
                  "<a href='{{ url('/tipo/mostrar') }}/"+obj[0]['idtipo']+"' class='btn-floating waves-effect waves-light grey lighten-5 tooltipped' data-position='top' data-delay='500' data-tooltip='Ver'><i class='material-icons' style='color: #7986cb'>visibility</i></a>"+                                     
                  " <a href='#confirmacion"+ obj[0]['idtipo'] +"' class='btn-floating waves-effect waves-light grey lighten-5 tooltipped modal-trigger' data-position='top' data-delay='500' data-tooltip='Eliminar'><i class='material-icons' style='color: #dd2c00'>remove</i></a>"+

                  " <a href='#confirmacion2"+ obj[0]['idtipo'] +"' class='btn-floating waves-effect waves-light grey lighten-5 tooltipped modal-trigger' data-position='top' data-delay='500' data-tooltip='Desabilitar'><i class='material-icons' style='color: #757575'>clear</i></a>"+
                "</td>"
                );}
                
                
                setTimeout(function() {
                  Materialize.toast('<span>Registro habilitado</span>', 1500);
                }, 100);  

               },
               error:function(){ 
                  alert("error!!!!");
            }
            });
        });    
          
    @endforeach


</script>