<?php
//This script is for adding a page

// Require the configuration before any PHP code as the configuration controls error reporting:
require('./includes/config.inc.php');

//Redirect non-administrators
//Must take place before including header.html
redirect_invalid_user('user_admin');

//Require the database connection
require(MYSQL);

//Include the header file
$page_title = 'Add a Site Content Page';
include('./includes/header.html');

//Create an array for storing errors
$add_pdf_errors = array();

//If the form was submitted, validate the title and description:
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!empty($_POST['title'])){
        $t = escape_data(strip_tags($_POST['title']), $dbc);
    }else{
        $add_pdf_errors['title'] = 'Please enter the title!';
    }
    if(!empty($_POST['description'])){
        $d = escape_data(strip_tags($_POST['description']), $dbc);
    }else{
        $add_pdf_errors['description'] = 'Please enter the description!';    
    }

    //Check for a PDF:
    if(is_uploaded_file($_FILES['pdf']['tmp_name']) && ($_FILES['pdf']['error'] === UPLOAD_ERR_OK)){
        $file = $_FILES['pdf'];

        //Validate the file information:
        $size = ROUND($file['size']/1024);
        if($size > 5120){
            $add_pdf_errors['pdf'] = 'The uploaded file was too large.';
        }

        //Validate the file's type:
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        if(finfo_file($fileinfo, $file['tmp_name']) !== 'application/pdf'){
            $add_pdf_errors['pdf'] = 'The uploaded file was not a PDF.';
        }
        finfo_close($fileinfo);

        //If there were no errors, create the file's new name and destination:
        if(!array_key_exists('pdf', $add_pdf_errors)){
            
            //Create a temporary name for the file
            $tmp_name = sha1($file['name']) . uniqid('', true);

            //Move the file to its proper folder but add _tmp, just in case
            $dest = PDFS_DIR . $tmp_name . '_tmp';

            if(move_uploaded_file($file['tmp_name'], $dest)){

                //Store the date in the session for later use:
                $_SESSION['pdf']['tmp_name'] = $tmp_name;
                $_SESSION['pdf']['size'] = $size;
                $_SESSION['pdf']['file_name'] = $file['name'];

                //Print a message:
                echo '
                    <div class="alert alert-success">
                        <h3>
                            The file has been uploaded!
                        </h3>
                    </div>';

            }else{
                trigger_error('The file could not be moved.');
                unlink ($file['tmp_name']);
            }
        } // End of array_key_exists IF
    }elseif(!isset($_SESSION['pdf'])){ // No current ot previous uploaded file
        switch ($_FILES['pdf']['error']){
            case 1:
            case 2:
                $add_pdf_errors['pdf'] = 'The uploaded file was too large.';
                break;
            case 3:
                $add_pdf_errors['pdf'] = 'The file was only partially uploaded.';
                break;
            case 6:
            case 7:
            case 8:
                $add_pdf_errors['pdf'] = 'The file could not be uploaded due to a system error.';
                break;
            case 4:
            default:
                $add_pdf_errors['pdf'] = 'No file was uploaded.';
                break;
        } // End of switch
    } // End of $_FILES IF-ELSEIF-ELSE

    //Add the PDF to the database:
    if(empty($add_pdf_errors)){
        $fn = escape_data($_SESSION['pdf']['file_name'], $dbc);
        $tmp_name = escape_data($_SESSION['pdf']['file_name'], $dbc);
        $size = (int) $_SESSION['pdf']['size'];
        $q = "INSERT INTO pdfs(title, description, tmp_name, file_name, size) VALUES('$t', '$d', '$tmp_name', '$fn', $size)";
        $r = mysqli_query($dbc, $q);

        //If the query worked, rename the temporary file:
        if(mysqli_affected_rows($dbc) === 1){
            $original = PDFS_DIR . $tmp_name . '_tmp';
            $dest = PDFS_DIR . $tmp_name;
            rename($original, $dest);

            //Indicate the success to the user and clear the values:
            echo '
                <div class="alert alert-success">
                    <h3>
                        The PDF has been added!
                    </h3>
                </div>';

            //Clear $_POST
            $_POST = array();

            //Clear $_FILES
            $_FILES = array();

            // Clear $file and $_SESSION['pdf']:
            unset($file, $_SESSION['PDF']);// All these values need to be cleared so that the form doesn't display any existing values.
        }else{ //If it did not run OK.
            trigger_error('The PDF could not be added due to a system error. We apologize for any inconvenience');
            unlink($dest);
        }

    }// End of $errors IF

//Clear out the session on a GET request:
}else{
    unset($_SESSION['pdf']);
}// End of the submission IF

//Begin the form:
// Need the form functions script, which defines create_form_input():
   

?>