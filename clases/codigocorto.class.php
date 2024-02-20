<?php

class codigocorto{
    public function ObtenerEncuesta($encuestaid){
        global $wpdb;
        $tabla = "{$wpdb->prefix}encuestas";
        $query = "SELECT * FROM $tabla WHERE encuestasId = '$encuestaid'";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            return array();
        }
        return $datos[0];
    }

    public function ObtenerEncuestaDetalle($encuestaid){
        global $wpdb;
        $tabla2 = "{$wpdb->prefix}encuenstas_detalle";
        $query = "SELECT * FROM $tabla2 WHERE EncuestaId = '$encuestaid'";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }

    public function formOpen($titulo){
        $html = "
            <div class='wrap'>
                <h4> $titulo</h4>
                <br>
                <form method='POST'>

        ";

        return $html;
    }

    public function formClose(){
        $html = "
              <br>
                 <input type='submit' id='btnguardar' name='btnguardar' class='page-title-action' value='enviar'>
            </form>
          </div>  
        ";

        return $html;
    }

    function fromInput($detalleid,$pregunta,$tipo){
        $html="";
        if($tipo == 1){
            $html="
                <diV class='from-group'>
                    <p><b>$pregunta</b></p>
                  <div class='col-sm-8'>  
                        <select class='form-select form-select-lg mb-3' id='$detalleid' name='$detalleid'>
                                <option value='SI'>SI</option>
                                <option value='No'>NO</option>
                        </select>
                  </div>
            
            ";
        }elseif ($tipo == 2) {
            //pendiente
            $html = "<div class='form-group'><p><b>$pregunta</b></p>... </div>";
        }else{
            //pendiente
            $html = "<div class='form-group'><p><b>$pregunta</b></p>... </div>";
        }
        return $html;
    }

    function Armador($encuestaid){
        $enc = $this->ObtenerEncuesta($encuestaid);
        //$nombre = $enc['nombre'];
        $nombre = isset($enc['nombre']) ? $enc['nombre'] : 'Nombre no disponible';
        //print_r($nombre);
        //obtener todas las preguntas
        $preguntas = "";
        $listapregutas = $this->ObtenerEncuestaDetalle($encuestaid);
        foreach ($listapregutas as $key => $value) {
            $detalleid = $value['DetalleId'];
            $pregunta = $value['Pregunta'];
            $tipo =$value['Tipo'];
            $encid = $value['EncuestaId'];

            if($encid == $encuestaid){
                $preguntas .= $this->fromInput($detalleid,$pregunta,$tipo);// al poner un (.) antes del (=) se guardan todas las preguntas
            }
        }

        $html = $this->formOpen($nombre);
        $html .= $preguntas;
        $html .= $this->formClose();

        return $html;

    }

    function GuardarDetalle($datos){
        global $wpdb;
        $tabla = "{$wpdb->prefix}encuestas_respuesta"; 
        return  $wpdb->insert($tabla,$datos);
    }

}

?>