<?php if((!empty($_POST)) && (!empty($_POST['email']))) {
	
	$email = $_POST['email'];
} else {
	$email = '';
	}

//scripts/reg/form_log2

?>



<div class="panel panel-primary col-lg-4 col-lg-offset-4">
	<div class="panel-heading">Acceso</div>
	<div class="panel-body">
    
        <form id="form_log2" name="form_login2" method="post" class="form form-horizontal" >
            <input type="hidden" name="act" value="log2"/>
            <input type="hidden" name="ip_enc" value="<?php echo md5($_SERVER['REMOTE_ADDR']); ?>"/>
            <input type="hidden" name="aleatorio" value="<?php echo $_SESSION['invisible']['token_key']; ?>"/>
            <label>Email </label>
            <input class="" type="text" name="email" value="<?php echo $email; ?>"/>
            <label>Password </label>
            <input class="" type="password" name="password"/><br /><br />
                                    
            <input type="submit" value="entrar"/>
                                    
        </form>    

    
	</div>
</div>
    
<?php
	if(!empty($error)){
		echo'<div id="" class="col-lg-4 col-lg-offset-4 alert alert-error">'.$error.'</div>';
	}
?>