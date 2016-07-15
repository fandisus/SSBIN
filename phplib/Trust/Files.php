<?php
namespace Trust;
class Files {
  /**
   * To force file download to files with txt, pdf, jpeg extensions.
   * Warning: high security risk
   * @param string $filename The file to be downloaded
   * @param mixed $extensions The disallowed file extensions
   * @throws Exception
   * @return void This function will stop the code flow and force download of file
   */
  static function DownloadFile($filename, $disallow_exts = array()) {
    $hasil = new \stdClass();
    if (!file_exists($filename)) throw new Exception ("File tidak ditemukan");
    if (count($disallow_exts) > 0) {
        $info = pathinfo($filename);
        if (in_array($info['extension'], $disallow_exts)) throw new Exception("File tidak dapat diakses");
    }
    header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
    header("Content-type: application/octet-stream");
    // or header("Content-Type: application/force-download");
    header("Content-Length: " . filesize($filename));
    header("Connection: close");
    readfile($filename);
    die();
  }

  /**
   * Scan files informations in a directory.
   * @param type $directory The directory to be scanned
   * @return arrayofobj a[filename][size/modTime/path]
   */
  static function GetDirFiles($directory, $isObj=false) { //recursive, 
    $files = array();
    $dh = opendir($directory);
    while ($filename = readdir($dh)) {
      if (($filename != ".") && ($filename != "..")) {
        $path = "$directory/$filename";
        if (is_dir($path)) {
          $files[$path] = Files::GetDirFiles($path, $isObj);
        } else {
          if ($isObj) {
            $o = new \stdClass();
            $o->size = round(filesize($path) / 1024);
            $o->modTime = filemtime($path);
            $o->filename = $filename;
            $o->path = $path;
            $files[]=$o;
          } else {
            $files[] = $path;
          }
        }
      }
    }
    return $files;
  }

  static function Encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
  }

  /**
   * Returns decrypted original string
   */
  static function Decrypt($encrypted_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return $decrypted_string;
  }
  static function checkUpload($upload) {
    $errMsgs = [
        UPLOAD_ERR_INI_SIZE=>"File size too large",
        UPLOAD_ERR_FORM_SIZE=>"File size too large",
        UPLOAD_ERR_PARTIAL=>"Upload interrupted",
        UPLOAD_ERR_NO_FILE=>"File not found"
    ];
    if (!isset($upload)) return "Upload not found";
    $errorCode = $upload['error'];
    if ($errorCode != 0) return $errMsgs[$errorCode];
  }
}
