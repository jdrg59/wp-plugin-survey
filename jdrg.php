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

function Activar(){
        global $wpdb;

        //crear tabla si no existe
        $sql_tbencuestas="CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas(
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
            PRIMARY KEY (`RespuestaId`));
          ";
        $wpdb->query($sql3);
}

function Desactivar(){

}

register_activation_hook(__FILE__, 'Activar');
register_deactivation_hook(__FILE__, 'Desactivar');
/*register_uninstall_hook(__FILE__, 'Borrar');*/


/* CREACION DE MENU DEL PLUGIN*/
add_action( 'admin_menu', 'CrearMenu');

function CrearMenu(){
    add_menu_page( 
        'Super Encuestas',//page_title
        'Super Encuestas',//menu_title
        'manage_options',//capability roles
        /* 'sp-menu',//menu_slug */
        plugin_dir_path( __FILE__ ).'/lista_encuestas.php',//slug
        //'MostrarContenido',//function
        null, //function
        plugin_dir_url(__FILE__).'img/icon.png',//icon_url,
        '61'//position 
    );

    add_submenu_page( 
        plugin_dir_path( __FILE__ ).'/lista_encuestas.php',//parent_slug 
        'Ajustes',//page_title 
        'Ajustes',//menu_title
        'manage_options',//capability
        'sp-menu-ajustes',//menu_slug
        'Submenu'//function 
    );
}

function MostrarContenido(){
    echo "<h1> contenido de la pagina </h1>";
}
function Submenu(){
    echo "<h1> Página de ajustes</h1>";
}


//####### importar librerias - integrar bootstrap
function EncolarBoostraJS($hook){
    //evitar que sobre cargue la pagina en otros plugins
    //echo "<script>console.log('hola: $hook')</script>";
    if($hook != "wp-plugin-survey/lista_encuestas.php"){
        return;//evitamos que sobrecargue sitio
    }
    wp_enqueue_script( 'bootstrapJs', plugins_url( 'bootstrap/js/bootstrap.min.js', __FILE__ ),array('jquery'));
}
//mostrarlo en acciones
add_action( 'admin_enqueue_scripts','EncolarBoostraJS');


function EncolarBoostrapCSS($hook){
    if($hook != "wp-plugin-survey/lista_encuestas.php"){
        return;
    }
    wp_enqueue_style( 'bootstrapCSS', plugins_url( 'bootstrap/css/bootstrap.min.css', __FILE__ ));
}
add_action( 'admin_enqueue_scripts','EncolarBoostrapCSS');

function EncolarJS($hook){
    if($hook != "wp-plugin-survey/lista_encuestas.php"){
        return;
    }
    wp_enqueue_script( 'JsExterno', plugins_url( 'js/lista_encuestas.js', __FILE__ ),array('jquery'));
}
add_action( 'admin_enqueue_scripts','EncolarJS');