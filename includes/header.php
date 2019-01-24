<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
      <?php // Use a default page title if one wasn't provided...
          if (isset($page_title)) { 
              echo $page_title; 
          } else { 
              echo 'Knowledge is Power: And It Pays to Know'; 
          } 
      ?>
  </title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">

  </head>

  <body>

    <!-- Wrap all page content here -->
    <div id="wrap">

      <!-- Fixed navbar -->
      <div class="navbar navbar-fixed-top">
        <div class="container">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Knowledge is Power</a>
          <div class="nav-collapse collapse">
            <ul class="nav navbar-nav">
              <?php //Dynamically create header menus

                // Array of labels and pages (without extensions):
                $pages = array(
                  'Home' => 'index.php',
                  'About' => '#',
                  'Contact => #',
                  'Register' => 'register.php'
                );

                // The page being viewed:
                $this_page = basename($_SERVER['PHP_SELF']);

                // Create each menu item:
                foreach($pages as $k => $v){

                  // Start the item:
                  echo '<li';

                  // Add the class if its the current page:
                  if($this_page ==$v){ 
                    echo ' class="active"';
                  }

                  // Complete the item:
                  echo '><a href="' . $v .'">' . $k . '</a></li>';
                }//End the foreach loop

                // Show the user options:
                if(isset($_SESSION['user_id'])){
                  
                  // Show basic user options:
                  echo '
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                          <li><a href="logout.php">Logout</a></li>
                          <li><a href="renew.php">Renew</a></li>
                          <li><a href="change_password.php">Change Password</a></li>
                          <li><a href="favorites.php">Favorites</a></li>
                          <li><a href="recommendations.php">Recommendations</a></li>
                      </ul>
                  </li>';

                  //Show administration options
                  if(isset($_SESSION['user_admin'])){
                    echo'
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href="add_page.php">Add Page</a></li>
                        <li><a href="add_pdf.php">Add PDF</a></li>
                        <li><a href="#">Something else here</a></li>
                      </ul>
                    </li>';
                  }
                }// user_id not set.
              ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/container-->
      </div><!--/navbar-->

      <!-- Begin page content -->
      <div class="container">
	
		<div class="row">
			
			<div class="col-3">
				<h3 class="text-success">Content</h3>
			<div class="list-group">
        <?php
          $q = 'SELECT * FROM categories ORDER BY category';
          $r = mysqli_query($dbc, $q);
          while(list($id, $category) = mysqli_fetch_array($r, MYSQLI_NUM)){
            echo '<a href="category.php?id=' . $id . '" class="list-group-item" title="' . $category .'">' . htmlspecialchars($category) . '</a>';
          }
        ?>
			</div><!--/list-group-->

      <?php
        if(!isset($_SESSION['user_id'])){
          require('includes/login_form.inc.php');
        }
      ?>

			<!--<form>
			  <fieldset>
			    <legend>Login</legend>
			    <div class="form-group">
			      <label for="email">Email address</label>
			      <input type="text" class="form-control" id="email" placeholder="Enter email">
			    </div>
			    <div class="form-group">
			      <label for="pass">Password</label>
			      <input type="password" class="form-control" id="pass" placeholder="Password">
			    </div>
			    <button type="submit" class="btn btn-default">Login</button>
			  </fieldset>
			</form>-->			
			</div><!--/col-3-->
		  
			
		  <div class="col-9">