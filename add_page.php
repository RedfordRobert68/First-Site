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
    $add_page_errors = array();

    // Check for a form submission:
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //Validate the page title
        if(!empty($_POST['title'])){
            $t = escape_data(strip_tags($_POST['title']), $dbc);
        }else{
            $add_page_errors['title'] = 'Please enter the title!';
        }

        //Validate the category
        if(filter_var($_POST['category'], FILTER_VALIDATE_INT, array('min_range' => 1))){
            $cat = $_POST['category'];
        }else{ //No category selection
            $add_page_errors['category'] = 'Please select a category';
        }

        //Validate the description
        if(!empty($_POST['description'])){
            $d = escape_data(strip_tags($_POST['description']), $dbc);
        }else{
            $add_page_errors['description'] = 'Please enter the description';
        }

        //Validate the content
        if(!empty($_POST['content'])){
            $allowed = '<div><p><span><br><a><img><h1><h2><h3><h4><ul><ol><li><blockquote>';
            $c = escape_data(strip_tags($_POST['content'], $allowed), $dbc);
        }else{
            $add_page_errors['content'] = 'Please enter the content!';
        }

        //If there are no errors, add the record to the database
        if(empty($add_page_errors)){
            $q = "INSERT INTO pages (categories_id, title, description, content) VALUES($cat, '$t', '$d', '$c')";
            $r = mysqli_query($dbc, $q);

            if(mysqli_affected_rows($dbc) === 1){
                echo
                    '<div class="alert alert-success">
                        <h3>
                            The page has been added!
                        </h3>
                    </div>';
            }else{ // Trigger an error if the query failed
                trigger_error('The page could not be added due to a system error. We apologize for any inconvenience.');
            }
        }// End of $add_page_errors IF.
    } //End of the main form submission consditional

    //Include the form_functions.inc.php
    require('includes/form_functions.inc.php');
?>

<h1>
    Add a Site Content Page
</h1>
<form action="add_page.php" method="post" accept-charset="utf-8">
    <fieldset>
        <legend>
            Fill out the form to add a page of content:
        </legend>
        <?php
            create_form_input('title', 'text', 'Title', $add_page_errors);

            //Add the category drop down menu
            echo '<div class="form-group';
            if(array_key_exists('category', $add_page_errors)) {
                echo 'has-error';
            }
            echo '">
                <label for="category" class="control-label">Category</label>
                <select name="category" class="form-control">
                <option>Select One</option>';
            
            //Retrieve all the categories and add to the pull-down menu      
            $q = "SELECT id, category FROM categories ORDER BY category ASC";
            $r = mysqli_query($dbc, $q);
            while($row = mysqli_fetch_array($r, MYSQLI_NUM)){
                echo "<option value=\"$row[0]\"";
                if(isset($_POST['category']) && ($_POST['category'] == $row[0])){
                    echo 'selected="selected"';
                }
                echo ">$row[1]</option>\n";
            }
              
            echo '</select>';
            if(array_key_exists('category', $add_page_errors)){
                echo 
                    '<span class="help_block">' . $add_page_errors['category'] . '</span>';
                echo '</div>';
            }

            //Complete the form
            create_form_input('description', 'textarea', 'Description', $add_page_errors);
            create_form_input('content', 'textarea', 'Content', $add_page_errors);
        ?>
        <input type="submit" name="submit_button" value="Add This Page" id="submit_button" class="btn btn-default" />
    </fieldset>
</form>
<script src='https://cloud.tinymce.com/5-testing/tinymce.min.js'></script>
<script>tinymce.init({ 
    selector: "#content", 
    width: "800",
    height: "400",
    browser_spellcheck: true,

    plugins: "paste searchreplace fullscreen hr link anchor image charmap media autoresize autosave contextmenu wordcount lists advlist", 
    
    toolbar1: "cut copy paste|undo redo removeformat|hr link unlink anchor image|charmap media|search replace|fullscreen", 

    toolbar2: "bold italic underline strikethrough|alignleft aligncenter alignright alignjustify|formatselect|numlist bullist|outdent indent blockquote",
    
    content_css : "css/bootstrap.min.css",
    });
</script>
<?php
    include('./includes/footer.html');
?>