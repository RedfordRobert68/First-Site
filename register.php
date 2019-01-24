<?php
	//b
	//This is the registration page for the site.
	//This file both displays and processes the registration form.
	//This script begun in Chapter 4

	//Require the configuration before any PHP code as the configuration controls error reporting:
	require('./includes/config.inc.php');
	//The config file also starts the session.
	
	//Require the database connection:
	require(MYSQL);

	//Include the header file:
	$page_title = 'Register';
	include('./includes/header.html');
	
	//For storing registration errors
	$reg_errors = array();

	//Check for form submission
	if($_SERVER['REQUEST_METHOD'] === 'POST'){

		//Check for the first name:
		if(preg_match ('/^[A-Z \'.-]{2,45}$/i', $_POST['first_name'])){
			$fn = escape_data($_POST['first_name'], $dbc);
		}else{
			$reg_errors['first_name'] = 'Please enter your first name!';
		}

		//Check for the last name:
		if(preg_match ('/^[A-Z \'.-]{2,45}$/i', $_POST['last_name'])){
			$ln = escape_data($_POST['last_name'], $dbc);
		}else{
			$reg_errors['last_name'] = 'Please enter your last name!';
		}

		//Check for the username:
		if(preg_match ('/^[A-Z0-9]{2,45}$/i', $_POST['username'])){
			$u = escape_data($_POST['username'], $dbc);
		}else{
			$reg_errors['username'] = 'Please enter a desired name using only letters and numbers!';
		}

		//Check for an email address
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === $_POST['email']){
			$e = escape_data($_POST['email'], $dbc);
		}else{
			$reg_errors['email'] = 'Please enter a valid email address!';
		}

		//Check for password and match against the confirmed password
		if(preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1'])){
			if($_POST['pass1'] === $_POST['pass2']){
				$p = $_POST['pass1'];
			}else{
				$reg_errors['pass2'] = 'Your password did not match the confirmed password!';
			}
		}else{
			$reg_errors['pass1'] = 'Please enter a valid password';
		}

		if(empty($reg_errors)){// If everything's OK...

			//Check availability of email address and username
			$q = "SELECT email, username FROM users WHERE email='$e' OR username='$u'";
			$r = mysqli_query($dbc, $q);
			
			// Get the number of rows returned
			$rows = mysqli_num_rows($r);
			
			if($rows === 0){// No problems!

				// Add the user to the database...
				
				// Temporary: set expiration to a month!
				// Change after adding PayPal!
				$q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires) VALUES ('$u', '$e', '" . password_hash($p, PASSWORD_BCRYPT) . "', '$fn', '$ln', ADDDATE(NOW(), INTERVAL 1 MONTH))";
				
				$r = mysqli_query($dbc, $q);

				//Thank the new customer and send out an email:
				if(mysqli_affected_rows($dbc) === 1){
					echo '
					<div class="alert alert-success">
						<h3>
							Thanks!
						</h3>
						<p>
							Thank you for registering! You may now log in and access the site\'s content.
						</p>
					</div>';

					// Send a separate email?
					$body = "Thank you for registering at boogalooga.com. Blah Blah Blah. \n\n";
					mail($_POST['email'], 'Registration Confirmation', $body, 'From: admin@boogalooga.com');

					//Finish the page
					include('./includes/footer.html');
					exit(); // Stop the page
				
				}else{ //If it did not run OK
					trigger_error('You could not be registered due to a system error. we apologize for any inconvenience. We will correct the error ASAP.');
				}

			}else{// The email address or username is not available
				
				if($rows === 2){// Both are taken

					$reg_errors['email'] = 'This email address has already been registered. If you have forgotten your password, use the link at the left to have your password sent to you.';
					$reg_errors['username'] = 'This username has already been registered. Please try another';
				}else{ //One or both may be taken.

					//Confirm which item has been registered
					$row = mysqli_fetch_array($r, MYSQLI_NUM);

					if( ($row[0] === $_POST['email']) && ($row[1] === $_POST['username'])){ //Both match
						
						$reg_errors['email'] = 'This email address has already been registered. If you have forgotten your password, use the link at the left to have your password sent to you.';

						$reg_errors['username'] = 'This username has already been registered with this email address. If you have forgotten your password, use the link at the left to have your password sent to you.';

					}elseif($row[0] === $_POST['email']){ //Email match

						$reg_errors['email'] = 'This email address has already been registered. If you have forgotten your password, use the link at the left to have your password sent to you.';

					}elseif($row[1] === $_POST['username']){ //Username match

						$reg_errors['username'] = 'This username has already been registered. Please try another.';
					}
				}//End of $rows == 2 ELSE
			}//End of $rows === 0 IF
		}//End of empty($reg_errors) IF
	}//End of main form submission conditional

	//Need the form functions script, which defines the create_form_input():
	//The file may already have been included by the header
	require_once('./includes/form_functions.inc.php');
?>

<h1>
	Register
</h1>
<p>
	Access to the site's content is available to registered users at a cost of $10.00 (US) per year. Use the form below to begin the registration process. <strong>Note: All fields are required.</strong> After completing this form, you'll be presented with the opportunity to securely pay for your yearly subscription via <a href="http://www.paypal.com">Paypal.</a> 
</p>
<form action="register.php" method="post" accept-charset="utf-8">
	<?php
		create_form_input('first_name', 'text', 'First Name', $reg_errors);
		create_form_input('last_name', 'text', 'Last Name', $reg_errors);
		create_form_input('username', 'text', 'Desired Username', $reg_errors);
		echo '<span class="help-block">Only letters and numbers are allowed</span>';
		create_form_input('email', 'email', 'Email Address', $reg_errors);
		create_form_input('pass1', 'password', 'Password', $reg_errors);
		echo '<span>Must be at least 6 characters long, with at least one lowercase letter, one uppercase letter, and one number</span>';
		create_form_input('pass2', 'password', 'Confirm Password', $reg_errors); 
	?>
	<input type="submit" name="submit_button" value="Next &rarr;" id="submit_button" class="btn btn-default" />
</form


<?php
	include('./includes/footer.html');
?>