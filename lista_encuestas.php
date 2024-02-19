<?php
//variable para acceder bd de wordpress
global $wpdb;

//Manipulacion de datos
  $tabla = "{$wpdb->prefix}encuestas";
  $tabla2 = "{$wpdb->prefix}encuenstas_detalle";


//Formulario me llena el array de los post que hago
  if (isset($_POST['btnguardar'])) {
   // print_r($_POST); //test
   //echo "<script>console.log( 'resultado: ". $_POST."')</script>";

   //almacenamos nombre de la encuesta
    $nombre = $_POST['txtnombre'];
    //Query para obtener el ultimo id de la base
    $query = "SELECT encuestasId FROM $tabla ORDER BY encuestasId DESC limit 1";
    $resultado = $wpdb->get_results($query, ARRAY_A);
    $proximoId = $resultado[0]['encuestasId'] + 1;
    //creando shortcode
    $shortcode = "[ENC_" .$proximoId ."]";

    //Array que resive el insert
    $datos = [
      'encuestasId' => null,
      'nombre'=>$nombre,
      'shortcode'=>$shortcode
    ];
    //INSERTAR EN WORDPRESS RESERVADO
    $respuesta = $wpdb->insert($tabla, $datos);

    //INGRESO DE DETALLES DE LA ENCUESTA
    //Si respuesta es true or false
    if($resultado){
      $listapreguntas = $_POST['name'];
      $i = 0;
      foreach($listapreguntas as $key => $value){
        $tipo = $_POST['type'][$i];

        $datos2 = [
          'DetalleId'=> null,
          'EncuestaId'=> $proximoId,
          'Pregunta'=> $value,
          'Tipo'=> $tipo
        ];

        $wpdb->insert($tabla2, $datos2);
        $i++;
      }
    }

   
    print_r($resultado);

  }


  $query = "SELECT * FROM {$wpdb->prefix}encuestas";
  //la respuesta y si no trae nada nos da un obj (lo hacemos array asociativo)
  $lista_encuestas = $wpdb->get_results($query, ARRAY_A);
  //print_r($lista_encuestas);

  //posible error tabla vacia
  if (empty($lista_encuestas)) {
    $lista_encuestas = array();
  }
?>

<div class="wrap">
  <?php echo '<h1 class="wp-heading-inline">' . get_admin_page_title() . '</h1>'; ?>
  <a id="btnnuevo" class="page-title-action">Añadir nueva</a>
  <br><br>
  <table class="wp-list-table widefat fixed striped table-view-list pages">
    <thead>
      <th>Nombre de la encuestas</th>
      <th>ShortCode</th>
      <th>Acciones</th>
    </thead>
    <tbody id="the-list">
      <?php
      foreach ($lista_encuestas as $key => $value) {
        //Obtener id elemento
        $id = $value['encuestasId'];

        $nombre = $value['nombre'];
        $shortcode = $value['shortcode'];
        echo "
              <tr>
                  <td>$nombre </td>
                  <td>$shortcode</td>
                  <td> 
                  <a class='page-title-action btn btn-primary'>Ver estadisticas</a>
                  <a  data-id='$id' class='page-title-action btn btn-danger'>Borrar</a>
                  </td>
              </tr>
              ";
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Modal -->
<!-- Modal -->
<div class="modal fade w-100" id="modalnuevo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 50vw;">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Nueva Encuesta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- APERTURA FORMULARIO -->
      <form method="post">
        <div class="modal-body">
          <div class="form-group row">
            <label for="txtnombre" class="col-sm-4 col-form-label">Nombre de la encuesta</label>
            <div class="col-sm-8 d-flex align-content-center flex-wrap">
              <input type="text" id="txtnombre" name="txtnombre" class="w-100">
            </div>
          </div>
          <hr>
          <h4>Preguntas</h4>
          <table id="camposdinamicos" class="table">
            <tr>
              <td>
                <label for="txtnombre" class="col-form-label">Pregunta 1</label>
              </td>
              <td>
                <input type="text" name="name[]" id="name" class="form-control name_list">
              </td>
              <td>
                <select name="type[]" id="type" class="form-control name_list mr-1">
                  <option value="1">SI - NO</option>
                  <option value="2">Rango 0 - 5</option>
                </select>
              </td>
              <td>
                <button name="add" id="add" class="btn btn-success px-3">+</button>
              </td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" name="btnguardar" id="btnguardar">Guardar</button>
        </div>
      </form>
      <!-- CIERRE FORMULARIO -->
    </div>
  </div>
</div>