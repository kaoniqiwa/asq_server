<?php

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once __DIR__ . '/../../src/Bootstrap.php';

$helper = new Sample();
if ($helper->isCli()) {
  $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

  return;
}
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
  ->setLastModifiedBy('Maarten Balliauw')
  ->setTitle('Office 2007 XLSX Test Document')
  ->setSubject('Office 2007 XLSX Test Document')
  ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
  ->setKeywords('office 2007 openxml php')
  ->setCategory('Test result file');

// Add some data
$spreadsheet->setActiveSheetIndex(0)
  ->setCellValue('A1', '1')
  ->setCellValue('B2', '2!')
  ->setCellValue('C1', '3')
  ->setCellValue('D2', '4!');

// Miscellaneous glyphs, UTF-8
$spreadsheet->setActiveSheetIndex(0)
  ->setCellValue('A4', 'n glyphs')
  ->setCellValue('A5', 'i');

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Simple');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
