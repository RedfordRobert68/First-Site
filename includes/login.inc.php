<?php
    $login_errors = array();

    //Validate the email address
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $e = escape_data($_POST['email'], $dbc);
    }else{
        $login_errors['email'] = 'Please enter a valid email address!';
    }

    //Validate the password
    if(!empty($_POST['pass'])){
        $p = $_POST['pass'];
    }else{
        $login_errors['pass'] = 'Please enter a password!';
    }

    //If no errors, query the database
    if(empty($login_errors)){
        $q = "SELECT id, username, type, pass, IF(date_expires >= NOW(), true, false) AS expired FROM users WHERE email = '$e'";
        $r = mysqli_query($dbc, $q);

        //Check to see if user's account is valid
        //IF(date_expires >= NOW(), true, false)

        if(mysqli_num_rows($r) === 1){
            
            //Fetch the data
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);

            //Compare the password against the stored password
            if(password_verify($p, $row['pass'])){

                //If the user is and admin, create a new session ID to be safe
                if($row['type'] === 'admin'){
                    session_regenerate_id(true);
                    $_SESSION['user_admin'] = true;
                }

                //Store other data in the session
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                if($row['expired'] === 1){
                    $_SESSION['user_not_expired'] = true;
                }
            }else{
                //If the password didn't match, create an error message
                $login_errors['login'] = 'The email address and password do not match those on file';
            }
        }else{
            $login_errors['login'] = 'The email address and password do not match those on file';
        }
    }//End of the $login_errors script