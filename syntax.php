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
require_once(DOKU_INC.'inc/io.php');

global $conf;

// -----------------------[ math plugin globals ]---------------------------------------
global $mathplugin_size, $mathplugin_urlimg;

  // default base size (pixels) of glyphs in the formulae
  $mathplugin_size = 12;

  // base url to access images, should correspond to $dirimg below.
  // if left at default, it will be modified to add a subfolder to avoid filling
  // the root media folder with clutter, refer _cacheExists()
  $mathplugin_urlimg = DOKU_URL.'lib/exe/fetch.php?w=&amp;h=&amp;cache=cache&amp;media=';

// -----------------------[ mathpublisher settings ]------------------------------------
global $dirfonts,$dirimg;

  // absolute path to the fonts directory (must not have '/' at end)
  $dirfonts=dirname(__FILE__)."/phpmathpublisher/fonts";

  // absolute path to the img directory (must not have '/' at end)
  // if left at default, it will be modified to add a subfolder to avoid filling
  // the root media folder with clutter, refer _cacheExists()
  $dirimg=$conf['mediadir'];

// ------------------------------------------------------------------------------------

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_mathpublish extends DokuWiki_Syntax_Plugin {

    // FIXME localise
    var $str_nopng = "PHP's gd library is missing or unable to create PNG images";
    var $str_noft = "PHP installation missing access to freetype library";
    var $enable = false;
    var $msg_disable = "math plugin disabled: ";
    var $msg_sent = false;

    function syntax_plugin_math() {
        $this->enable = $this->_requirements_ok();
    }

    function getType(){ return 'protected'; }
    function getPType(){ return 'normal'; }
    function getSort(){ return 208; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
      $this->Lexer->addEntryPattern('<m(?=[^\r\n]*?>.*?</m>)',$mode,'plugin_math');
    }

    function postConnect() {
      $this->Lexer->addExitPattern('</m>','plugin_math');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
      global $mathplugin_size;

      if ( $state == DOKU_LEXER_UNMATCHED ) {
        list($size, $math) = preg_split('/>/u', $match, 2);   // will split into size & math formulae
        if (!is_numeric($size)) $size = $mathplugin_size;

        if (strlen($math) > 1) {
          $c_first = $math{0};
          $c_last = $math{strlen($math)-1};

          $align = ($c_first == ' ') ? ($c_last == ' ' ? 'center' : 'right') : ($c_last == ' ' ? 'left' : 'normal');
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
    function render($mode, &$renderer, $data) {
      global $mathplugin_urlimg;

      if (!$data) return;   // skip rendering for the enter and exit patterns
      list($size, $math, $align) = $data;

      if($mode == 'xhtml'){
          // phpmathpublisher generates many E_NOTICE errors, ensure error_reporting doesn't include E_NOTICE.
          $error_level = error_reporting();
          error_reporting($error_level & ~E_NOTICE);

          // check we have ability to create png images
          if ($this->enable) {
            // check we have somewhere to create our images & make them
            if ($this->_cacheExists()) {
                require_once(dirname(__FILE__).'/phpmathpublisher/mathpublisher.php');
                $math_html = mathimage($math, $size, $mathplugin_urlimg);

                if ($align != 'normal') {
                    $math_html = preg_replace('/<img /i','\0 class="media'.$align.'" ',$math_html);
                }

                $renderer->doc .= $math_html;
            } else {
                $this->_msg("math plugin img folder is not writable", -1);
            }
          } else {
            $this->_msg($this->msg_disable, -1);
          }

          // return to previous error reporting level
          error_reporting($error_level);
          return true;
      }
      return false;
    }

    function _cacheExists() {
        global $dirimg, $mathplugin_urlimg, $conf;

        // check for default setting
        if (!isset($dirimg) || !$dirimg) { $dirimg = $conf['mediadir']; }
        if ($dirimg == $conf['mediadir']) {
            // we don't want to clutter the root media dir, so create our own subfolder
            $dirimg .= "/cache_mathplugin";
            $mathplugin_urlimg .= "cache_mathplugin%3a";

            if (!@is_dir($dirimg)) {
                $this->_mkdir($dirimg);
            }
        }

        return @is_writable($dirimg);
    }

    // return true if php installation has required libraries/functions for mathpublisher
    function _requirements_ok() {
        if (!function_exists('imagepng')) {
          $this->msg_disable .= $this->str_nopng;
          return false;
        }

        if (!function_exists('imagettftext')) {
          $this->msg_disable .= $this->str_noft;
          return false;
        }

        return true;
    }

    // used to avoid multiple messages
    function _msg($str, $lvl=0) {
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

