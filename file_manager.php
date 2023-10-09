<?php

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}
$user_id = intval($_SESSION['user_id']);
    // Check if the user is logged in
    // Add your authentication logic here

    // Connect to the database
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'test';
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle file deletion
    if (isset($_POST['delete'])) {
        $fileId = $_POST['file_id'];
        $deleteQuery = "DELETE FROM files WHERE id = '$fileId'";
        if ($conn->query($deleteQuery) === TRUE) {
            $message = "File deleted successfully.";
        } else {
            $message = "Error deleting file: " . $conn->error;
        }
    }

    // Handle file renaming
    if (isset($_POST['rename'])) {
        $fileId = $_POST['file_id'];
        $newFilename = $_POST['new_filename'];
        $updateQuery = "UPDATE files SET filename = '$newFilename' WHERE id = '$fileId'";
        if ($conn->query($updateQuery) === TRUE) {
            $message = "File renamed successfully.";
        } else {
            $message = "Error renaming file: " . $conn->error;
        }
    }

    // Handle deleting all files
    if (isset($_POST['delete_all'])) {
        $deleteAllQuery = "DELETE FROM files WHERE user_id = $user_id";
        if ($conn->query($deleteAllQuery) === TRUE) {
            $message = "All files deleted successfully.";
        } else {
            $message = "Error deleting files: " . $conn->error;
        }
    }
    
    // Get all files
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $filesQuery = "SELECT id,user_id, filename, file_type, file_size FROM files WHERE user_id = $user_id";
    if (!empty($search)) {
      // Add a condition to search for filenames containing the search keyword
      $filesQuery .= " AND filename LIKE '%$search%'";
    }
    $filesResult = $conn->query($filesQuery);


    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Manager</title>
    <link rel="stylesheet" href="css/file_manager.css">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="js/dashboard.js" defer></script>
    <script src="js/sure.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
    <h2 style="text-align:center;font-size:30px;color:#f37702;">File Manager</h2>
    <form id="search-form" action="file_manager.php" method="GET">
  <div class="search-container" style="display: flex;justify-content: center;">
  <input type="text" name="search" placeholder="Search..." class="search" id="search">
  <div class="icon-container">
  <i onclick="submit1(); function submit1(){ const form = document.getElementById('search-form'); form.submit();} " id="search-icon" class="fa-solid fa-magnifying-glass fa-xl"></i>
  </div>
  </div>
</form>
    <?php if (isset($message)) { ?>

    <h3 style="text-align:center;font-size:20px;color:red;"><?php echo $message; ?></h3>
    <?php } ?>
    <div class="table-con">
    <table style="margin-top:3rem;">
    <?php while ($fileRow = $filesResult->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $fileRow['filename']; ?></td>
                <td>
                    <form method="POST" action="text_viewer.php">
                        <input type="hidden" name="file_id" value="<?php echo $fileRow['id']; ?>">
                        <input type="submit" value="View File" class="btn">
                    </form>
                    <form method="POST">
                        <input type="hidden" name="file_id" value="<?php echo $fileRow['id']; ?>">
                        <br><input type="text" name="new_filename" placeholder="New Filename" class="input">
                        <input type="submit" name="rename" value="Rename" class="btn">
                    </form>
                    <form method="POST">
                        <input type="hidden" name="file_id" value="<?php echo $fileRow['id']; ?>">
                        <br><input type="submit" name="delete" value="Delete" class="btn">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
    </div>
    <form id="delete" method="POST">
      <div class="delete-all">
      <button id="delete_btn" onclick="sure()" name="" style="margin-top:1rem;" class="btn">Delete All</button>
      </div>

    </form>
</body>
</html>
