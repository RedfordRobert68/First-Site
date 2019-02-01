<?php
    //This script is for adding a page

    // Require the configuration before any PHP code as the configuration controls error reporting:
    require('./includes/config.inc.php');

    //Require the database connection
    require(MYSQL);

    //Validate the category ID:
    if(filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))){
        $cat_id = $_GET['id'];

        //Get the category title
        $q = 'SELECT category FROM categories WHERE id =' . $cat_id;
        $r = mysqli_query($dbc, $q);

        //If one row was not returned, report the problem:
        if(mysqli_num_rows($r) !== 1){
            $page_title = 'Error!';
            include('./includes/header.html');
            echo '
                <div class="alert alert-danger">
                    This page has been accessed in error.
                </div>';
            include('./includes/footer.html');
            exit();
        }

        //Fetch the category title and use it as the page title:
        list($page_title) = mysqli_fetch_array($r, MYSQLI_NUM);
        include('./includes/header.html');
        echo '<h1>' .htmlspecialchars($page_title) . '</h1>';

        //Print a message if the user doesn't have an active account
        if(isset($_SESSION['user_id']) && !isset($_SESSION['user_not_expired'])){
            echo '
                <div class="alert">
                    <h4>
                        Expired Account
                    </h4>
                    Thank you for your interest in this content. Unfortunately your account has expired. Please <a href="renew.php">renew your account</a> in order to access site content.
                </div>';
        }elseif(!isset($_SESSION['user_id'])){
            echo '
                <div class="alert">
                    Thank you for your interest in this content. You must be logged in as a registered user to view site content.
                </div>';
        }

        //Get the pages associated with this category
        $q = 'SELECT id, title, description FROM pages WHERE categories_id=' . $cat_id. ' ORDER BY date_created DESC';
        $r = mysqli_query($dbc, $q);
        if(mysqli_num_rows($r) > 0){
            while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
                echo '
                    <div>
                        <h4>
                            <a href="page.php?id=' . $row['id'] . '">' .htmlspecialchars($row['title']) . '</a>
                        </h4>
                        <p>' .htmlspecialchars($row['description']) . '</p>
                    </div>';
            }//End of WHILE loop
        
        }else{// No pages available
            echo '
                <p>
                    There are currently no pages of content associated with this category. Please check back again!
                </p>';
        }
    
    //If no valid ID was received by the page, display an error
    }else{
        $page_title = 'Error!';
        include('./includes/header.html');
        echo '
            <div class="alert alert-danger">
                This page has been accessed in error.
            </div>';
    }

    include('./includes/footer.html');
    
?>