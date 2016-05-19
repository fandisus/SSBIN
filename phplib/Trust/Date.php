<?php
namespace Trust;
class Date {
  /**
   * Checks if the specified string is a valid date.
   * @param string $theDate The date string to be checked
   * @return boolean
   */
//  static function isValidDate($theDate) {
//      $test = date_parse($theDate);
//      if (count($test['errors']) || count($test['warnings'])) return false;
//      if (!$test['year'] || !$test['month'] || !$test['day']) return false;
//      if (!checkdate($test['month'], $test['day'], $test['year'])) return false;
//      return true;
//  }
  /**
   * Check if the specified string is a valid javascript datetime string.
   * Might be a problem for non US computers
   * @param string $theDate The date string to be checked
   * @return boolean
   */
  static function isJavaDate($theDate) {
    if (\DateTime::createFromFormat("D M d Y H:i:s \G\M\TO +", $theDate)) return true;
    return false;
  }
  static function firstDayOfMonth() { return Date('m-01-Y'); }
  static function lastDayOfMonth() { return Date('m-t-Y'); }
  static function fromJavascript($strDate) { //http://stackoverflow.com/questions/24258876/convert-javascript-datetime-into-php-datetime
    //Warning: This also converts the date timezone to server timezone.
    //contoh: client: 7AM GMT+7, server: UTC Timezone, result: 0AM UTC  (PHP does not save the UTC part, just 0AM)
    //solusi: date_default_timezone_set("Asia/Jakarta");
    return strtotime(substr($strDate,0,strpos($strDate,"(")));
  }
  //Might be a problem for non US 
  static function toJavascript($timestamp) { return date("D M d Y H:i:s \G\M\TO",$timestamp); }
  static function toSqlDateTime($timestamp) { return date("Y-m-d H:i:s",$timestamp); }
  static function toSqlDate($timestamp) { return date("Y-m-d",$timestamp); }

  static function fromJavascriptToSQLDate($strDate) { return Date::toSqlDate(Date::fromJavascript($strDate)); }
  static function fromJavascriptToSQLDateTime($strDate) { return Date::toSqlDateTime(Date::fromJavascript($strDate)); }
}
?>
