<?php
/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <gohr@cosmocode.de>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',dirname(__FILE__).'/../../../');
define('NOSESSION',true);
require_once(DOKU_INC.'inc/init.php');

// let the syntax plugin do the work
$cache = getcachename($_GET['img'], '.mathpublish.png');
if(!file_exists($cache)) _fail();

header('Content-Type: image/png;');
header('Expires: '.gmdate("D, d M Y H:i:s", time()+max($conf['cachetime'], 3600)).' GMT');
header('Cache-Control: public, proxy-revalidate, no-transform, max-age='.max($conf['cachetime'], 3600));
header('Pragma: public');
http_conditionalRequest($time);
if (http_sendfile($cache)) exit;
$fp = @fopen($cache,"rb");
if($fp){
    http_rangeRequest($fp,filesize($cache),'image/png');
}else{
    header("HTTP/1.0 500 Internal Server Error");
    print "Could not read file - bad permissions?";
}


function _fail(){
    header("HTTP/1.0 404 Not Found");
    header('Content-Type: image/png');
    echo io_readFile('broken.png',false);
    exit;
}

