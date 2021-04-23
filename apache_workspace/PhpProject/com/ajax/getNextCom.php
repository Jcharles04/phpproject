<?php



require '__app.php';

require_once __APPDIR__ . '/Database/db.php';
require_once __DIR__ . '/../component/modcom.php';
require_once __APPDIR__ . '/com/component/rendercom.php';


$comId = $_GET['comId'];

if (!$comId) {
    http_response_code(400);
    die();
} else {
    $comments = getNextCom($comId);
    foreach ($comments as $comment) {
        renderComment($comment);
    }
}
?>