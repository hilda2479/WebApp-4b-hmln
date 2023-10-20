<?php 

class ControladorFormularios{
    /** Registrios guardados */

    static public function ctrRegistro(){
        if(isset($_POST["registroNombre"])){
            if (preg_match('/^[a-zA-Z ]+$/', $_POST["registroNombre"]) && 
                preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})+$/', $_POST["registroEmail"]) && 
                preg_match('/^[0-9a-zA-Z]+$/', $_POST["registroPassword"])){

                $tabla = "registros";

                $token = md5($_POST["registroNombre"] . "+" . $_POST["registroEmail"]);

                $encriptarPassword = crypt($_POST["registroPassword"], '$2a$07$wwwinnovara3dcomwebApp$');

                $datos = array("token" => $token,
                    "nombre" => $_POST["registroNombre"],
                    "email" => $_POST["registroEmail"],
                    "password" => $encriptarPassword
                );


                $respuesta = ModeloFormularios::mdlRegistro($tabla, $datos);
                return $respuesta;
            } else{
                $respuesta = "error";
                return $respuesta;
            }

        }
    }

    /** * seleccionar registros de la tabla */
    static public function ctrSeleccionarRegistros($item, $valor){
        $tabla = "registros";

        $respuesta = ModeloFormularios::mdlSeleccionarRegistros($tabla, $item, $valor);

        return $respuesta;
    }
    /** * Ingresar */
    public function ctrIngreso(){
        if (isset($_POST["ingresoEmail"])){
            $tabla = "registros";
            $item = "email";
            $valor = $_POST["ingresoEmail"];

            $respuesta = ModeloFormularios::mdlSeleccionarregistros($tabla, $item, $valor);

            $encriptarPassword = crypt($_POST["ingresoPassword"], '$2a$07$wwwinnovara3dcomwebApp$');

            if (is_array($respuesta)){//$respuesta != null
                if ($respuesta["email"] == $_POST["ingresoEmail"] && $respuesta["password"] == $encriptarPassword){
                    ModeloFormularios::mdlActualizarIntentosFallidos($tabla, 0, $respuesta["token"]);

                    $_SESSION["validarIngreso"] = "ok";

                    echo '<script>
                    if (window.history.replaceState){
                        window.history.replaceState(null, null, window.location.href);
                    }
                    window.location = "index.php?pagina=inicio";
                </script>';
                } else {
                    if ($respuesta["intentos_fallidos"] < 3) {
                        $tabla = "registros";
                        $intentos_fallidos = $respuesta["intentos_fallidos"] + 1;

                        $actualizarIntentosFallidos = ModeloFormularios::mdlActualizarIntentosFallidos($tabla, $intentos_fallidos, $respuesta["token"]);

                    }else {
                        echo '<div class="alert alert-warning">RECAPTCHA! Debes de validar que no eres un robot</div>';
                    }

                    echo '<script>
                        if (window.history.replaceState){
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>';
                    echo '<div class="alert alert-danger">!Erro! al ingrasar al sistema, el EMAIL o la CONTRASEÑA no coinciden</div>';
                }
            } 
            else {
                echo '<script>
                    if (window.history.replaceState){
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>';
                echo '<div class="alert alert-danger">!Erro! al ingrasar al sistema, el EMAIL o la CONTRASEÑA no coinciden</div>';
            }
        }
    }
    /** * Actualizar registros de la tabla */
    static public function ctrActualizarRegistro(){
        if (isset($_POST["actualizarNombre"])){

            if (preg_match('/^[a-zA-Z ]+$/', $_POST["actualizarNombre"]) && 
                preg_match('/^[_a-z0-9- ]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})+$/', $_POST["actualizarEmail"])){
                $usuario = ModeloFormularios::mdlSeleccionarregistros("registros", "token", $_POST["tokenUsuario"]);

                $compararToken = md5($usuario["nombre"] . "+" . $usuario["email"]);

                if ($compararToken ==  $_POST["tokenUsuario"]) {
                    
                    if ($_POST["actualizarPassword"] != "") {
                        if (preg_match('/^[0-9a-zA-Z]+$/', $_POST["actualizarPassword"])){
                            $password = crypt($_POST["actualizarPassword"], '$2a$07$wwwinnovara3dcomwebApp$');
                        }
                    } else {
                        $password = $_POST["passwordActual"];
                    }
                    
/** Actualizar */
                    if($_POST["nombreActual"] !=$_POST["actualizarNombre"] || $_POST["emailActual"] !=$_POST["actualizarEmail"]){
                        $nuevoToken = md5($_POST["actualizarNombre"] ."+". $_POST["actualizarEmail"]);
                    } else {
                        $nuevoToken = null;
                    }

                    $tabla = "registros";
                    $datos = array(
                        "token" => $_POST["tokenUsuario"],
                        "nuevoToken" => $nuevoToken,// vine un dato si se cambio el nombre o el email, de lo contrario viene vacio
                        "nombre" => $_POST["actualizarNombre"],
                        "email" => $_POST["actualizarEmail"],
                        "password" => $password
                    );
                    $respuesta = ModeloFormularios::mdlActualizarRegistros($tabla, $datos);

                    return $respuesta;
                } else {
                    $respuesta = "error";
                    return $respuesta;
                }
            } else {
                $respuesta = "error";

                return $respuesta;
            }
        }
    }
    /** * Eliminar los registros */
    public function ctrEliminarRegistro(){
        if (isset($_POST["eliminarRegistro"])){

            $usuario = ModeloFormularios::mdlSeleccionarregistros("registros", "token", $_POST["eliminarRegistro"]);

            $compararToken = md5($usuario["nombre"]."+". $usuario["email"]);
             
            if ($compararToken ==  $_POST["eliminarRegistro"]) {
                $tabla = "registros";
                $valor =  $_POST["eliminarRegistro"];

                $respuesta = ModeloFormularios::mdlEliminarRegistros($tabla, $valor);

                if ($respuesta == "ok") {
                    echo '<script>
                    if (window.history.replaceState ){
                        window.history.replaceState(null, null, window.location.href);
                    }
                    window.location = "index.php?pagina=inicio";
                </script>';
                }
            }

        }
    }

}

?>