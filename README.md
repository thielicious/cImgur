# cImgur
##### Simple image uploader to imgur.com packed in a class
---

<br>

## INTRODUCTION

cImgur (abbr: c = custom) is a customized imgur remote uploader using cURL and imgur API endpoints. Made for anonymous execution, single images only.
<br>

## SETUP INFORMATION

Use your CLI and enter the following to clone:<br>
`git clone https://github.com/thielicious/cImgur.git`

<br>

## USAGE

Create an object :
```
$imgur = new cImgur("<CLIENT-ID>");
$imgur->setUploadSize(<UPLOAD MAX SIZE>);
```

After a form has been submitted, use this boilerplate:<br>
```
try {
  $imgur->upload($_FILES["upload"]);
  $response = $imgur->data(cImgur::RETURN_OBJECT);
  echo $response->link;
} catch (Exception $e) {
  foreach ($imgur->getErrors() as $err) {
    echo $err;
  }
}
```
This example above spits out the direct URL to the uploaded image at imgur.com. You may use this as a PRG pattern or whatever you like.

<br>

## METHODS

**cImgur::__construct(string $clientID = NULL)** (required)
* (optional) defines the CLIENT-ID for authorization. If you don't have a Client-ID, then visit https://api.imgur.com/oauth2/addclient to register and copy the ID. You may leave this parameter blank and add later your ID with **cImgur::clientID(string $clientID)**.

**cImgur::setUploadSize(int $size)** (required)
* defines the maximum size of images.

**cImgur::setImageSize(int $width, int $height)** (optional)
* use this method to implicitly set the image's height and width. This will check if the selected image meets the requirement.

**cImgur::upload(array $file)** (required)
*  this will upload the file, if $_FILES is set.

**cImgur::data(const $return_type = NULL)** (required)
*  (optional) this method returns the image data object encoded in JSON once the upload process to imgur.com was successful. <br>
The following return types can be used: <br>
* > cIMGUR::RETURN_JSON (default) <br>
* > cIMGUR::RETURN_OBJECT (Std Class Object) <br>
* > cIMGUR::RETURN_ARRAY (Associative Array) <br>

**cImgur::getErrors()** (required)
*  through a try-catch block it might throw an exception when the upload has failed. You can check out the errors with this method, which returns an Array.

<br>
<br>

:new: A **[Demo](https://jsfiddle.net/Thielicious/4oxmsy49/)** has been added.

<br>

###### If you encounter any bugs, feel free to open up an **[issue](https://github.com/thielicious/selectFile.js/issues)**, thank you.

---
**[thielicious.github.io](http://thielicious.github.io)**
