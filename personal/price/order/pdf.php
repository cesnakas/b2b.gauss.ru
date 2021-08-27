<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Dompdf\Dompdf;
use Dompdf\Options;
use Bitrix\Main\Loader;

Loader::includeModule('citfact.tools');

global $USER;
$html = '<html>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <style>
    .print-page {
            padding-top: 50px;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            font-family: DejaVu Sans;
        }
    </style>
    <body>    <table class="print-page">
' . $_POST["HTML"] . '</table></body></html>';
$options = new Options();
$options->set('defaultFont', 'times');
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

$output = $dompdf->output();

$pathRel = '/upload/download_prices_tmp/' . $USER->GetID() . '_kp_price_' . date('d.m.y') . '.pdf';
$pathFull = $_SERVER['DOCUMENT_ROOT'] . $pathRel;
file_put_contents($pathFull, $output);

?>
<script>
    var src = '<?=$pathRel?>';
    window.location.href = '/personal/price/order/downloader.php?path=' + src;
</script>