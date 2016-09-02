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

if(!defined('DOKU_INC')) define('DOKU_INC', realpath(dirname(__FILE__) . '/../../') . '/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(DOKU_PLUGIN . 'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_mathpublish extends DokuWiki_Syntax_Plugin {

    protected $enable = false;
    protected $msg_sent = false;

    /**
     * syntax_plugin_mathpublish constructor.
     */
    public function __construct() {
        $this->enable = $this->_requirements_ok();
    }

    /**
     * Syntax Type
     *
     * Needs to return one of the mode types defined in $PARSER_MODES in parser.php
     *
     * @return string
     */
    public function getType() {
        return 'substition';
    }

    /**
     * Paragraph Type
     *
     * Defines how this syntax is handled regarding paragraphs. This is important
     * for correct XHTML nesting. Should return one of the following:
     *
     * 'normal' - The plugin can be used inside paragraphs
     * 'block'  - Open paragraphs need to be closed before plugin output
     * 'stack'  - Special case. Plugin wraps other paragraphs.
     *
     * @see Doku_Handler_Block
     *
     * @return string
     */
    public function getPType() {
        return 'normal';
    }

    /**
     * Sort for applying this mode
     *
     * @return int
     */
    public function getSort() {
        return 208;
    }

    /**
     * Connect pattern to lexer
     * @param string $mode
     */
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<m.*?>.*?(?:</m>)', $mode, 'plugin_mathpublish');
    }

    /**
     * Handle the match
     * @param string $match
     * @param int $state
     * @param int $pos
     * @param Doku_Handler $handler
     * @return array
     */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        $match = substr($match, 2, -4); // strip '<m' and '</m>'
        list($size, $math) = explode('>', $match, 2);
        if(!$math) {
            $math = $size;
            $size = '';
        }
        $size = (int) trim($size);
        if(!$size) $size = 12;

        if(strlen($math) > 1) {
            $c_first = $math{0};
            $c_last = $math{strlen($math) - 1};
            if($c_first == ' ') {
                if($c_last == ' ') {
                    $align = 'center';
                } else {
                    $align = 'right';
                }
            } else {
                if($c_last == ' ') {
                    $align = 'left';
                } else {
                    $align = '';
                }
            };
        } else {
            $align = '';
        }

        return (array($size, trim($math), $align));
    }

    /**
     * Create output
     * @param string $mode
     * @param Doku_Renderer $R
     * @param array $data
     * @return bool
     */
    function render($mode, Doku_Renderer $R, $data) {
        if(!$this->enable) return true;
        if($mode != 'xhtml' && $mode != 'odt') return false;

        list($size, $math, $align) = $data;
        $ident = md5($math . '-' . $size);

        // check if we have a cached version available
        $valignfile = getCacheName($ident, '.mathpublish.valign');
        $imagefile = getCacheName($ident, '.mathpublish.png');
        if(file_exists($valignfile)) {
            $valign = (int) io_readFile($valignfile);
        } else {
            require_once(__DIR__ . '/phpmathpublisher/load.php');
            $pmp = new \RL\PhpMathPublisher\PhpMathPublisher('', '', $size);
            $pmp->getHelper()->setTransparent(true);
            $valign = $pmp->renderImage($math, $imagefile) - 1000;
            io_saveFile($valignfile, $valign);
        }

        // pass local files to PDF renderer
        if(is_a($R, 'renderer_plugin_dw2pdf')) {
            $img = 'dw2pdf://' . $imagefile;
        } else {
            $img = DOKU_BASE . 'lib/plugins/mathpublish/img.php?img=' . $ident;
        }

        // output aligned image
        if($mode == 'odt') {
            if(!$align) {
                $align = 'left';
            }
            $R->_odtAddImage($imagefile, NULL, NULL, $align, NULL, NULL);
        } else {
            if($align) {
                $display = 'block';
                $align = "media$align";
            } else {
                $display = 'inline-block';
            }

            $R->doc .= '<img src="' . $img . '"
                         class="' . $align . ' mathpublish"
                         alt="' . hsc($math) . '"
                         title="' . hsc($math) . '"
                         style="display: ' . $display . '; vertical-align:' . $valign . 'px" />';
        }
        return true;
    }

    /**
     * check if php installation has required libraries/functions
     */
    private function _requirements_ok() {
        if(!function_exists('imagepng')) {
            $this->_msg($this->getLang('nopng'), -1);
            return false;
        }

        if(!function_exists('imagettftext')) {
            $this->_msg($this->getLang('noft'), -1);
            return false;
        }

        return true;
    }

    /**
     * used to avoid multiple messages
     *
     * @param string $str
     * @param int $lvl
     */
    protected function _msg($str, $lvl = 0) {
        if($this->msg_sent) return;

        msg($str, $lvl);
        $this->msg_sent = true;
    }
}

