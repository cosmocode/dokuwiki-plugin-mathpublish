<?php
/**
 * Math Plugin: incorporate mathematical formulae using MathPublisher into Dokuwiki
 *
 * Syntax:     <m size>...mathematical formula..</m>
 *   size      (optional) base glyph size in pixels,
 *             if not present will use the value of $mathplugin_size global, the value
 *             of which can be set below (default: 12)
 *
 * Formulae syntax:  refer http://www.xm1math.net/phpmathpublisher/doc/help.html
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Christopher Smith <chris@jalakai.co.uk>
 * @date       2005-12-17
 *
 * phpmathpublisher
 * @link       http://www.xm1math.net/phpmathpublisher/
 * @author     Pascal Brachet
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_mathpublish extends DokuWiki_Syntax_Plugin {

    // FIXME localise
    var $enable = false;
    var $msg_sent = false;

    public function __construct() {
        $this->enable = $this->_requirements_ok();
    }

    public function getType(){ return 'protected'; } #FIXME why not substition?
    public function getPType(){ return 'normal'; }
    public function getSort(){ return 208; }

    /**
     * Connect pattern to lexer
     */
    public function connectTo($mode) {
        $this->Lexer->addEntryPattern('<m(?=[^\r\n]*?>.*?</m>)',$mode,'plugin_mathpublish');
    }

    public function postConnect() {
        $this->Lexer->addExitPattern('</m>','plugin_mathpublish');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, &$handler){
        if ( $state == DOKU_LEXER_UNMATCHED ) {
            list($size, $math) = preg_split('/>/u', $match, 2);   // will split into size & math formulae
            if (!is_numeric($size)) $size = 12; // default size in pixels

            if (strlen($math) > 1) {
                $c_first = $math{0};
                $c_last = $math{strlen($math)-1};

                if($c_first == ' '){
                    if($c_last == ' '){
                        $align = 'center';
                    }else{
                        $align = 'right';
                    }
                }else{
                    if($c_last == ' '){
                        $align = 'left';
                    }else{
                        $align ='normal';
                    }
                };
            } else {
                $align = 'normal';
            }

            return (array($size, trim($math), $align));
        }
        return false;
    }

    /**
     * Create output
     */
    function render($mode, &$R, $data) {
        if(!$data)           return; // skip rendering for the enter and exit patterns #FIXME
        if(!$this->enable)   return;
        if($mode != 'xhtml') return;

        list($size, $math, $align) = $data;
        $ident = md5($math.'-'.$size);


        // check if we have a cached version available
        $valignfile = getcachename($ident, '.mathpublish.valign');
        if(file_exists($valignfile)){
            $valign = (int) io_readFile($valignfile);
        }else{
            $imagefile = getcachename($ident, '.mathpublish.png');
            require_once(dirname(__FILE__).'/phpmathpublisher/mathpublisher.php');
            $pmp    = new phpmathpublisher();
            $valign = $pmp->renderImage($math,$size,$imagefile);
            io_saveFile($valignfile,$valign);
        }

        // output aligned image
        $R->doc .= '<img src="'.DOKU_BASE.'lib/plugins/mathpublish/img.php?img='.$ident.'"
                         class="media'.$align.' mathpublish"
                         alt="'.hsc($math).'"
                         title="'.hsc($math).'"
                         style="display: inline-block; vertical-align:'.$valign.'px" />';

        return false;
    }


    /**
     * check if php installation has required libraries/functions
     */
    private function _requirements_ok() {
        if (!function_exists('imagepng')) {
            $this->msg($this->getLang('nopng'),-1);
            return false;
        }

        if (!function_exists('imagettftext')) {
            $this->msg($this->getLang('noft'),-1);
            return false;
        }

        return true;
    }

    /**
     * used to avoid multiple messages
     */
    private function _msg($str, $lvl=0) {
        if ($this->msg_sent) return;

        msg($str, $lvl);
        $this->msg_sent = true;
    }

    // would like to see this function in io.php :)
    function _mkdir($d) {
        global $conf;

        umask($conf['dmask']);
        $ok = io_mkdir_p($d);
        umask($conf['umask']);
        return $ok;
    }

}

