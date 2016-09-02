<?php
/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <gohr@cosmocode.de>
 */

if(!defined('DOKU_INC')) define('DOKU_INC', dirname(__FILE__) . '/../../../');
define('NOSESSION', true);
require_once(DOKU_INC . 'inc/init.php');
global $conf;

$cache = getCacheName($_GET['img'], '.mathpublish.png');
if(!file_exists($cache)) _fail();
$time = filemtime($cache);

header('Content-Type: image/png;');
header('Expires: ' . gmdate("D, d M Y H:i:s", time() + max($conf['cachetime'], 3600)) . ' GMT');
header('Cache-Control: public, proxy-revalidate, no-transform, max-age=' . max($conf['cachetime'], 3600));
header('Pragma: public');
http_conditionalRequest($time);
http_sendfile($cache); // exits if x-sendfile support
$fp = @fopen($cache, "rb");
if($fp) {
    http_rangeRequest($fp, filesize($cache), 'image/png');
} else {
    http_status(500);
    print 'Could not read file - bad permissions?';
}

function _fail() {
    http_status(404);
    header('Content-Type: image/png');
    echo io_readFile(__DIR__ . '/broken.png', false);
    exit;
}

