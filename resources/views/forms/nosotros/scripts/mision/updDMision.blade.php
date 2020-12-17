<script type="text/javascript">
      //---JPaiva-31-07-2018----------------ACTUALIZAR-----------------------------
    $('#u_DMision').click(function(e){
      e.preventDefault();

      var val = $('#comodin').data('valor');
      var data = $('#u_myForm').serializeArray();
      //data.push({name: 'tienn2t', value: 'love'});
      //var formData = new FormData();
      //formData.append('url_imagen', $('#avatarInput')[0].files[0]);
      
      if(val == '0'){
        $.ajax({
            url: "{{ url('/dmision/actualizar') }}",
            type:"POST",
            beforeSend: function (xhr) {
                var token = $('meta[name="csrf-token"]').attr('content');

                if (token) {
                      return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
           type:'POST',
           url:"{{ url('/dmision/actualizar') }}",
           data:data,

           success:function(data){
              
              if ( data[0] == "error") {
                ( typeof data.imagen != "undefined" )? $('#u_error').text(data.imagen) : null;
                ( typeof data.titulo != "undefined" )? $('#u_error2').text(data.titulo) : null;
                ( typeof data.descripcion != "undefined" )? $('#u_error3').text(data.descripcion) : null;
              } else {  
                window.location="{{ url('/mision') }}";
              }
           },
           error:function(){ 
              alert("error!!!!");
        }
        });
      }

      if(val == '1'){
        var formData = new FormData();
        formData.append('url_imagen', $('#u_imagenURL')[0].files[0]);
        console.log(formData);
      //console.log(data);,

        $.ajax({
                beforeSend: function (xhr) {
                    var token = $('meta[name="csrf-token"]').attr('content');

                    if (token) {
                          return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
                url: "{{ url('dmision/iactualizar') }}" + '?' + $('#u_myForm').serialize(),
                method: "POST",               
                data: formData,
                processData: false,
                contentType: false
            }).done(function (data) {
              
                if (data.success)
                    //$avatarImage.attr('src', data.path);
                   window.location="{{ url('/mision') }}";
                    
            }).fail(function () {
                alert('La imagen subida no tiene un formato correcto');
            });
      }
      

    });   

    @foreach ($dmision as $val)
      $(document).on('click','#upd_DMision{{$val->id}}', function(){

        var imagen = $(this).data('imagen');
        var val = '#';
        var tipo = $(this).data('tipo');
        
        //console.log($(this).data('icon'));
        if(imagen.length > 0 && tipo === 1){
          val = "{{url('/images')}}"+"/"+ $(this).data('imagen'); 
        }
                

        $("#u_id").val($(this).data('id'));
        $("#u_imagen_scr").text($(this).data('icono'));
        $("#u_fila").val($(this).data('fila'));
        $("#u_imagen").val($(this).data('imagen'));
        $("#u_icono").val($(this).data('icono'));
        $("#u_img").attr('src',val);
        $("#u_titulo").val($(this).data('titulo'));
        $("#u_descripcion").val($(this).data('descripcion'));
        $("#u_idtipo").val($(this).data('idtipo'));

        if (tipo === 0) {
          
          $("#test3").attr('checked', true);
          $('#test4').removeAttr('checked');
          $('#u_img').hide();
          $('#bbb').hide();
          $('#aaa').show();
          
          $('#u_imagen_scr').show();
          
        }
        if (tipo === 1) {
          $('#u_img').show();
          $('#u_imagen_scr').hide();
          $('#aaa').hide();
          $('#bbb').show();
          $("#test4").attr('checked', true);
          $('#test3').removeAttr('checked');
        }

        if($(this).data('estado') === 0){
          $('#u_estado').hide();
          $('#u_estado2').show();
        }else{
          $('#u_estado2').hide();
          $('#u_estado').show();
        }

      });      
    @endforeach 

</script>