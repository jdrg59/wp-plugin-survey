<?php
/*
Plugin Name: JDRG Plugin
Plugin URI: https://www.mesodi.com/
Description: Este es mi primer plugin de pruebas.
Author: Rivas, Inc.
Author URI: https://www.mesodi.com/
Version: 1.0.0
Text Domain: mesodi
Domain Path: /languages
*/

/*requerimientos */
require_once dirname(__FILE__). '/clases/codigocorto.class.php';


function Activar()
{
    global $wpdb;

    //crear tabla si no existe
    $sql_tbencuestas = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas(
            `encuestasId` int not null auto_increment,
            `nombre` varchar(45) null,
            `shortcode` varchar(45) null,
            primary key (`encuestasId`)
        );";
    $wpdb->query($sql_tbencuestas);

    $sql2 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuenstas_detalle(
            `DetalleId` INT NOT NULL AUTO_INCREMENT,
            `EncuestaId` INT NULL,
            `Pregunta` VARCHAR(150) NULL,
            `Tipo` VARCHAR(45) NULL,
            PRIMARY KEY (`DetalleId`));";
    $wpdb->query($sql2);

    $sql3 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas_respuesta (
            `RespuestaId` INT NOT NULL AUTO_INCREMENT,
            `DetalleId` INT NULL,
            `Codigo` VARCHAR(45) NULL,
            `Respuesta` VARCHAR(45) NULL,
            `Codigo`VARCHAR(45) NULL,     
            PRIMARY KEY (`RespuestaId`));
          ";
    $wpdb->query($sql3);
}

function Desactivar()
{
}

register_activation_hook(__FILE__, 'Activar');
register_deactivation_hook(__FILE__, 'Desactivar');
/*register_uninstall_hook(__FILE__, 'Borrar');*/


/* CREACION DE MENU DEL PLUGIN*/
add_action('admin_menu', 'CrearMenu');

function CrearMenu()
{
    add_menu_page(
        'Super Encuestas', //page_title
        'Super Encuestas', //menu_title
        'manage_options', //capability roles
        /* 'sp-menu',//menu_slug */
        plugin_dir_path(__FILE__) . '/lista_encuestas.php', //slug
        //'MostrarContenido',//function
        null, //function
        plugin_dir_url(__FILE__) . 'img/icon.png', //icon_url,
        '61' //position 
    );

    add_submenu_page(
        plugin_dir_path(__FILE__) . '/lista_encuestas.php', //parent_slug 
        'Ajustes', //page_title 
        'Ajustes', //menu_title
        'manage_options', //capability
        'sp-menu-ajustes', //menu_slug
        'Submenu' //function 
    );
}

function MostrarContenido()
{
    echo "<h1> contenido de la pagina </h1>";
}
function Submenu()
{
    echo "<h1> Página de ajustes</h1>";
}


//####### importar librerias - integrar bootstrap
function EncolarBoostraJS($hook)
{
    //evitar que sobre cargue la pagina en otros plugins
    //echo "<script>console.log('hola: $hook')</script>";
    if ($hook != "wp-plugin-survey/lista_encuestas.php") {
        return; //evitamos que sobrecargue sitio
    }
    wp_enqueue_script('bootstrapJs', plugins_url('bootstrap/js/bootstrap.min.js', __FILE__), array('jquery'));
}
//mostrarlo en acciones
add_action('admin_enqueue_scripts', 'EncolarBoostraJS');


function EncolarBoostrapCSS($hook)
{
    if ($hook != "wp-plugin-survey/lista_encuestas.php") {
        return;
    }
    wp_enqueue_style('bootstrapCSS', plugins_url('bootstrap/css/bootstrap.min.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'EncolarBoostrapCSS');


//Importar JS Propio

function EncolarJS($hook)
{
    if ($hook != "wp-plugin-survey/lista_encuestas.php") {
        return;
    }
    wp_enqueue_script('JsExterno', plugins_url('js/lista_encuestas.js', __FILE__), array('jquery'));

    //seccion para borrar ajax
    wp_localize_script('JsExterno', 'SolicitudesAjax', [
        'url' => admin_url('admin-ajax.php'),
        'seguridad' => wp_create_nonce('seg')
    ]);
}
add_action('admin_enqueue_scripts', 'EncolarJS');

//Ajax Eliminar
function EliminarEncuestas(){
    $nonce = $_POST['nonce'];   //lista_encuestas linea 48

    if (!wp_verify_nonce($nonce, 'seg')) {
        die('no tienen permisos suficientes para ejecutar este ajax');
    }
    $id = $_POST['id'];
    global $wpdb;
    //Manipulacion de datos
    $tabla = "{$wpdb->prefix}encuestas";
    $tabla2 = "{$wpdb->prefix}encuenstas_detalle";
    //Borrar en wordpress no pide base y un where
    $wpdb->delete($tabla, array('encuestasId' => $id));
    $wpdb->delete($tabla2, array('EncuestaId' => $id));
    return true;
}

//ejecutar eliminado
add_action( 'wp_ajax_peticioneliminar', 'EliminarEncuestas' );


//Shortcode enviar id a funcion en clases creadas

function imprimirshortcode($atts){
    $_short = new codigocorto; //clase creada en clases/codigocorto.class.php
    $id= $atts['id']; //capturo los parametros de un shortcode ya creado

    //Pruebas de boton guardar
    if(isset($_POST['btnguardar'])){
       // var_dump($_POST); //Ver cuando el user le da clic al btn guardar

       //recorrer formulario en clase ya antes programada
       $listadePreguntas = $_short->ObtenerEncuestaDetalle($id);
       $codigo = uniqid(); //Genera codigos aleatorios

       //Recorremos el array
       foreach($listadePreguntas as $key => $value ){
        $idpregunta = $value['DetalleId'];
            if(isset($_POST[$idpregunta])){
                $valortxt = $_POST[$idpregunta];
                //Campos de la tabla encuestas_respuesta
                $datos = [
                    'DetalleId' => $idpregunta,
                    'Codigo' => $codigo,
                    'Respuesta' => $valortxt
                ];
                $_short->GuardarDetalle($datos); //se creara en codigocorto.class.php
            }
       }
       return "Encuesta enviada exitosamente";
    }


    $html = $_short->Armador($id);
    return $html;
    //var_dump($atts);
}
add_shortcode( "ENC", "imprimirshortcode" );
