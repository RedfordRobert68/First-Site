<?php
// Connect to the MySQL database  
require "connect_to_mysql.php";  

$sqliCommand = "CREATE TABLE users (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    type ENUM('member', 'admin') NOT NULL DEFAULT 'member',
    username VARCHAR(45) NOT NULL,
    email VARCHAR(80) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    first_name VARCHAR(45) NOT NULL,
    last_name VARCHAR(45) NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_expires DATE NOT NULL,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    UNIQUE INDEX username_UNIQUE (username ASC),
    UNIQUE INDEX email_UNIQUE (email ASC),
    INDEX login (email ASC, pass ASC)
    )ENGINE = InnoDB DEFAULT CHARSET=utf8";
if (mysqli_query($connection, $sqliCommand)){ 
   echo "Your users table has been created successfully!"; 
} else { 
   echo "CRITICAL ERROR: users table has not been created.";
}
?>