<?php
 
	/*

		cImgur v1.0
		(c) 2017 by Thielicious
		
		cImgur (abbr: c = custom) is a customized imgur remote uploader using cURL 
		and imgur API endpoints. Made for anonymous execution.

	*/



	class cImgur {

		public
			$clientID = null,
			$debug = 0;

		private 
			$imgWidth,	$imgHeight,
			$uploadSize, $errors = [],
			$data;

		const 
			RETURN_JSON = 1,
			RETURN_ARRAY = 2,
			RETURN_OBJECT = 3;
		
		public function __construct(string $cid = null) {
			if ($cid != null) {
				$this->clientID = $cid;
			} 
		}	

		public function clientID(string $cid) { 
			$this->clientID = $cid; 
		}

		public function debug() {
			$this->debug = 1;
		}

		private static function error(array $file) { 
			return $file["error"] !== 0 ? 
				true : false; 
		}

		public function getErrors() { 
			if (count($this->errors) > 0) {
				return $this->errors; 
			}
		}

		public function setUploadSize(int $usize) { 
			$this->uploadSize = $usize; 
		}

		public function setImageSize(int $width, int $height) { 
			$this->imgWidth = $width;
			$this->imgHeight = $height; 
		}

		private static function checkMIME(array $file) {
			$filetype = explode("/",mime_content_type($file["tmp_name"]));
			return $filetype[0] !== "image" ?
				false : true;
		}

		private function connectToImgur(array $file) {
			$image = @file_get_contents($file["tmp_name"]);
			$curl = curl_init();
			curl_setopt_array(
				$curl, [
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_URL => "https://api.imgur.com/3/image.json",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => [
						"image" => base64_encode($image)
					],
					CURLOPT_HTTPHEADER => [
						"Authorization: Client-ID ".$this->clientID
					]
				]
			);
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
				$this->errors[] = "Upload Error: ".$err;
			} else {
				if (@count(json_decode($response)->data->error,true) > 0) {
					$this->errors[] = "Sorry, this image file does not meet the required standards at imgur.com to be uploaded. <br> Upload denied.";
				}
				$this->data = $response;
			}
			return true;
		}

		public function data($type = null) {
			if ($type == self::RETURN_JSON || null) {
				return $this->data; 
			} elseif ($type == self::RETURN_OBJECT) {
				$data = json_decode($this->data);
				return $data->data; 
			} elseif ($type == self::RETURN_ARRAY) {
				$data = json_decode($this->data,true);
				return $data["data"];	
			}
		}

		public function upload(array $file) {
			if ($this->debug == 1) {
				echo "<strong>DEBUG:</strong><br>
					<pre>".print_r($file,true)."</pre>";
				$this->connectToImgur($file);
				echo "<pre>".print_r(json_decode($this->data)->data,true)."</pre>";
				exit;
			} elseif ($file["error"] != 4) {
				if (static::error($file) == false) {
					if (strlen($this->uploadSize) != 0) {
						if ($file["size"] <= $this->uploadSize) {
							if (static::checkMIME($file) == true) {
								$size = getimagesize($file["tmp_name"]);
								if (strlen($this->imgWidth && $this->imgHeight) != 0) {
									if ($size[0] == $this->imgWidth && $size[1] == $this->imgHeight) {
										$this->connectToImgur($file);
									} else {
										$this->errors[] = "Image size does not match (allowed: ".$this->imgWidth."x".$this->imgHeight." pixel)";
									}
								} else {
									$this->connectToImgur($file);
								}
							} else {
								$this->errors[] = "File is not an image.";
							}
						} else {
							$this->errors[] = "Image is too large. (max ".floor(($this->uploadSize/1024))." KB)";
						}
					} else {
						$this->errors[] = "Upload size not set.";
					}
				} else {
					$this->errors[] = "File error :( Could be incorrectly formatted or is damaged.";
				}		
			} else {
				$this->errors[] = die("<span style=color:crimson>[!] No image selected.</span>");
			}
			if (count($this->errors) > 0) {
				throw new Exception();
			} else {
				return true;
			}
		}

	}

?>
