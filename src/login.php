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

    $post = $_SERVER['REQUEST_METHOD']=='POST';
    if ($post) {
        if(
            empty($_POST['uname'])||
            empty($_POST['pass'])
        ) $empty_fields = true;

        else {
                try {
                    $pdo = new PDO("mysql:host=localhost;dbname=".$sqldatabase,$sqluser,$sqlpassword);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    exit($e->getMessage());
                }
                $st = $pdo->prepare('SELECT * FROM list WHERE user_name=?');
                $st->execute(array($_POST['uname']));
                $r=$st->fetch();
                if($r != null && $r["password"]==$_POST['pass']) {
                    echo $_POST["uname"];
                    echo $_POST["pass"];
                    $_SESSION["uname"] = $_POST["uname"];
                    $_SESSION["pass"] = $_POST["pass"];
                    $_SESSION["fname"] = $r["first_name"];
                    echo $_SESSION["uname"];
                    echo $_SESSION["pass"];
                    header("Location:success.php");
                    exit;
                } else $login_err = true;
        }
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
        padding:10px 20px;
        border-radius: 2px;
        box-shadow:0px 0px 10px #aaa;
        box-sizing:border-box;
    }
    input {
        display: inline-block;
        border: none;
        width:100%;
        border-radius:2px;
        margin:5px 0px;
        padding:7px;
        box-sizing: border-box;
        box-shadow: 0px 0px 2px #ccc;
    }
    #submit {
        border:none;
        background-color: blue;
        color:white;
        font-size:1em;
        box-shadow: 0px 0px 3px #777;
        padding:10px 0px;
    }
    span {
        color:red;
        font-size: 0.75em;
    }
    p {
        text-align: center;
        font-size: 1.75em;
    }
    a {
        text-decoration: none;
        color:blue;
        font-weight: bold;
    }
</style>
</head> 
<body>
<div>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <p>Login</p>
    <?php 
    echo 'Username<br><input type="text" name="uname" value="'.$_POST['uname'].'" placeholder="Username"><br>';
    echo '<br>Password<br><input type="password" name="pass" value="'.$_POST['pass'].'" placeholder="Password"><br>';
    if(!empty($login_err)&&$login_err) echo "<span>Incorrect Username or password.</span>";
    if(!empty($empty_fields)&&$empty_fields) echo "<span>Enter username and password.</span>";
    ?>
    <br>
    <input type="submit" id="submit" value="Login"><br><br>
    Don't have a account? <a href="signup.php">SignUp</a>.<br><br>
</form>
</div>
</body>
</html>