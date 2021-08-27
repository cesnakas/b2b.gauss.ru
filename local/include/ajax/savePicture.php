<?
$copyRes = false;
if(isset($_FILES['img']) && isset($_FILES['img']['tmp_name']) && !empty($_FILES['img']['tmp_name'])){
    $dir = substr(md5($_FILES['img']['tmp_name']), 0, 3);
    if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/upload/form/' . $dir)){
        $res = mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/form/' . $dir);
        if($res){
            $localPath = '/upload/form/' . $dir . '/' . date('dmYHis') . $_FILES['img']['name'];
            $copyRes = copy($_FILES['img']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $localPath);
        }
    }
}
if($copyRes){
    echo $localPath;
}