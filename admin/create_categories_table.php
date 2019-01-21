<?php
// Connect to the MySQL database  
require "connect_to_mysql.php";  

$sqliCommand = "CREATE TABLE categories (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    category VARCHAR(45) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE INDEX category_UNIQUE (category ASC)
    )ENGINE = InnoDB DEFAULT CHARSET=utf8";
if (mysqli_query($connection, $sqliCommand)){ 
   echo "Your categories table has been created successfully!"; 
} else { 
   echo "CRITICAL ERROR: categories table has not been created.";
}
?>