




<?php
include"server/db_conn.php";

if (isset($_POST['user']) && isset($_POST['password'])){
    session_regenerate_id();
    
    $_SESSION['name'] = $_POST['user'];
    
    
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = validate($_POST['user']);
    $pass = validate($_POST['password']);

    if (empty($uname)){
        header("Location: info.php?error=User Name is required");
        exit();
    } elseif (empty($pass)){
        header("Location: info.php?error=Password is required");
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE user_name='$uname' AND password ='$pass'";
        

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1){
            $row = mysqli_fetch_assoc($result);
            if ($row['user_name'] === $uname && $row['password'] === $pass){
                $_SESSION['loggedin'] = true;
                $user_id = intval($row['id']); // Adjust this line based on your actual code
                $_SESSION['user_id'] = intval($user_id);
                $message1="Logged in!";
                $message2="welcome $uname";


            }
            if (isset($_SESSION['loggedin'])) {
                $message = "<li><a href='dashboard.php'>Dashboard</a></li>";
            
            }

            
            
        }else{
            header("Location: info.php?error=Incorrect User name or password");
            exit();

        }

    }
} else {
    header("Location: info.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/logged_in.css">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org//face/archivo-black" type="text/css"/>
    <title>HOME SERVER</title>
</head>
<body>
    
    <nav>
        <img class="pic1" src="img/logo.png" width="80px" height="80"><label class="logo">HOME SERVER</label>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php
            echo $message; 
            ?>
        </ul>
    </nav>
    <section>
    <h2 style="    color: #fffefe;
    font-size: 100px;
    font-family: 'ArchivoBlackRegular';
    text-align: center;
    margin-top: 10rem;"><?php echo $message1; ?></h2>
    <h2 style="    color: #fffefe;
    font-size: 100px;
    font-family: 'ArchivoBlackRegular';
    text-align: center;
    margin-top: 10rem;"><?php echo $message2; ?></h2>
    
    <button onclick="redirectToPage()">
    <span>Dashboard</span>
    
</button>
<p style="color:#fff;font-weight:bold;text-align:center;margin-top:1rem">Warning: By clicking here you are responsable on the files you upload we don't assure you to secure your files tottaly</p>
    <div class='air air1'></div>
        <div class='air air2'></div>
        <div class='air air3'></div>
        <div class='air air4'></div>
        
    </section>
    <script>
    function redirectToPage() {
        // Redirect to another page
        window.location.href = "dashboard.php" ;
      }
    </script>

</body>
</html>
