<?php
namespace Trust;
class Image {
  static function IsImage($tempFile) {
    // Get the size of the image: 0:Width, 1:Height, 2:Type
    $size = getimagesize($tempFile);
    if (!isset($size))return false;
    if (!in_array($size[2],[IMAGETYPE_GIF,IMAGETYPE_PNG,IMAGETYPE_JPEG])) return false;
    if (!$size[0] || !$size[1]) return false;
    return true;
  }


//fungsi: $SourcePath, $maxWidth, $maxHeight, $targetPath
  static function GenerateThumb($sourcePath, $maxWidth, $maxHeight, $targetPath) {
    list($srcWidth, $srcHeight, $srcType) = getimagesize($sourcePath);
    switch($srcType) {
      case IMAGETYPE_GIF:  $readFunction = "imagecreatefromgif"; $writeFunction="imagegif"; break;
      case IMAGETYPE_JPEG: $readFunction = "imagecreatefromjpeg"; $writeFunction="imagejpeg"; break;
      case IMAGETYPE_PNG:  $readFunction = "imagecreatefrompng"; $writeFunction="imagepng"; break;
    }
    $srcImage = $readFunction($sourcePath);
    if ($srcImage === false) return false;
    if ($srcWidth<$maxWidth && $srcHeight<$maxHeight) {
      $newWidth = $srcWidth;
      $newHeight = $srcHeight;
    } elseif ($srcWidth/$maxWidth >= $srcHeight/$maxHeight) { //size limiter: width
      $newWidth = (int) $maxWidth;
      $newHeight = (int) ($maxWidth/$srcWidth * $srcHeight);
    } else { //size limiter: height
      $newHeight = (int) $maxHeight;
      $newWidth = (int) ($maxHeight/$srcHeight * $srcWidth);
    }
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
    if ($writeFunction == "imagegif") imagegif($newImage, $targetPath); 
    elseif ($writeFunction == "imagejpeg") imagejpeg($newImage, $targetPath, 80);
    elseif ($writeFunction == "imagepng") imagepng($newImage, $targetPath, 0);
    imagedestroy($srcImage);
    imagedestroy($newImage);
    return true;
  }

  static function CommonUploadErrors($key){
    $uploadErrors = [
      UPLOAD_ERR_INI_SIZE     => "File is larger than the specified amount set by the server",
      UPLOAD_ERR_FORM_SIZE    => "File is larger than the specified amount specified by browser",
      UPLOAD_ERR_PARTIAL      => "File could not be fully uploaded. Please try again later",
      UPLOAD_ERR_NO_FILE      => "File is not found",
      UPLOAD_ERR_NO_TMP_DIR   => "Can't write to disk, due to server configuration ( No tmp dir found )",
      UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk. Please check you file permissions",
      UPLOAD_ERR_EXTENSION    => "A PHP extension has halted this file upload process"
    ];
    return $uploadErrors[$key];
  }
  
  static function checkImageUpload($upload) {
    $errMsgs = [
        UPLOAD_ERR_INI_SIZE=>"Gambar yang diupload terlalu besar",
        UPLOAD_ERR_FORM_SIZE=>"Gambar yang diupload terlalu besar",
        UPLOAD_ERR_PARTIAL=>"Upload gambar terhenti di tengah jalan",
        UPLOAD_ERR_NO_FILE=>"Tidak ada gambar yang diupload"
    ];
    if (!isset($upload)) return "Gagal mengirim gambar";
    $errorCode = $upload['error'];
    if ($errorCode != 0) return ($errMsgs[$errorCode]);
    
    if (!Image::IsImage($upload['tmp_name'])) {
      unlink($upload['tmp_name']);
      return "Gambar yang diupload tidak dapat diproses.\nHanya tipe gif, jpg atau png yang diterima.";
    }
    
    return null;
  }
}
