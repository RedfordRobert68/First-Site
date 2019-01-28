<?php
   //This page is used to reset a forgotten password.
   // A new password is generated and sent to the registered email address.
   // Require the configuration before any PHP code as the configuration controls error reporting:
    require('./includes/config.inc.php');
    
    // Require the database connection:
    require(MYSQL);

    // Include the header file:
    $page_title = 'Forgot Your Password?';
    include('./includes/header.html');

    //Create an array for storing errors
    $pass_errors = array();

   // If it's a POST request, handle the form submission:
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

         //Validate the email address
        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

            //Check for the existence of that email address...
            $q = 'SELECT id FROM users WHERE email="'.escape_data($_POST['email'], $dbc) . '"';
            $r = mysqli_query($dbc, $q);
            if(mysqli_num_rows($r) === 1){
                list($uid) = mysqli_fetch_array($r, MYSQLI_NUM);
            }else{
                $pass_errors['email'] = 'The submitted email address does not match those on file';
            }
        }else{//Complete the filter_var() conditional;
            $pass_errors['email'] = 'Please enter a valid email address!';
        }// End of $_POST['email'] IF.

        //Generate a new password
        if(empty($pass_errors)){
            $p = substr(md5(uniqid(rand(), true)), 10, 15);
            $q = "UPDATE users SET pass='" . password_hash($p, PASSWORD_BCRYPT) . "' WHERE id=$uid LIMIT 1";
            if(mysqli_affected_rows($dbc) === 1){

                //Send the new password to the user
                $body = "Your password to log into boogalooga.com has been temporarily changed to '$p'. Please log in using that password and this email address. Then you may change your password to something more familiar.";
                mail($_POST['email'], 'Your temporary password.', $body, 'From: robert_vincent@metrographicsanddesign.com');
                
                //Print a message and wrap it up:
                echo
                    '<h1>
                        Your password has been changed.
                    </h1>
                    <p>
                        You will receive the new, temporary password via email. Once you have logged in with the new password, you may change it by clicking on the "Change Password" link.
                    </p>';
                include('./includes/footer.html');
                exit();
            
            //If the database couldn't run, generate an error:
            }else{
                trigger_error('Your password could not be changed due to a system error. We aplogize for any inconvenience');
            }
        }//End of $uid IF.
    }// End of the main submit conditional

    //Create the form
    require_once('./includes/form_functions.inc.php');
?>

<h1>
    Reset Your Password
</h1>
<p>
    Enter your email address below to reset your password.
</p>
<form action="forgot_password.php" method="post" accept-charset="utf-8">
    <?php
        create_form_input('email', 'email', 'Email Address', $pass_errors);
    ?>
    <input type="submit" name="submit_button" value="Reset &rarr;" id="submit_button" class="btn btn-default" />
</form>
<?php
    include('./includes/footer.html');
?>