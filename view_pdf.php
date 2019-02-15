<?php
    // Require the configuration before any PHP code as the configuration controls error reporting:
    require('./includes/config.inc.php');

    //Require the database connection
    require(MYSQL);
    //Create a flag variable
    //This script will have many tests before getting to the point of displaying the PDF, so it will start by assuming that something's wrong.
    $valid = false;

    //Validate the PDF ID:
    if(isset($_GET['id']) && (strlen($_GET['id']) === 63) && (substr($_GET['id'], 0, 1) !== '.') ){//ID is not an integer but should be exactly 63 characters long. Third part checks that the first character isn't an integer. 
        $file = 'PDFS_DIR' . $_GET['id'];
        //File is tested to confirm that it exists and is a file (as opposed to a directory)
        if(file_exists($file) && (is_file($file))){

            //Get the PDF information from the database:
            $q = 'SELECT id, title, description, file_name FROM pdfs WHERE tmp_name="' . escape_data($_GET['id'], $dbc) . '"';
            $r = mysqli_query($dbc, $q);
            if(mysqli_num_rows($r) === 1){
                $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
                $valid = true;

                //Only display the PDF to a user whose account is active:
                if(isset($_SESSION['user_not_expired'])){
                    header('Content-type:application/pdf');
                    header('Content-Disposition:inline;filename="' . $row['file_name'] . '"');//Show the file in the browser -  and what filename it is
                    $fs = filesize($file);//Show the actual file size not the database-stored approximation.
                    header("Content-Length:$fs\n");
                    readfile ($file);//Reads the binary data and sends it to the database.
                    exit();
                
                //For inactive users, show the content's description:
                }else{//Inactive account
                    $page_title = $row['title'];
                    include('./includes/header.html');
                    echo "
                        <h1>
                            $page_title
                        </h1>";
                    if(isset($_SESSION['user_id'])){
                        echo '
                            <div class="alert">
                                <h4>
                                    Expired Account
                                </h4>
                                Thank you for your interest in this content, but your account is no longer current. Please <a href="renew.php">renew your account</a> in order to access this file.
                            </div>';
                    }else{// Not logged in
                        echo '
                            <div class="alert">
                                Thank you for your interest in this content. You must be logged in as a registered user to access this file.
                            </div>';
                    }
                    echo '<div>' . htmlspecialchars($row['description']) . '</div>';
                    include('./includes/footer.html');
                }//End of user IF-ELSE.
            }//End of mysqli_num_rows() IF
        }//End of file_exists() IF
    }//End of $_GET['id'] IF.

    //Indicate a problem and complete the page:
    if(!$valid){
        $page_title = 'Error!';
        include('./includes/header.html');
        echo '
            <div class="alert alert-danger">
                This page has been accessed in error.
            </div>';
        include('./includes/footer.html');
    }//If the page didn't receive an ID corresponding to a database record and an actual file on the server, the user will see an error message.
?>