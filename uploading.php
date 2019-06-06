<!DOCTYPE html>
<html>
<head>
	<title>Upload</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

	<link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>

<body>
  <ul id="top">
      <li class="topnav"><a href="projects.php">Home</a></li>
      <li class="topnav"><a href="myProjects.php">My Projects</a></li>
    
    <li class="topnav"><a class="active" href="uploading.php">Upload</a></li>
      <li  class="topnav" style="float:right"><a href="logout.php">LogOut</a></li>
      <li class="topnav" style="float:right"><a href="notification.php">Notification</a></li>
  </ul><br>

  <?php
    session_start();

  if(isset($_SESSION['user']))
    $user = $_SESSION['user'];
  else
    header("Location:login.php");

if(isset($_POST['submit']))
{
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "uplabs";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $user = $_SESSION['user'];
  $type = $_GET['type'];

do{
    $uni_id = rand(10,99);

    $first = $uni_id;
    $chars_to_do = 5 - strlen($uni_id);
    for ($i = 1; $i <= $chars_to_do; $i++){ 
        $first .= chr(rand(48,57)); 
    }

    $pid = $first;

    $sql1 = "SELECT * FROM project WHERE pid='".$pid."';";
    $result1 = $conn->query($sql1);
    
    $sql2 = "SELECT * FROM approval WHERE pid='".$pid."';";
    $result2 = $conn->query($sql2);

}while($result1->num_rows > 0 || $result2->num_rows > 0);

  if ($type == 'd') {

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$target_file_img = $target_dir . $pid . "." . $imageFileType;

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        echo "<center><h3>File is an image - " . $check["mime"] . ".</h3></center>";
        $uploadOk = 1;
    } else {
        echo "<center><h3>File is not an image.</h3></center>";
        $uploadOk = 0;
    }
}
// Check file size
if ($_FILES["file"]["size"] > 5242880) {
    echo "<center><h3>Sorry, your file is too large.</h3></center>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    echo "<center><h3>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</h3></center>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<center><h3>Sorry, your file was not uploaded.</h3></center>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file_img)) {

    } else {
        echo "<center><h3>Sorry, there was an error uploading your file.</h3></center>";
    }
}
  }

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["source"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$target_file = $target_dir . $pid . "." . $fileType;

// Check file size
if ($_FILES["source"]["size"] > 52428800) {
    echo "<center><h3>Sorry, your file is too large.</h3></center>";
    $uploadOk = 0;
}
// Allow certain file formats
if($fileType != "rar" && $fileType != "zip" && $fileType != "tar" && $fileType != "psd" ) {
    echo "<center><h3>Sorry, only RAR, ZIP, TAR & PSD files are allowed.</h3></center>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<center><h3>Sorry, your file was not uploaded.</h3></center>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["source"]["tmp_name"], $target_file)) {

        $sql = "INSERT INTO approval VALUES('".$user."','".$pid."','".$_POST['name']."','".$target_file_img."','".$_POST['city']."','".$_POST['tag']."','".$target_file."');";
        $result = $conn->query($sql);

        $_SESSION['msg']="Your file has been successfully uploaded.";
        header('location:myProfile.php');
        exit();

    } else {
        echo "<center><h3>Sorry, there was an error uploading your source file.</h3></center>";
    }
}
  
}
?>
    <div class="forms w3-border w3-round-large">
        <div class="w3-container w3-blue w3-round-large">
            <h2>Upload Form</h2>
        </div>

        <form class="w3-container w3-mobile" method='post' action='uploading.php?type=d' enctype="multipart/form-data">
        <p>
            <label>Design Image</label>
            <input class="w3-input" type="file" name='file' required />
            (*File size must be < 5MB) (Allowed formats are JPEG,JPG,PNG and GIF)
        </p><br>

        <p>
            <label>Design Name</label>
            <input class="w3-input" type="text" name='name' required />
        </p>
        
        <p>
            <label>Project Name</label>
            <input class="w3-input" type="text" name='project' required />
        </p>
  
        <p>
            <label>City</label>
            <input class="w3-input" type="text" name='city' required />
        </p>

        <p>
            <label>Tags</label>
            <input class="w3-input" type="text" name='tag' required />
            (Separate different tags using commas)
        </p>

        <p>
            <label>Source File</label>
            <input class="w3-input" type="file" name='source' required />
            (*File size must be < 50MB) (Allowed formats are ZIP,RAR,TAR and PSD)
        </p><br>

        <p>
            <input class="w3-button w3-large w3-blue w3-round-large" type="submit" name="submit" value="Upload">
        </p>
        
        </form>
    </div>

<!--
        <div id="videof">
        	<form method='post' action='upload.php?type=v' enctype="multipart/form-data">
        		Upload the Video : <input type="file" name="file" required/><br><br>
        		Video Name : <input type='text' name ='name' required/><br><br>
        		Project : <input type="text" name="project" required /><br><br>
        		City : <input type="text" name="city" required/><br><br>
        		Tag : <input type="text" name="tag" required/><br><br>
        		<input type="submit" name="submit" value="Upload">
        	</form>
        </div>

        <div id="imagef">
        	<form method='post' action='upload.php?type=i' enctype="multipart/form-data">
        		Upload the Image : <input type="file" name="file" /><br><br>
        		Image Name : <input type='text' name ='name' required/><br><br>
        		Project : <input type="text" name="project" /><br><br>
        		City : <input type="text" name="city" /><br><br>
        		Tag : <input type="text" name="tag" /><br><br>
        		<input type="submit" name="submit" value="Upload">
        	</form>
        </div>
-->



	<script type="text/javascript">
		document.getElementById("designf").style.display = "none";
		document.getElementById("videof").style.display = "none";
		document.getElementById("imagef").style.display = "none";

		function designf() {
			document.getElementById("designf").style.display = "block";

			document.getElementById("videof").style.display = "none";
			document.getElementById("imagef").style.display = "none";
		}

		function videof() {
			document.getElementById("designf").style.display = "none";

			document.getElementById("videof").style.display = "block";
			
			document.getElementById("imagef").style.display = "none";
		}

		function imagef() {
			document.getElementById("designf").style.display = "none";
			document.getElementById("videof").style.display = "none";
			
			document.getElementById("imagef").style.display = "block";
		}
	</script>

</body>
</html>