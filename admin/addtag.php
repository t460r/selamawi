<?php
/**
 * Created by PhpStorm.
 * User: yehuala
 * Date: 6/22/2016
 * Time: 2:59 PM
 */
include "DB.php";

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
<div>
    <h1>add tag</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <p>
            <label for="tag">write the tag name

            </label>
            <input type="text" name="tag" id="tag">

        </p>
        <input type="submit" name="add" value="add">
    </form>
    <?php
    $database=new DB();
    if(isset($_POST['add'])){
        $tag=$_POST['tag'];
        $query="insert into tag (tagName) values('$tag')";
        $stms2=$database->db->query($query);

    
    if ($database->db->query($query) === TRUE) {
        echo "you add succesfully";
    } else {
        echo "Error deleting record: " . $database->db->error;
    }
    }
    ?>
</div>
</body>
</html>
