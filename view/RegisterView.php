<?php

class RegisterView {

    private static $message = 'RegisterView::Message';
    private static $username = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $repeatPassword = 'RegisterView::RepeatPassword';
    private static $register = 'DoRegistration';



    public function generateRegisterForm() {

        return "
		<h2>Register new user</h2>
			<form action='?register' method='post' enctype='multipart/form-data'>
				<fieldset>
				<legend>Register a new user - Write username and password</legend>
					<p id='" . self::$message . "'></p>
					<label for='" . self::$username . "' >Username :</label>
					<input type='text' size='20' name='" . self::$username . "' id='" . self::$username . "' value='' />
					<br/>
					<label for='" . self::$password . "' >Password  :</label>
					<input type='password' size='20' name='" . self::$password . "' id='" . self::$password . "' value='' />
					<br/>
					<label for='" . self::$repeatPassword . "' >Repeat password  :</label>
					<input type='password' size='20' name='" . self::$repeatPassword . "' id='" . self::$repeatPassword . "' value='' />
					<br/>
					<input id='submit' type='submit' name='" . self::$register . "'  value='Register' />
					<br/>
				</fieldset>
			</form>


        ";

    }

}