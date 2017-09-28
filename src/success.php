<?php

    session_start();

    $sqluser = "user";
    $sqlpassword = "password";

    /*
    Replace user and password above with your sql server user and password.
    if you have not created an user, run sql server in shell as root user and enter:
    
    CREATE USER 'user'@'localhost' IDENTIFIED BY 'password';

    GRANT ALL PRIVILEGES ON *.* TO 'user'@'localhost';

    FLUSH PRIVILEGES;
    
    You can replace user and password with your desired username and password.
    */

    $sqldatabase = "login";

    /*
    for this page to work you have to create a database named login and a table named list in your mysql server.
    To do this, enter following in your mysql server:

    CREATE DATABASE login;

    USE login;

    CREATE TABLE list(
        id int not null auto_increment,
        user_name varchar(255) not null,
        first_name varchar(255) not null,
        last_name varchar(255) not null,
        email varchar(255) not null,
        password varchar(255) not null,
        PRIMARY KEY (id)
    );

    keep 'login' and 'list' and all field names in lowercase, otherwise, it won't work.
    */

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=".$sqldatabase,$sqluser,$sqlpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
    $st = $pdo->prepare('SELECT * FROM list WHERE user_name=?');
    $st->execute(array($_SESSION["uname"]));
    if(($r=$st->fetch())==null||($r["password"]!=$_SESSION["pass"])) {
        header("Location:login.php");
        exit;
    }
    if ($_SERVER['REQUEST_METHOD']=='POST') {
    	session_destroy();
        header("Location:login.php");
        exit;
    	
    }
?>

<!DOCTYPE HTML>
<html>
<head>
<style type="text/css">
    body {
        margin:0px;
        padding:0px;
        font-family: sans-serif;
        font-size:.9em;
    }
    div {
        top:50%;
        left:50%;
        transform: translate(-50%,-50%);
        -ms-transform: translate(-50%,-50%);
        -moz-transform: translate(-50%,-50%);
        -webkit-transform: translate(-50%,-50%);
        position:absolute;
        width:350px;
        background:#eee;
        padding:20px;
        border-radius: 2px;
        box-shadow:0px 0px 10px #aaa;
        box-sizing:border-box;
    }
    #submit {
    	width:100%;
    	display: inline-block;
        border:none;
        background-color: blue;
        color:white;
        font-size:1em;
        box-shadow: 0px 0px 3px #777;
        padding:10px 0px;
    }
    p {
        text-align: center;
        font-size: 1.75em;
    }
</style>
</head> 
<body>
<div>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <p>Logged In</p><br>
    Welcome user <?php echo $_SESSION["fname"].' (@'.$_SESSION["uname"].').';?><br><br>
    <input type="submit" id="submit" name="submit" value="Logout">
</form>
</div>
</body>
</html>