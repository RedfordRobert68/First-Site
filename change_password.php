<?php
    
    //This page is used to change password.
    // Users must be logged in to access this page.

    // Require the configuration before any PHP code as the configuration controls error reporting:
    require('./includes/config.inc.php');

    //Only logged in users can access the page
    redirect_invalid_user();

    // Require the database connection:
    require(MYSQL);

    // Include the header file:
    $page_title = 'Change Your Password';
    include('./includes/header.html');

    //Create an array for storing errors:
    $pass_errors = array();

    // If it's a POST request, handle the form submission:
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        //Check the current password
        if(!empty($_POST['current'])){
            $current = $_POST['current'];
        }else{
            $pass_errors['current'] = 'Please enter your current password!';
        }

        //Validate the new password
        if(preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1'])){
            if($_POST['pass1'] == $_POST['pass2']){
                $p = $_POST['pass1'];
            }else{
                $pass_errors['pass2'] = 'Your password did not match the confirmed password!';
            }
        }else{
            $pass_errors['pass1'] = 'Please enter a valid password!';
        }

        //If everything is fine, validate the current password against the database
        if(empty($pass_errors)){

            //Check the current password:
            $q = "SELECT pass FROM users WHERE id={$_SESSION['user_id']}";
            $r = mysqli_query($dbc, $q);
            list($hash) = mysqli_fetch_array($r, MYSQLI_NUM);
            
            //Validate the password
            if(password_verify($current, $hash)){ //Correct!

                //Define the query
                $q = "UPDATE users SET pass='" . password_hash($p, PASSWORD_BCRYPT) . "' WHERE id={$_SESSION['user_id']} LIMIT 1";
                if($r = mysqli_query($dbc, $q)){ //If it ran OK

                    //Send an email if desired

                    //Indicate to the user the successful change:
                    echo 
                        '<h1>
                            You password has been changed.
                        </h1>';
                        include('./includes/footer.html');
                        exit();
                }else{
                    //If there was a problem, trigger an error.
                    trigger_error('Your password could not be changed due to a system error. We apologize for any inconvenience');
                }
            }else{ //Invalid password
                $pass_errors['current'] = 'Your current password is incorrect!';
            }
        }//End of the (empty($pass_errors) IF
    } //End of the form submission conditional
    
    // Need the form functions script, which defines create_form_input():
    // The file may already have been included by the header.
    require_once('./includes/form_functions.inc.php');
?>
<h1>
    Change Your Password
</h1>
<p>
    Use the form below to change your password.
</p>
<form action="change_password.php" method="post" accept-charset="utf-8">
    <?php
        create_form_input('current', 'password', 'Current Password', $pass_errors);
        create_form_input('pass1', 'password', 'Password', $pass_errors);
        echo 
            '<span class="help-block>
                Must be at least 6 characters long, with at least one lowercase letter, one uppercase letter, and one number.
            </span>';
        create_form_input('pass2', 'password', 'Confirm Password', $pass_errors);
    ?>
    <input type="submit" name="submit_button" value="Change &rarr;" id="submit_button" class="btn btn-default" />
</form>
<?php
    include('./includes/footer.html');
?>