<?php
include"server/db_conn.php";

if (isset($_POST['user']) && isset($_POST['password'])){
    session_regenerate_id();

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $user = validate($_POST['user']);
    $pass = validate($_POST['password']);
    $name = validate($_POST['name']);
    $passc = validate($_POST['password_check']);

    if (empty($user)){
        header("Location: signup.php?error=User Name is required");
        
        exit();        
    } elseif(empty($pass)){
        header("Location: signup.php?error=Password is required");
        
        exit();
    }
    elseif(empty($name)){
        header("Location: signup.php?error=Name is required");
        $error = "Name is required";
        exit();
    }elseif(empty($passc)){
        header("Location: signup.php?error=You must rewrite your password");
        
        exit();
    }elseif($pass != $passc){
        header("Location: signup.php?error=Rewrite the same password");
        
    }else {
        $sql = "INSERT INTO `users` (`id`, `user_name`, `password`,`name` ) VALUES (NULL, $user, $pass,$name)";
        $result = mysqli_query($conn, $sql);
    }



}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/signup.css">
    <title>HOME SERVER</title>
</head>
<body>
    
    <nav>
        <img class="pic1" src="img/logo.png" width="80px" height="80"><label class="logo">HOME SERVER</label>
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>
    <section>

        
        <div class="container">
            <div class="box">
                <div class="login"> 
                    <form method="POST" action="signup.php">
                        <h1>SIGNUP</h1>
                        <?php    if (isset($_GET['error'])){ ?>
        <p class="error" style="  background: #F2DEDE;
  color: #A94442;
  padding: 10px;
  width: 95%;
  border-radius: 5px;
  font-family: arial;"
  ><?php echo $_GET['error']; ?></p>
    <?php } ?>
                        <label for="name">Name:</label><br>
                        <input type="text" id="name" name="name"><br><br>
                        <label for="user">User:</label><br>
                        <input type="text" id="user" name="user" ><br><br>
                        <label for="password">Password:</label><br>
                        
                        <input type="password" id="password" name="password" ><br><br>
                        <label for="password_check">Confirm password:</label><br>
                        
                        <input type="password" id="password_check" name="password_check"><br><br>
                        <input type="submit" id="submit" name="submit" value="Submit"><br><input type="reset" id="reset" value="Reset">
                    </form>
                </div>
                <div class="side">
                    <img src="img/photo.png" width="500px" height="400px">
                </div>
            </div>
        </div>
        
        <div class='air air1'></div>
        <div class='air air2'></div>
        <div class='air air3'></div>
        <div class='air air4'></div>
        
    </section>

</body>
</html>
