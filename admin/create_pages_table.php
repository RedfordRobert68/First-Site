<?php
// Connect to the MySQL database  
require "connect_to_mysql.php";  

$sqliCommand = "CREATE TABLE pages (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    categories_id SMALLINT UNSIGNED NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TINYTEXT NOT NULL,
    content LONGTEXT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    INDEX date_created (date_created ASC),
    INDEX fk_pages_categories_idx (categories_id ASC),
    CONSTRAINT fk_pages_categories 
        FOREIGN KEY (categories_id)
        REFERENCES categories (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )ENGINE = InnoDB DEFAULT CHARSET=utf8";
if (mysqli_query($connection, $sqliCommand)){ 
   echo "Your pages table has been created successfully!"; 
} else { 
   echo "CRITICAL ERROR: Pages table has not been created.";
}
?>