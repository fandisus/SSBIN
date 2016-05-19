<?php
define("SYSTEMNAME", "FORBIS");
namespace Trust;
class ExcelRowElement {
    public $col;
    public $row;
    public $val;
    public $colSpan;
    public $rowSpan;
    public function __construct($col, $row, $val, $colSpan=1, $rowSpan=1) {
        $this->col=$col; $this->row=$row; $this->val=$val; $this->colSpan=$colSpan; $this->rowSpan=$rowSpan;
    }
}

class Excel {
  static function ExcelFromData($title, $sheetName, $tableHeader, $rows, $filename="Data") {
    require(DIR."/jslib/excel/PHPExcel.php");
    $oExcel = new PHPExcel();
    $oExcel->getProperties()->setCreator(SYSTEMNAME)
                             ->setLastModifiedBy(SYSTEMNAME)
                             ->setTitle($title)
                             ->setSubject($title)
                             ->setDescription("");
    $oSheet = $oExcel->setActiveSheetIndex(0); $maxHeaderRow = $maxHeaderCol= 0;
    foreach($tableHeader as $v) {
      $oSheet->setCellValueByColumnAndRow($v->col, $v->row, $v->val);
      if ($v->colSpan > 1) {
        $oSheet->mergeCellsByColumnAndRow($v->col, $v->row, $v->col+$v->colSpan-1, $v->row);
      }
      if ($v->rowSpan > 1) {
        $oSheet->mergeCellsByColumnAndRow($v->col, $v->row, $v->col, $v->row+$v->rowSpan-1);
      }
      if ($v->row > $maxHeaderRow) $maxHeaderRow = $v->row;
      if ($v->col > $maxHeaderCol) $maxHeaderCol = $v->col;
    }
    $maxColName = PHPExcel_Cell::stringFromColumnIndex($maxHeaderCol);
    $rangeName = "A1:$maxColName$maxHeaderRow";

    $oSheet->getStyle($rangeName)->getFont()->setBold(true);
    $oSheet->getStyle($rangeName)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $oSheet->getStyle($rangeName)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $rowIdx = $maxHeaderRow;
    if(isset($rows) && is_array($rows)) {
      foreach($rows as $baris) {
        $colNo='A'; $rowIdx++;
        foreach($baris as $v) {
          $oSheet->setCellValue($colNo.$rowIdx,$v);
          $colNo++;
        }
      }
    }
    $colNo='A';
    for ($i=0; $i<=$maxHeaderCol; $i++) {
      $oSheet->getColumnDimensionByColumn($i)->setAutoSize(true);
    }
    $oSheet->setTitle($sheetName);
    // $oExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$filename.xlsx\"");
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
  }
}