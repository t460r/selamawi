<?php
/**
 * Created by PhpStorm.
 * User: yehuala
 * Date: 6/22/2016
 * Time: 3:07 PM
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

        <?php
        include"DB.php";

        //$query=mysql_query("select petID,title from petition where status=2",$connection);
        $database=new DB();
        if(isset($_GET['searchstudent'])) {
            $name=$_GET['search'];
            $query = "select fname,mname,lname,studID from student where fname LIKE '%$name%' or mname LIKE '%$name%'  or lname LIKE '% $name%'";
            //echo $query;
            $stms = $database->db->query($query);
            if ($stms->num_rows > 0) {
                echo "<ul>";
                while($item = $stms->fetch_assoc()) {
                    echo "<li>" . $item['fname'] . " " . $item['mname'] . " " . $item['lname'] . "</a>";
                    echo "<li><a href='blockAccount.php?studID=" . $item['studID'] . "&type=yes'> <input type='submit' value='Block'> </a></li>";
                }
                echo "</ul>";
            } else {
                echo " ";
            }

        }else if( isset($_GET['studID']) and isset($_GET['type']))
        {
            if ( $_GET['type']=='yes')
                $status = 3;
            $query = "UPDATE student set status=$status WHERE studID=" . (int)$_GET['studID'];
            echo $query;
            $stmt = $database->db->query($query);
        }

        $database->db->close();
        ?>
    </div>
    <div>
        <form>
            <label id="search"></label>
            <input type="text" name="search" id="search" style="width: 30%">
            <input type="submit" name="searchstudent" value="search" style="width: auto">
        </form>
    </div>
</div>
</body>
</html>
