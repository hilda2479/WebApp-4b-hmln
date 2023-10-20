<?php
require_once "../controladores/formularios.controlador.php";
require_once "../modelos/formularios.modelo.php";

/** AJAX */

class ajaxFormularios{

public $validarEmail;

public function ajaxValidarEmail(){
    $item= "email";
    $valor= $this->validarEmail;
    $respuesta = ControladorFormularios::ctrSeleccionarRegistros($item, $valor);
    echo json_encode($respuesta);
  }
} 


/** * Objetos de AJAX que recibe la variable POST */
if (isset($_POST["validarEmail"])){
  $valEmail = new AjaxFormularios();
  $valEmail -> validarEmail =  $_POST["validarEmail"];
  $valEmail -> ajaxValidarEmail();
}

?>