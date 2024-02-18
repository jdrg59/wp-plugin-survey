<?php 
    //variable para acceder bd de wordpress
    global $wpdb;
    $query = "SELECT * FROM {$wpdb->prefix}encuestas";
    //la respuesta y si no trae nada nos da un obj (lo hacemos array asociativo)
    $lista_encuestas = $wpdb->get_results($query, ARRAY_A);

    //posible error tabla vacia
    if(empty($lista_encuestas)){
        $lista_encuestas = array();
    }
?>

<div class="wrap">
    <?php echo '<h1 class="wp-heading-inline">' . get_admin_page_title() . '</h1>'; ?>
    <a id="btnnuevo" class="page-title-action">Añadir nueva</a>
    <br><br>
    <table class="wp-list-table widefat fixed striped table-view-list pages">
                <thead>
                    <th >Nombre de la encuestas</th>
                    <th >ShortCode</th>
                    <th >Acciones</th>
                </thead>
                <tbody id="the-list">
                    <?php
                            foreach ($lista_encuestas as $key => $value) {
                                $nombre = $value['nombre'];
                                $shortcode = $value['shortcode'];
                                echo <<< EOT
                                <tr>
                                    <td>$nombre </td>
                                    <td>$shortcode</td>
                                    <td> <a class='page-title-action'>Borrar</a></td>
                                </tr>
                                EOT;
                        }
                    ?>
                </tbody>
        </table>
</div>

<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="modalnuevo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Nueva Encuesta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>