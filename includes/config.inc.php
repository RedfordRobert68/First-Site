<?php
//Define LIVE and CONTACT_EMAIL constants
if(!defined('LIVE')) DEFINE ('LIVE', false);
DEFINE('CONTACT_EMAIL', 'robert_vincent@metrographicsanddesign.com');

define('BASE_URI', 'C:/xampp/htdocs/first-site/First-Site/');
define('BASE_URL', 'localhost:82/first-site/First-Site/');
define('MYSQL', BASE_URI . 'includes/mysql.inc.php');
define('PDFS_DIR', BASE_URI . 'pdfs/');

session_start();

//define error-handling function
function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars){
    
    //Build the error message
    $message = "An error occurred in the script '$e_file' on line $e_line:\n$e_message\n";
    
    //backtrace information (everything that happened up to the point that the error occurred)
    $message = "<pre>" .print_r(debug_backtrace(), 1) . "</pre>\n";

    // Or just append $e_vars to the message:
	//	$message .= "<pre>" . print_r ($e_vars, 1) . "</pre>\n";
    
    //if the site isn't live show the error message in the browser
    if(!LIVE){//Show the error in the browser.
       echo '<div class="alert alert-danger">' .nl2br($message) .'</div>';
    
    }else{// Development (print the error)
        
        // Send the error in an email:
        error_log($message, 1, CONTACT_EMAIL, 'robert_vincent@metrographicsanddesign.com');
        
        // Only print an error message in the browser, if the error isn't a notice:
        if($e_number != E_NOTICE){
            echo '<div class="alert_alert-danger">A system error occurred. We apologize for the inconvenience.</div>';
        }
    }//End of $live IF-ELSE
    
    return true;
}//End of my_error_handler() definition
set_error_handler('my_error_handler');

/************************************************************
Redirect Function
*************************************************************/
if(!headers_sent()){

    //Redirect block of code. NOTE: remove surrounding text to remove headers
    function redirect_invalid_user($check = 'user_id', $destination = 'index.php', $protocol = 'http://'){
        if(!isset($_SESSION[$check])){
            $url = $protocol . BASE_URL . $destination;
            header("Location: $url");
            exit();
        }// End of redirect_invalid_user() function.
    }
}else{
    include_once('./includes/header.html');
    trigger_error('You do not have permission to access this page. Please log in and try again.');
    include_once('./includes/footer.html');
}    