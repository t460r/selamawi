<?php

/**
 * Created by PhpStorm.
 * User: yehuala
 * Date: 5/31/2016
 * Time: 10:02 PM
 */
class DB
{
    public $db;
    private $connected = true;
    private $resultset = array();
    private $num_rows  = 0;
    private $affected_rows  = -1;
    private $lastID = -1;

    const HOST       = "localhost";
    const DBNAME     = "selamawi_db";
    const USRNAME    = "root";
    const PSSWRD     = "";

    function __construct()
    {
        $this->db = new mysqli(DB::HOST, DB::USRNAME, DB::PSSWRD, DB::DBNAME);
        if(mysqli_connect_errno())
        {

            $this->connected = false;
            die();
        }


    }


}
