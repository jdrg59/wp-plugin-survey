jQuery(document).ready(function($) {

    //Ver ajax funcionando y TOKEN
   // console.log(SolicitudesAjax);

    //console.log('Estoy aqui')
    $('#btnnuevo').click(function(){
        $('#modalnuevo').modal('show');
        //console.log('Clic nuevo')
    });



    // FUNCION PARA AGREGAR ITEMS A LA ENCUESTA
    var i = 1
    $("#add").click(function(){
        i++;
        $("#camposdinamicos").append('<tr id="row'+i+'"><td><label for="txtnombre" class="col-form-label">Pregunta '+i+'</label></td><td><input type="text" name="name[]" id="name" class="form-control name_list"></td><td><select name="type[]" id="type" class="form-control name_list mr-1"><option value="1">SI - NO</option><option value="2">Rango 0 - 5</option></select></td><td><button name="remove" id="'+i+'" class=" btn btn-danger btn_remove px-3">x</button></td></tr>')

        return false;
    });

    //FUNCION BORRAR - BUSCAR OPCION SELECCIONADA
    //escucha el evento click
    $(document).on('click', '.btn_remove', function(){
        var button_id = $(this).attr('id')
        console.log(button_id)
        $("#row" +button_id+"").remove();
        return false;

    })



    //FUNCION QUE EJECUTA EL ELIMINAR
    $(document).on('click', "a[data-id]", function(){
        var id= this.dataset.id
        console.log(id)

        //Guardar por medio de AJAX metodo wordpress
        var url = SolicitudesAjax.url;
        $.ajax({
                type: "POST",
                url: url,
                data: {
                    action: "peticioneliminar",
                    nonce: SolicitudesAjax.seguridad,
                    id: id,
                },
                success:function(){
                    alert('Datos Eliminados correctamente');
                    location.reload();
                }
        });
    })
});