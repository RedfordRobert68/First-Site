<?php
/*
1."die()" will exit the script and show an error statement if something goes wrong with the "connect" or "select" functions
2. A "mysql_connect()" error usually means your username/password are wrong
3. A "mysql_select_dv()" error usually means that the database does not exist
*/
//Place db host name. Sometimes "localhost" but
//sometimes looks like >> ???mysql?? someserver.net

$db_host = "localhost";
//Username for MySQL database
$db_username = "lwadmin";
//Password for MySQL database
$db_pass = "BlackDiamonds1423!@";
//MySQL database name
$db_name = "lwstore";
//Run the actual connection here
mysql_connect("$db_host","$db_username","$db_pass") or die("could not connect to mysql");
mysql_select_db("$db_name") or die ("no database");
// echo ("You are connected");
?>