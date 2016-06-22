<?php
/**
 * Created by PhpStorm.
 * User: yehuala
 * Date: 6/21/2016
 * Time: 6:26 PM
 */
session_start();
if(session_destroy()) // Destroying All Sessions
{
    header("Location: index.php"); // Redirecting To Home Page
}
?>