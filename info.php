<?php
include"server/db_conn.php";
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'test';
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = null;
if (isset($_SESSION['loggedin'])) {
    $message = "<li><a href='dashboard.php'>Dashboard</a></li>";

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>HOME SERVER</title>
</head>
<body>
    
    <nav>
        <img class="pic1" src="img/logo.png" width="80px" height="80"><label class="logo">HOME SERVER</label>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php 
            echo $message; ?>
        </ul>
    </nav>
    <section>

        
        <div class="container">
            <div class="box">
                <div class="login"> 
                    <form method="POST" action="login.php">
                        <h1>LOGIN</h1>
                        <?php if (isset($_GET['error'])){ ?>
        <p class="error" style="  background: #F2DEDE;
  color: #A94442;
  padding: 10px;
  width: 95%;
  border-radius: 5px;
  font-family: arial;"
  ><?php echo $_GET['error']; ?></p>
    <?php } ?>
                        <label for="user">User:</label><br>
                        <input type="text" id="user" name="user" ><br><br>
                        <label for="password">Password:</label><br>
                        <input type="password" id="password" name="password" ><br><br>
                        <input type="submit" id="submit" name="submit" value="SUBMIT"><input type="reset" id="reset" value="RESET">
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
