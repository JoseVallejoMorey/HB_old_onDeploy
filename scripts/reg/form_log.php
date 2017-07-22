<?php 
if((!empty($_POST)) && (!empty($_POST['email']))) {
	$email = $_POST['email'];
} else {
	$email = '';
	}
?>

<div class="form-group">
    <div class="input-group">
        <input class="form-control" placeholder="Email" type="text" name="email" value="<?php echo $email; ?>" />
        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
    </div>
</div>
<div class="form-group">
    <div class="input-group">
        <input name="password" class="form-control" placeholder="Password" type="password">
        <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
    </div>
</div>
