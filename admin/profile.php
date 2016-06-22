<?php
/**
 * Created by PhpStorm.
 * User: yehuala
 * Date: 6/21/2016
 * Time: 6:24 PM
 */
include('session.php');
//require 'DB.php';

$year=[1=>'Year',2=>'I',3=>'II',4=>'III',5=>'IV',6=>'V'];
$semister=[1=>'Semister',2=>'I',3=>'II',4=>'III'];
$section=[1=>'Section',2=>'1',3=>'2',4=>'3',5=>'4',6=>'5',7=>'6'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Home Page</title>
    <link href="loginstyle.css" rel="stylesheet" type="text/css">
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
    <!--<div id="petiitonlist" style="float:left;">
        <h2 style="color: black;">petition list</h2>-->
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
                echo"<ul><li><a href='profile.php?petID=".$item['petID'] . "&type=yes' title='click to blick the message'> <input type='submit' value='yes'> </a></li>";
                echo"<li> <a href='profile.php?petID=".$item['petID'] . "&type=no' title='click to pass as it is!'><input type='submit' value=' No'> </a></li></ul></li>";
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
</div>-->
<div class="container">
    <?php
    //include "DB.php";
    //$database = new DB();
    $fname=$lname=$id=$email=$department="";
    $mname=$semister1=$section1=$year1="";
    if(isset($_POST['create'])) {
        if (isset($_POST['fname'])) {
            $fname = $_POST['fname'];
        }
        if(isset($_POST['mname'])){
            $mname=$_POST['mname'];
        }

        if (isset($_POST['lname'])) {
            $lname = $_POST['lname'];
        }
        if(isset($_POST['id'])){
            $id=$_POST['id'];
        }

        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        }
        if (isset($_POST['department'])) {
            $department = $_POST['department'];
        }
        if(isset($_POST['section'])){
            $section1=$_POST['section'];
        }
        if(isset($_POST['semister'])){
            $semister1=$_POST['semister'];
        }
        if(isset($_POST['year'])){
            $year1=$_POST['year'];
        }
        /*
        if($database->db->connect_error){
        echo"you are not connercted to the database";
        }
        else{
        echo"you are conneted";
        }
        */

        $query = "INSERT INTO student(fname,mname,lname,ID,email,semester,year,section,department) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $database->db->prepare($query);
        $stmt->bind_param("sssssiiii", $fname,$mname, $lname,$id, $email,$semister1,$year1,$section1, $department);
        if ($stmt->execute()) {
            echo "<script type='javascript'>alert('you are succesfully register')</script>";
        } else {
            die("unable to register");
        }


    }

    ?>
    <h1 style="color: black;" >Registe user</h1>
    <form name="account" method="post" action="<?php echo htmlspecialchars ($_SERVER['PHP_SELF']); ?>">
        <div class="combine">
            <p>
                <label for="fname"></label>
                <input type="text" name="fname" id="fname" class="form-control" placeholder="First Name">
            </p>
            <p>
                <label for="mname"></label>
                <input type="text" name="mname" id="mname"  class="form-control" placeholder="middle Name">
            </p>
        </div>
        <p>
            <label for="lname"></label>
            <input type="text" name="lname" id="lname"  class="form-control" placeholder="last name">
        </p>
        <div class="combine">
            <p>
                <label for="id">
                </label>
                <input type="text" name="id" id="id" class="form-control" placeholder="ID">
            </p>
            <p>
                <label for="email">
                </label>
                <input type="email" name="email" id="email" class="form-control"  placeholder="Email">
            </p>
        </div>
        <div class="combine">
                <p>
                    <label for="semister">
                    </label>
                    <select name="semister" id="semister" class="form-control">
                        <?php foreach($semister as $key=>$elem){
            //this is used to iterate the assosiative array
            echo "<option value='$key'>$elem</option>";
        }
        ?></select>
                </p>
                <p>
                    <label for="year">
                    </label>
                    <select name="year" id="year" class="form-control">
                        <?php
        foreach($year as $k=> $item){
            echo"<option value='$k'>$item</option>";
        }
        ?>
                    </select>
                </p>

                <p>
                    <label for="section">
                        <select name="section" id="section" class="form-control">
                            <?php
        foreach($section as $ke=>$el){
            echo "<option value='$ke'>$el</option>";
        }
        ?>
                        </select>
                    </label>
                </p>
            </div>

        <p>
            <label for="department">
            </label>
            <?php
            $department=[
                1 => 'Software Engineering',
                2 => 'Mechanical Enginnering',
                3 => 'Bio medical Engineering',
                4 => 'Civil Engineering',
                5 => 'Computer and Electrical Engineering',
                6 => 'Chemical Engineering',
                7 => 'Information Technology (IT)']
            ?>
            <select name="department" id="department" class="form-control">
                <?php foreach($department as $key=>$item){
                    echo "<option value='$key'>$item</option>";
                }
                ?>
            </select>
        </p>
        <p>
            <input type="submit" name="create" id="create" value="Register" class="btn-primary">
        </p>
    </form>

</body>
</html>