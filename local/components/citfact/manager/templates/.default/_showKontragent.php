<? require '_filters.php';?>
<? foreach ($kontragents as $id =>$kontragent) {?>
    <?php require $_SERVER['DOCUMENT_ROOT'] . $this->__template->__folder . '/_kontragent.php'?>
<? } ?>