<?php
// Connect to the MySQL database  
require "connect_to_mysql.php";  

$sqliCommand = "CREATE TABLE orders (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    users_id INT UNSIGNED NOT NULL,
    transaction_id VARCHAR(45) NOT NULL,
    payment_status VARCHAR(45) NOT NULL,
    payment_amount INT UNSIGNED NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    INDEX date_created (date_created ASC),
    INDEX transaction_id (transaction_id ASC),
    CONSTRAINT fk_orders_users1 
        FOREIGN KEY (id)
        REFERENCES users (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )ENGINE = InnoDB DEFAULT CHARSET=utf8";
if (mysqli_query($connection, $sqliCommand)){ 
   echo "Your orders table has been created successfully!"; 
} else { 
   echo "CRITICAL ERROR: orders table has not been created.";
}
?>