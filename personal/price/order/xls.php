<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Tstn\Core\CatalogHelper\ElementRepository;
use Tstn\Core\Core;

GLOBAL $USER;

$str = strip_tags( str_replace('<sup>', '.', $_POST["HTML"]) );
$str = preg_replace("/\s*\r+/", "", $str);
$str = explode("\n", $str);

function trim_value(&$value)
{
	$value = trim($value);
}

//array_walk($str, 'trim_value');

//array_shift($str);
//array_pop($str);
$data = array();
foreach ($str as $item) {
	if ($item == "Артикул" || $item == "Название" || $item == "Ед.изм." || $item == "Цена, шт." || $item == "Количество" || $item == "Кол-во" || $item == ",") {
		unset($item);
	} else {
		$data[] = $item;
	}
}
$headers = array("Артикул", "Количество", "Наименование", "Цена, шт.");
function array_combine2($arr1, $arr2)
{
	$count1 = count($arr1);
	$count2 = count($arr2);
	$numofloops = $count2 / $count1;

	$i = 0;
	while ($i < $numofloops) {
		$arr3 = array_slice($arr2, $count1 * $i, $count1);
		$arr4[] = array_combine($arr1, $arr3);
		$i++;
	}

	return $arr4;
}

$combined = array_combine2($headers, $data);
$fileName = $USER->GetID() . "_KpPrise_" . date("d-m-Y H-i");
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$active_sheet = $objPHPExcel->getActiveSheet();
$sheet = $objPHPExcel->getActiveSheet();
$sheet->setTitle($fileName);
$headers = array("Артикул", "Количество", "Наименование", "Цена, шт.");
$charNumber = ord('A');

foreach ($headers as $item) {
	$sheet->setCellValue(chr($charNumber) . '1', $item);
	$charNumber++;
}

$row_start = 2;
$i = 0;
foreach ($combined as $item) {
	$row_next = $row_start + $i;

	$active_sheet->setCellValue('A' . $row_next, $item['Артикул']);
	$active_sheet->setCellValue('B' . $row_next, $item['Количество']);
	$active_sheet->setCellValue('C' . $row_next, $item['Наименование']);
	$active_sheet->setCellValue('D' . $row_next, str_replace(' ', '', $item['Цена, шт.']));

	$i++;
}

foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
	$objPHPExcel->getActiveSheet()
		->getColumnDimension($col)
		->setAutoSize(true);
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $fileName . '.xlsx');
$pathRel = '/upload/download_prices_tmp/' . $fileName . '.xlsx';
$pathFull = $_SERVER['DOCUMENT_ROOT'] . $pathRel;
$objWriter->save($pathFull);

?>
<script>
    var src = '<?=$pathRel?>';
    window.location.href = '/personal/price/order/downloader.php?path=' + src;
</script>
