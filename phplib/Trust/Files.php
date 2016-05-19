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
   * @return string
   * array: a[filename][size/modTime/path]
   */
  function GetDirFiles($directory) {
    $file = array();
    $dh = opendir($directory);
    while ($filename = readdir($dh)) {
      if (($filename != ".") && ($filename != "..")) {
        if (is_dir($directory . "/" . $filename)) {
          continue;
        } else {
          $file[$filename] = array();
          $file[$filename]['size'] = round(filesize($directory . "/" . $filename) / 1024);
          $file[$filename]['modTime'] = filemtime($directory . "/" . $filename);
          $file[$filename]['path'] = $directory . "/" . $filename;
        }
      }
    }
    return $file;
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
}
