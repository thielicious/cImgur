<?php require_once("./cImgur.class.php");  ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thielicious @Github - cImgur Demo</title>
		<link rel="stylesheet" href="style.css">
    </head>
	<body>
		<h2>cImgur Demo</h2>
		<p><small>cImgur v1.01 | &copy; 2017 | Thielicious | https://thielicious.github.io</small></p><br>
		
		<form action="<?php $_SERVER["PHP_SELF"] ?>" method="POST" enctype="multipart/form-data">
			<label>Choose an image to upload</label><br>
			<input type="file" name="browse">
			<input type="submit" name="upload" value="Upload">
		</form>
		
		<?php
		
			$imgur = new cImgur("b9ec36b6c896798"); // <--- enter your own Client-ID by registering at https://api.imgur.com/oauth2/addclient
			$imgur->setUploadSize(150000);
		
			if (isset($_POST["upload"])) {
				if (isset($_FILES["browse"])) {	
				
					try {
						$imgur->upload($_FILES["browse"]);
						$response = $imgur->data(cImgur::RETURN_OBJECT);
						echo "<p><span style=color:green>Upload succeeded: <span></p>
								<img src='".$response->link."'>						
								<p>Link: <a href=".$response->link.">".$response->link."</a></p>";
					} catch (Exception $e) {
						foreach ($imgur->getErrors() as $err) {
							echo "<p><span style=color:red>".$err."<span></p>";
						}
					}
					
				}
				
			} else {
				echo "<br>Please upload an image.";
			}
			
		?>
	</bod>
</htm>