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
    <link rel="stylesheet" href="css/style.css">
    <title>HOME SERVER</title>
</head>
<body>
    
    <nav>
        <img class="pic1" src="img/logo.png" width="80px" height="80"><label class="logo">HOME SERVER</label>
        <ul>
            <li><a href="info.php">login</a>
        </li>
            <?php echo $message;
            ?>
            
        </ul>
    </nav>
    
    <section>
        <div class="all">
            <div class="container">
                <h1 class="title">Welcome to <span class="name1">HOME SERVER</span> </h1>
                <p class="para"><span class="name1">HOME SERVER</span> is a Lahmer's home service makes all the familly members <br> access to a server in the house who stores many types of data. </p>
                <br><button onclick="redirectToPage()">LOGIN</button>

    
            </div>
            <div class="container2">
                <img src="img/picture.png">
            </div>
        </div>


        <div class='air air1'></div>
        <div class='air air2'></div>
        <div class='air air3'></div>
        <div class='air air4'></div>
        
    </section>
    <script>
    function redirectToPage() {
        // Redirect to another page
        window.location.href = "info.php" ;
      }
    </script>

</body>
</html>
