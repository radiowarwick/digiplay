<?php
require_once("pre.php");

$upload_handler = new UploadHandler();


header('Pragma: no-cache');
header('Cache-Control: private, no-cache');
header('Content-Disposition: inline; filename="files.json"');
header('X-Content-Type-Options: nosniff');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'OPTIONS':
       	break;
   	case 'HEAD':
   	case 'GET':
        $upload_handler->get();
       	break;
   	case 'POST':
        $upload_handler->post();
       	break;
   	case 'DELETE':
        $upload_handler->delete();
       	break;
   	default:
   	    header('HTTP/1.1 405 Method Not Allowed');
}
?>