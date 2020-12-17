 <script type="text/javascript">
    //---JPaiva-06-08-2018----------------GRABAR-----------------------------
    $(function () {
        var $Input, $myForm;
        
        $Input = $('#inputLinea');
        $myForm = $('#myForm');

     

        //$avatarInput.on('change', function () {
        $('#importLinea').on('click', function () {
            //alert('change');
            
            var formData = new FormData();
            formData.append('lineaXLS', $Input[0].files[0]);

            $.ajax({
                beforeSend: function (xhr) {
                    var token = $('meta[name="csrf-token"]').attr('content');

                    if (token) {
                          return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
                url: "{{ url('herramientas/importarLinea') }}" + '?' + $myForm.serialize(),
                method: 'POST',               
                data: formData,
                processData: false,
                contentType: false,

                 success: function(data){
                  
                setTimeout(function() {
                  Materialize.toast('<span>Importación de Lineas exitoso</span>', 2000);
                }, 200);  
              },
                 error:function(){ 
                    alert("error!!!!");
              }

            })
        });
    });

   
  </script>
    