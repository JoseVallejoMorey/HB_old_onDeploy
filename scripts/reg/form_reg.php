<?php 

//definimos variables vacias
    $tel    = '';
    $email  = '';
    $nombre = '';
//rellenamos variables si hay post
if(!empty($_POST)){ 
    if(!empty($_POST['email'])) {   $email  = $_POST['email'];  }
    if(!empty($_POST['nombre'])) {  $nombre = $_POST['nombre']; }
    if(!empty($_POST['tel'])) {     $tel    = $_POST['tel'];    }
}


?>


<div class="form-group">
    <div class="input-group">
        <input placeholder="Nombre " class="form-control" type="text" name="nombre" 
               value="<?php echo $nombre; ?>" req="required"/>
        <span class="input-group-addon"><i class="fa fa-user"></i></span>
    </div>
</div>
<div class="form-group">
    <div class="input-group">
        <input placeholder="Email " class="form-control" type="text" name="email" 
               value="<?php echo $email; ?>"/>
        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
    </div>
</div>
<div class="form-group">
    <div class="input-group">
        <input placeholder="Telefono " class="form-control" type="text" name="telefono" 
               value="<?php echo $tel; ?>"/>
        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
    </div>
</div>




<div class="form-group">
        <label class="col-md-3 control-label" for="select">Tipo de usuario </label>
        <div class="col-md-9">        
            <select name="tipo_usuario" req="required" class="form-control" size="1">
                <option></option>
                <option>empresa</option>
                <option>particular</option>
            </select>
        </div>
</div>

<!-- respuesta de ajax -->
<div id="res-response"></div>

<div class="form-group">
    <div class="input-group">
        <input type="password" class="form-control" name="password" placeholder="Password" />
        <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
    </div>
</div>
<div class="form-group">
    <div class="input-group">
        <input type="password" class="form-control" name="password2" placeholder="Repita password" />
        <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
    </div>
</div>
