<?php
	Oraculum_Register::set('titulo', 'Home');
	Oraculum_Views::LoadElement('header');
 ?>
<form method="post" action="<?php echo URL; ?>">
    <fieldset>
        <legend>Login</legend>
        <label for="user">
            Usu&aacute;rio: 
        </label>
        <input type="text" name="user" id="user" />
        
        <label for="pass">
            Senha: 
        </label>
        <input type="password" name="pass" id="pass" />
        
        <input type="submit" name="send" id="send" value="Entrar" />
    </fieldset>
</form>
<?php
	Oraculum_Views::LoadElement('footer');
