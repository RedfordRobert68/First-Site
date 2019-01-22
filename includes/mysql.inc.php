<?php
    DEFINE('DB_USER', 'BobertoVicente');
    DEFINE('DB_PASSWORD', 'Rockon0123!');
    DEFINE('DB_HOST', 'localhost');
    DEFINE('DB_NAME', 'first_site');

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    mysqli_set_charset($dbc, 'utf8');
    
    //function to make data safe
    function escape_data($data, $dbc){
        //checks if magic quotes is enabled on server (hopefully not)
        if(get_magic_quotes_gpc()) $data = stripslashes($data);
        //return a trimeed, secure version of data
        return mysqli_real_escape_string($dbc, trim ($data));
    }//end if the escape_data() function
//omit closing php tag because this is an include file