$("#email").change(function(){
    $(".alert").remove();

    var email = $(this).val();

    

    var datos = new FormData();
    datos.append("validarEmail", email);


    $.ajax({
        url: "ajax/formularios.ajax.php", 
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(respuesta){
            console.log("contenido de respuesta:", respuesta);
            if (respuesta){
                $("#email").val("");
                $("#email").parent().after(`
                    <div class="alert alert-warning">
                        <b>ERROR:</b>
                        ¡El correo que ingresaste ya existe, intenta con otro!
                    </div>
                `)
                
            }
        }
    });

})