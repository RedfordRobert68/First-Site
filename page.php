<?php
    // Require the configuration before any PHP code as the configuration controls error reporting:
    require('./includes/config.inc.php');

    //Require the database connection
    require(MYSQL);

    //Validate the page ID:
    if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))){
        $page_id = $_GET['id'];

        //Get the page info
        $q = 'SELECT title, description, content FROM pages WHERE id = ' . $page_id . '';
        $r = mysqli_query($dbc, $q);

        //If no rows were returned, print an error
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

        //Fetch the page info
        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
        $page_title = $row['title'];
        include('includes/header.html');
        echo '
            <h1>
                ' . htmlspecialchars($page_title) .' 
            </h1>'; // This page's title will be used as the browser's title and as a header on the page
        
        //Display the content if the user's account is current
        if(isset($_SESSION['user_not_expired'])){
            echo "
                <div>
                    {$row['content']}
                </div>";
        }elseif(isset($_SESSION['user_id'])){    
            echo '
                <div class="alert">
                    <h4>
                        Expired Account
                    </h4>
                    Thank you for yor interest in this content, but your account has is no longer current. <a href="renew.php">Please renew your account</a> in order to view this page in its entirety. 
                </div>';
            echo '
                <div>
                    ' . htmlspecialchars($row['description']) . '
                </div>';
        }else{
            echo '
                <div class="alert">
                    Thank you for your interest in this content. You must be logged in as a registered user to view this page in its entirety.
                </div>';
            echo '<div>' . htmlspecialchars($row['description']) . '</div>';
        }
            
    //Complete the ID conditional
    }else{
        $page_title = 'Error!';
        include('includes/header.html');
        echo '
            <div class="alert alert-danger">
                This page has been accessed in error.
            </div>';
    } //End of primary IF. If no integer ID greater than or equal to 1 was received by this page, the user will see this error message.

    include('./includes/footer.html');
?>