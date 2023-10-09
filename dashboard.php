<?php
// Start the session
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}


// Connect to the database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'test';
function validate($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the file statistics
$user_id = $_SESSION['user_id']; // Get the user's id from the session


$totalFilesQuery = "SELECT COUNT(*) as total_files FROM files WHERE user_id = $user_id";
$txtFilesQuery = "SELECT COUNT(*) as total_txt_files FROM files WHERE file_type = 'text/plain' AND user_id = $user_id";
$pngFilesQuery = "SELECT COUNT(*) as total_png_files FROM files WHERE file_type = 'image/jpeg' AND user_id = $user_id";
$pdfFilesQuery = "SELECT COUNT(*) as total_pdf_files FROM files WHERE file_type = 'application/pdf' AND user_id = $user_id";


$totalFilesResult = mysqli_query($conn, $totalFilesQuery);
$txtFilesResult = mysqli_query($conn, $txtFilesQuery);
$pngFilesResult = mysqli_query($conn, $pngFilesQuery);
$pdfFilesResult = mysqli_query($conn, $pdfFilesQuery);

$totalFiles = $totalFilesResult->fetch_assoc()['total_files'];
$txtFiles = $txtFilesResult->fetch_assoc()['total_txt_files'];
$pngFiles = $pngFilesResult->fetch_assoc()['total_png_files'];
$pdfFiles = $pdfFilesResult->fetch_assoc()['total_pdf_files'];

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home Server</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <!-- Boxicons CSS -->
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="js/dashboard.js" defer></script>
  </head>
  <body >
  <nav class="sidebar locked">
      <div class="logo_items flex">
        <span class="nav_image">
          <img src="img/logo.png" alt="logo_img" />
        </span>
        <span class="logo_name">Home Server</span>
        <i class="bx bx-lock-alt" id="lock-icon" title="Unlock Sidebar"></i>
        <i class="bx bx-x" id="sidebar-close"></i>
      </div>
      <div class="menu_container">
        <div class="menu_items">
          <ul class="menu_item">
            <div class="menu_title flex">
              <span class="title">Dashboard</span>
              <span class="line"></span>
            </div>
            <li class="item">
              <a href="dashboard.php" class="link flex">
                <i class="bx bx-home-alt"></i>
                <span>Overview</span>
              </a>
            </li>
            <li class="item">
              <a href="image_viewer.php" class="link flex">
                <i class="bx bx-grid-alt"></i>
                <span>Image Viewer</span>
              </a>
            </li>
            <li class="item">
              <a href="text_viewer.php" class="link flex">
                <i class="bx bx-grid-alt"></i>
                <span>Text Viewer</span>
              </a>
            </li>
          </ul>
          <ul class="menu_item">
            <div class="menu_title flex">
              <span class="title">Upload</span>
              <span class="line"></span>
            </div>
            <li class="item">
              <a href="file_manager.php" class="link flex">
                <i class="bx bx-folder"></i>
                <span>Browse files</span>
              </a>
            </li>
            <li class="item">
              <a href="upload.php" class="link flex">
                <i class="bx bx-cloud-upload"></i>
                <span>Upload New</span>
              </a>
            </li>
            <li class="item">
              <a href="pdf_viewer.php" class="link flex">
                <i class="bx bx-folder"></i>
                <span>PDF viewer</span>
              </a>
            </li>
            <li class="item">
              <a href="logout.php" class="link flex">
                <i class="fas fa-sign-out-alt"></i>
                <span>Log Out</span>
              </a>
            </li>
          </ul>
          </ul>
        </div>
        
      </div>
    </nav>
    <h1 style="text-align:center;font-size:50px">Hello <span style="color:orange;"><?php echo $_SESSION['name'] ?></span></h1>
    <button onclick="redirectToPage3()" role="button" class="button-name" style="position:absolute;top:10px;right:10px;margin-top:0rem;padding:15px;padding-left:40px;padding-right:40px;background-color:#ff7d04;font-weight:bold;box-shadow: rgba(45, 35, 66, 0.2) 0 2px 4px,rgba(45, 35, 66, 0.15) 0 7px 13px -3px,#ff7d54 0 -3px 0 inset;">Home</button>
    <div class="container">
      <div class="card" style="margin-left:20rem;margin-top:5rem;"><span style="font-size:40px;font-weight:bold;text-align:center;margin-top:4rem;"><?php echo $totalFiles; ?></span><span style="font-weight:bold;text-align:center;margin-top:2rem;">You have <?php echo $totalFiles; ?> files stored</span></div>
      <div class="card" style="margin-left:50rem;margin-top:5rem;"><span style="font-size:40px;font-weight:bold;text-align:center;margin-top:4rem;"><?php echo $txtFiles; ?></span><span style="font-weight:bold;text-align:center;margin-top:2rem;">You have <?php echo $txtFiles; ?> .txt files stored</span></div>
      <div class="card" style="margin-left:80rem;margin-top:5rem;"><span style="font-size:40px;font-weight:bold;text-align:center;margin-top:4rem;"><?php echo $pngFiles; ?></span><span style="font-weight:bold;text-align:center;margin-top:2rem;">You have <?php echo $pngFiles; ?> images files stored</span></div>
      <div class="card" style="width:50rem;margin-left:37.5rem;margin-top:25rem;"><span style="font-size:40px;font-weight:bold;text-align:center;margin-top:4rem;"><?php echo $pdfFiles; ?></span><span style="font-weight:bold;text-align:center;margin-top:2rem;">You have <?php echo $pdfFiles; ?> pdf files stored</span></div>
      <button role="button" class="button-name" style="margin-left: 27.5rem;margin-top: 25rem;" onclick="redirectToPage1()">Browse Files</button>
      <button role="button" class="button-name" style="margin-left: 20.5rem;margin-top: 45rem;" onclick="redirectToPage()">Text Viewer</button>
      <button role="button" class="button-name" style="margin-left: 21.5rem;margin-top: 25rem;" onclick="redirectToPage2()">Image Viewer</button>
    </div>
    
    <script>
    function redirectToPage() {
        // Redirect to another page
        window.location.href = "text_viewer.php" ;
      }
      function redirectToPage1() {
        // Redirect to another page
        window.location.href = "file_manager.php" ;
      }
      function redirectToPage2(){
        window.location.href = "image_viewer.php";
      }
      function redirectToPage3(){
        window.location.href = "index.php"
      }

    </script>

  </body>
</html>