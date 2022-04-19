
<?php
$partId = isset($_GET['partId']) ? $_GET['partId'] : "";
$submit = isset($_GET['submit']) ? $_GET['submit'] : "";
if($partId>0){
 if($submit==""){
	?>
	<html>
	<body>

	<form action="upload.php?partId=<?php echo $partId; ?>&submit=1" method="post" enctype="multipart/form-data">
		Select image to upload:
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Upload Image" name="submit">
	</form>

	</body>
	</html>

	<?php
 }
 else
 {
	$target_dir = "../../Document Management System/relatedDrawing/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	//$temp = explode(".",$_FILES["fileToUpload"]["name"]);
	//$newfilename = 'yow.' .end($temp);
	//$target_file = $target_dir . $newfilename;

	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) 
		{
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) 
			{
				//echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else 
			{			
				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "pdf" ) {
					//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 0;
					header('location: checkDrawingStatus.php?partId='.$partId.'&error=Sorry, your file was not uploaded.file not allowed.');
				}
				//echo "File is not an image.";   //$uploadOk = 0;
			}
		}
	// Check if file already exists
		if (file_exists($target_file)) { /*echo "Sorry, file already exists.";*/    $uploadOk = 0; header('location: checkDrawingStatus.php?partId='.$partId.'&error=Sorry, your file was not uploaded.file already exists.'); }
	// Check file size
		if ($_FILES["fileToUpload"]["size"] > 6000000) { /*echo "Sorry, your file is too large.";*/ $uploadOk = 0; header('location: checkDrawingStatus.php?partId='.$partId.'&error=Sorry, your file was not uploaded.file is too large.'); }

	// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {  }
	 
	else 
	{
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
		{ 
			//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded."; 
			header('location: checkDrawingStatus.php?partId='.$partId.'&error=The file has been uploaded.');
		} 
		else { header('location: checkDrawingStatus.php?partId='.$partId.'&error=Sorry, there was an error uploading your file.');}
		
	}

	//convert it to png
	$imagick = new imagick();

	$imagick->setResolution(200,200);
	$imagick->setCompressionQuality(50); 
	$imagick->readImage($target_file);
	$countPage = $imagick->getNumberImages();
	$imagick->setImageFormat('pdf'); 
	$imagick->writeImage($target_dir.$partId.'.pdf');
	unlink($target_file);
 }
}
?>
