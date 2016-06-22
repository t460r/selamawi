<?php
/**
 * Created by PhpStorm.
 * User: yehuala
 * Date: 6/22/2016
 * Time: 2:42 PM
 */





?>
<html>
<head>
    <title>Your Home Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">

</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="profile.php">home</a></li>
            <li><a href="logout.php">logout</a></li>
            <li><a href="petitionlist.php">petitionlist</a></li>
            <li><a href="addtag.php">addtag</a></li>
            <li><a href="blockAccount.php">block account</a></li>

        </ul>
    </nav>
</header>
<div style="display: inline; background-color: black;" >
    <div id="petiitonlist" style="float:left;">
        <h2 style="color: black;">petition list</h2>
        <?php
        include"DB.php";
        //$query=mysql_query("select petID,title from petition where status=2",$connection);
        $database=new DB();
        $query="select petID,title from petition where status=2";
        $stms=$database->db->query($query);
        if($stms->num_rows>0){
            echo"<ol>";
            foreach($stms as $item){
                echo"<li>". $item['title']."</a>";
                echo"<ul><li><a href='petitionlist.php?petID=".$item['petID'] . "&type=yes' title='click to blick the message'> <input type='submit' value='yes'> </a></li>";
                echo"<li> <a href='petitionlist.php?petID=".$item['petID'] . "&type=no' title='click to pass as it is!'><input type='submit' value=' No'> </a></li></ul></li>";
            }
            echo"</ol>";
        }
        else{
            echo "there is no reported petition";
        }

        if (isset($_GET['petID']) and isset($_GET['type'])){
            if($_GET['type']=='yes')
                $status = '3';
            else
                $status = '1';

            $query="update petition set status='$status' where petID=". (int)$_GET['petID'];
            $stms2=$database->db->query($query);

        }
        if ($database->db->query($query) === TRUE) {
            echo "Record are changed successfully";
        } else {
            echo "" . $database->db->error;
        }



        //$database->db->close();
        ?>
</div>
<div></div>
</div>
</body>
</html>
