<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once './Classes/PHPExcel.php';


// Create new PHPExcel object
$exarr = array('A','B','C','D','E','F','G','H','I','J','K','L','M', 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM', 'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM', 'BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM', 'CN','CO','CP','CQ','CR','CS','CT','CU','CV');

$barr = array('机构名称','子账号','问卷编号','宝宝姓名','身份信息','身份类型','性别','出生年月日','完成问卷的时间','是否早产','体重（g）','孕周','孕周+天', '月龄','月龄+天','顺产','剖腹产','产钳助产','吸引器助产','双胞胎','多胎','完成问卷人姓名','关系','答题方式','手机','省','市','地区（县）','地址','邮政编码','电子邮箱','协助人的姓名或身份','母亲职业','父亲职业','母亲文化程度','母亲出生日期','父亲文化程度','父亲出生日期','主要照顾者文化程度', '问卷月龄组','CM-1','CM-2','CM-3','CM-4','CM-5','CM-6','沟通能区总分','沟通能区结果','GM-1','GM-2','GM-3','GM-4','GM-5','GM-6','粗大动作能区总分','粗大动作能区结果','FM-1','FM-2','FM-3','FM-4','FM-5','FM-6','精细动作能区总分','精细动作能区结果','CG-1', 'CG-2','CG-3','CG-4','CG-5','CG-6','解决问题能区总分','解决问题能区结果','PS-1','PS-2','PS-3','PS-4','PS-5','PS-6','个人-社会总分','个人-社会结果','综合问题1选项','综合问题1','综合问题2选项','综合问题2','综合问题3选项','综合问题3','综合问题4选项','综合问题4','综合问题5选项','综合问题5','综合问题6选项', '综合问题6','综合问题7选项','综合问题7','综合问题8选项','综合问题8','综合问题9选项','综合问题9','综合问题10选项','综合问题10');



$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


// Add some data

for($i=0;$i<count($exarr);$i++){
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($exarr[$i].'1', $barr[$i]);
}


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
