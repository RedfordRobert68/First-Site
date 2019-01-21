<?php
// Connect to the MySQL database  
require "connect_to_mysql.php";  

$sqliCommand = "CREATE TABLE pdfs (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TINYTEXT NOT NULL,
    tmp_name CHAR(63) NOT NULL,
    file_name VARCHAR(100) NOT NULL,
    size MEDIUMINT UNSIGNED NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    UNIQUE INDEX tmp_name_UNIQUE (tmp_name ASC),
    INDEX date_created (date_created ASC)
    )ENGINE = InnoDB DEFAULT CHARSET=utf8";
if (mysqli_query($connection, $sqliCommand)){ 
   echo "Your pdfs table has been created successfully!"; 
} else { 
   echo "CRITICAL ERROR: pdfs table has not been created.";
}
?>