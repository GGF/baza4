<?

require $_SERVER["DOCUMENT_ROOT"] . "/lib/config.php";
$_SERVER["debug"] = false;
require $_SERVER["DOCUMENT_ROOT"] . "/lib/core.php";


// перекодируем полученые данные 
// (используются функции из multibyte.php, потому 
// здесь, а не в encoding.php вызываем)
// TODO: А нужно ли здесь? Запретил регистрацию глобальных,
//  а пост и гет тут всё равно регистрирую
foreach ($_GET as $key => $val) {
    ${$key} = cmsUTF_decode($val);
    // она сама и массивы перекодирует и проверяет на utf
}
foreach ($_POST as $key => $val) {
    ${$key} = cmsUTF_decode($val);
    // она сама и массивы перекодирует и проверяет на utf
}

// заказчик по tzid
$sql = "SELECT orders.customer_id AS id FROM tz JOIN (orders) ON (tz.order_id=orders.id) WHERE tz.id='{$tznumber}'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
    echo -1;
    exit;
}
$customer_id = $rs[id];
// плату
// коментарий
$sql = "SELECT id FROM boards WHERE customer_id='$customer_id' AND board_name='$board'";
$rs = sql::fetchOne($sql);
if (empty($comment)) {
    $comment_id = $rs[comment_id];
} else {
    $sql = "SELECT * FROM coments WHERE comment='{$comment}'";
    $com = sql::fetchOne($sql);
    if (empty($com)) {
        $sql = "INSERT INTO coments (comment) VALUES ('{$comment}')";
        sql::query($sql);
        $comment_id = sql::lastId();
    } else {
        $comment_id = $com[id];
    }
}
$sql = "REPLACE INTO boards 
        (id,board_name,customer_id,sizex,sizey,thickness,
        textolite,textolitepsi,thick_tol,rmark,frezcorner,layers,razr,
        pallad,immer,aurum,numlam,lsizex,lsizey,mask,mark,glasscloth,
        class,complexity_factor,frez_factor,comment_id)
        VALUES ('{$rs["id"]}' , '{$board}' ,'{$customer_id}' ,'{$sizex}' ,'{$sizey}' ,
        '{$thickness}' ,'{$textolite}' ,'{$textolitepsi}' ,'{$thick_tol}' ,
        '{$rmark}' ,'{$frezcorner}' ,'{$layers}' ,'{$razr}' ,'{$pallad}' ,'{$immer}' ,
        '{$aurum}' ,'{$numlam}' ,'{$lsizex}' ,'{$lsizey}' ,'{$mask}' ,'{$mark}' ,
        '{$glasscloth}' ,'{$class}' ,'{$complexity_factor}' ,'{$frez_factor}','{$comment_id}')";
sql::query($sql);

$plate_id = sql::lastId();

// позицию к блоку
$sql = "INSERT INTO blockpos (block_id,board_id,nib,nx,ny) VALUES ('$block_id','$plate_id','$num','$bnx','$bny')";
sql::query($sql);

echo $plate_id;
?>