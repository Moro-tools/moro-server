<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

// Connect to the database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'test';
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$message1 = "";
if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
    $fileName = $_FILES['uploaded_file']['name'];
    $fileType = $_FILES['uploaded_file']['type'];
    if($fileType === 'image/png'){
      $fileType = 'image/jpeg';
    }
    $fileSize = $_FILES['uploaded_file']['size'];
    $fileTempPath = $_FILES['uploaded_file']['tmp_name'];
    $fileContent = file_get_contents($fileTempPath);
    // Get the logged-in user's ID
    $userId = $_SESSION['user_id'];

    // Generate a unique file path
    $destination = "uploads/" . uniqid() . "_" . $fileName;
    $allowedFileTypes = ['text/plain', 'image/png', 'image/jpeg','application/pdf'];
if (!in_array($_FILES['uploaded_file']['type'], $allowedFileTypes)) {
    $message = "Only .txt .pdf and .png or .jpeg files are allowed.";}

    elseif (move_uploaded_file($fileTempPath, $destination)) {
        // Insert file details into the database
        $insertQuery = "INSERT INTO files (filename, file_type, file_size, file_content, file_path, user_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssisss", $fileName, $fileType, $fileSize, $fileContent, $destination, $userId);
        if ($stmt->execute()) {
            $message = "File uploaded successfully.";
        } else {
            $message = "Error uploading file: " . $stmt->error;
        }
        


        $stmt->close();
    }
     else {
        $message = "Error moving file to destination.";
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home Server</title>
    <link rel="stylesheet" href="css/upload.css" />
    <!-- Boxicons CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
    <h2 style="text-align:center;font-size:40px;color:#f48240;">Upload File</h2><br>
    <?php if (isset($message)) { ?>
        <h3 style="text-align:center;font-weight:bold;margin-top:1rem;"><?php echo $message; echo $message1; ?></h3><br><br>
    <?php } ?>
    <form method="POST" enctype="multipart/form-data" style="text-align:center;">
        <div class="input-container" >
            <label for="file" class="drop-container">
                <span class="drop-title">Drop files here</span>
                or
                <input type="file" name="uploaded_file" required>
            </label>
        </div><br>
        <button class="button type1" type="submit" name="upload">
            <span class="btn-txt">Upload</span>
        </button>
    </form>
    <p style="text-align:center;margin-top: 1rem;color:gray;">Warning: You can't upload a file that contains arabic or any other language except english and frensh.</p>
  </body>
</html>