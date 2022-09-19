<?php


require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;



function exportExcel($header = array(), $data = array(), $fileName = '', $savePath = '/', $isDown = false)
{

  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();


  $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');



  $_row = 1;   //设置纵向单元格标识
  if ($header) {
    $_cnt = count($header);

    // $sheet->mergeCells('A' . $_row . ':' . $cellName[$_cnt - 1] . $_row);
    // $sheet->setCellValue('A' . $_row, '数据导出：' . date('Y-m-d H:i:s'));
    // $_row++;

    $i = 0;
    foreach ($header as $v) {   //设置列标题
      $sheet->setCellValue($cellName[$i] . $_row, $v);
      $i++;
    }
    $_row++;

    if ($data) {
      $i = 0;
      foreach ($data as $_v) {
        $j = 0;
        foreach ($_v as $_cell) {
          $sheet->setCellValue($cellName[$j] . ($i + $_row), $_cell);
          $j++;
        }
        $i++;
      }
    }
  }

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

  if ($isDown) {
    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="01simple.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer->save('php://output');
    exit;
  }
  $_fileName = $fileName; //iconv("utf-8", "gb2312", $fileName);   //转码
  $_savePath = $savePath . $_fileName . '.xlsx';
  $writer->save($_savePath);
  return $savePath . $fileName . '.xlsx';
}
