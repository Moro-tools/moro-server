<?php
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'test';

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $user_id = $_SESSION['user_id'];

// Now you can use $user_id in your SQL query
$query = "SELECT user_id, filename FROM files WHERE file_type = 'text/plain' AND user_id = $user_id";
$txtFilesResult = $conn->query($query);


    // Get selected file contents
    $selectedFileContent = '';
    if (isset($_POST['selected_file'])) {
        $selectedFile = $_POST['selected_file'];
        $selectedFileQuery = "SELECT file_content FROM files WHERE filename = '$selectedFile'";
        $selectedFileResult = $conn->query($selectedFileQuery);
        $selectedFileRow = $selectedFileResult->fetch_assoc();
        $selectedFileContent = $selectedFileRow['file_content'];
        $selectedFileQuery1 = "SELECT file_path FROM files WHERE filename = '$selectedFile'";
        $selectedFileResult1 = $conn->query($selectedFileQuery1);
        $row = mysqli_fetch_assoc($selectedFileResult1);

        // Make sure to properly sanitize and validate the data before outputting it
        $filePath = htmlspecialchars($row['file_path']); // Assuming the column name is 'filepath'
    }

    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="css/text_viewer.css">
    <title>Text Viewer</title>
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="js/dashboard.js" defer></script>
</head>
<body>
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
    

<div>
    <h3 style="font-size:30px;text-align:center">Browse .txt Files</h3>
    <form method="POST">
          <div class="selected-con">
            <select name="selected_file" class="select1" style="margin-top:1rem;border: solid 3px #f37702;">
              <?php while ($row = $txtFilesResult->fetch_assoc()) { ?>
                <option  value="<?php echo $row['filename']; ?>"><?php echo $row['filename']; ?></option>
              <?php } ?>
            </select>
            <div class="view-con">
              <input type="submit" value="View File"></div>
            </div>
            
          </div>
    </form>
    
        <h4 style="text-align:center;margin-top:3rem">Selected File Content:</h4>
        <div class="show-con" >
        <div style="display:flex;justify-content:center;border: 3px solid #f37702;width:600px;height:600px;padding:3rem;border-radius:1rem;overflow:scroll;">
          <pre><?php echo $selectedFileContent; ?></pre>
        </div>
        </div> 
        <div class="ex_file">
          <a href="<?php echo $filePath; ?>" target="_blank" style="text-decoration:none;font-size:17px;font-weight:bold;color:black;padding:1.5rem">View the file clearly</a>
        </div>
               
</div>


</body>
</html>

