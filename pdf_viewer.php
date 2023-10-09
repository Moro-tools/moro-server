<?php 

include"server/db_conn.php";
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}

$user_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? $_GET['search'] : '';

if(isset($_POST['view'])){
  $fileId = $_POST['file_id'];
  $query = "SELECT file_path FROM files WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $fileId);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    $stmt->bind_result($path);
    $stmt->fetch();
    header("Location:" . $path);
  }
  
}
if (isset($_POST['delete'])) {
  $fileId = $_POST['file_id'];

  // Fetch the file name from the database
  $query = "SELECT filename FROM files WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $fileId);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
      $stmt->bind_result($fileName);
      $stmt->fetch();

      // Delete the file record from the database
      $deleteQuery = "DELETE FROM files WHERE id = ?";
      $stmt = $conn->prepare($deleteQuery);
      $stmt->bind_param("i", $fileId);

      if ($stmt->execute()) {
          // Delete the file from the server
          $filePath = "uploads/" . $fileName;
          if (file_exists($filePath)) {
              unlink($filePath);
          }
          $message = "File deleted successfully.";
      } else {
          $message = "Error deleting file: " . $conn->error;
      }
  } else {
      $message = "File not found.";
      $stmt->close();
  }
}

$query = "SELECT id, user_id, filename, file_type, file_size, file_path FROM files WHERE file_type = 'application/pdf' AND user_id = $user_id";
if (!empty($search)) {
  // Add a condition to search for filenames containing the search keyword
  $query .= " AND (filename LIKE '%$search%')";
}
$filesResult = $conn->query($query);
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pdf viewer</title>
    <link rel="stylesheet" href="css/pdf-viewer.css">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="js/dashboard.js" defer></script>
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
<h1>PDF Viewer</h1>
<form id="search-form" action="pdf_viewer.php" method="GET">
  <div class="search-container" style="display: flex;justify-content: center;">
  <input type="text" name="search" placeholder="Search..." class="search" id="search">
  <div class="icon-container">
  <i onclick="submit1(); function submit1(){ const form = document.getElementById('search-form'); form.submit();} " id="search-icon" class="fa-solid fa-magnifying-glass fa-xl"></i>
  </div>
</div>
</form>
<table style="margin-left:30rem;margin-top:3rem;width:60%;padding:0rem;border:#f37702 5px solid">
    <tr>
        <th>PDF Name</th>
        <th>Actions</th>
    </tr>
    <?php while ($fileRow = $filesResult->fetch_assoc()) { ?>
        <tr>
            <td><?php echo '<div style="text-align:center;">' . $fileRow['filename'] . "</div>"; ?></td>
            <td>
                <form method="POST" action="pdf_viewer.php">
                    <input type="hidden" name="file_id" value="<?php echo $fileRow['id']; ?>">
                    <div style="  display: flex;justify-content: center;align-items: center;height: auto;">
                    <input type="submit" name="delete" value="Delete" class="btn" style="margin-left:0rem" >
                    <input type="submit" name="view" value="View" class="btn" style="margin-left:2rem" >
                    </div>
                </form>
            </td>
        </tr>
    <?php } ?> 
</table>
</body>
</html>