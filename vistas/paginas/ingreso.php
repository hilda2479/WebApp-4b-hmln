<div class="d-flex justify-content-center text-center">
    
    <form class="p-5 bg-light" method="post">
        
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                </div>
                <input type="email" class="form-control" placeholder="Enter email" id="email" name="ingresoEmail">
            </div>
        </div>
        <div class="form-group">
            <label for="pwd">Contraseña</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                </div>
                <input type="password" class="form-control" placeholder="Enter password" id="pwd" name="ingresoPassword">
            </div>
        </div>
        <?php
            $ingreso = new ControladorFormularios();
            $ingreso -> ctrIngreso();
        ?>
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</div>