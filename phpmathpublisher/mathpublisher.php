<?php
/**
 * PHPMathPublisher main class
 * 
 * This was converted fromt he original release 0.3 into a class structure. I
 * don't speak french, so I'm a bit at loss at what all the functions and
 * parameters really do. Documentation should be updated, function and parameter
 * names should be refactored into English names.
 * 
 * @license GPL 2
 * @author  Pascal Brachet <pbrachet [at] xm1math.net>
 * @author  Andreas Gohr <gohr@cosmocode.de>
 * @link    http://www.xm1math.net/phpmathpublisher/
 * @fixme   document and refactor, see above
 */

require(dirname(__FILE__).'/expression.php');
require(dirname(__FILE__).'/expression_texte.php');
require(dirname(__FILE__).'/expression_math.php');


class phpmathpublisher {


    /**
     * image output location
     */
    public $dirimg;

    /**
     * Constructor
     *
     * Sets default directories
     */
    public function __construct(){
        $this->dirimg   = dirname(__FILE__)."/img";
    }

    /**
     * Detects if the formula image already exists in the $dirimg cache directory.
     *
     * In that case, the function returns a parameter (recorded in the name of
     * the image file) which allows to align correctly the image with the text.
     *
     * @param string $n the image name
     * @fixme this needs a directory scan which is quite inefficient
     * @return unknown
     */
    protected function detectimg($n) {
        $ret=0;
        $handle=opendir($this->dirimg);
        while ($fi = readdir($handle)) {
            $info=pathinfo($fi);
            if ($fi!="." && $fi!=".." && $info["extension"]=="png" && ereg("^math", $fi)) {
                list($math, $v, $name)=explode("_", $fi);
                if ($name==$n) {
                    $ret=$v;
                    break;
                }
            }
        }
        closedir($handle);
        return $ret;
    }


    /**
     * Creates the formula image (if the image is not in the cache)
     *
     * returns the <img src=...></img> html code.
     *
     * @param string  $text a formular in syntax
     * @param int $size
     * @param string  $pathtoimg HTML base path pointing to the image dir
     * @return string the HTML img code
     * @fixme fix XSS vulnerability
     */
    public function mathimage($text, $size, $pathtoimg) {
        $nameimg = md5(trim($text).$size).'.png';
        $v=$this->detectimg($nameimg);
        if ($v==0) {
            //the image doesn't exist in the cache directory. we create it.
            $formula=new PMP_expression_math($this->tableau_expression(trim($text)));
            $formula->dessine($size);
            $v=1000-imagesy($formula->image)+$formula->base_verticale+3;
            //1000+baseline ($v) is recorded in the name of the image
            imagepng($formula->image, $this->dirimg."/math_".$v."_".$nameimg);
        }
        $valign=$v-1000;
        return '<img src="'.$pathtoimg."math_".$v."_".$nameimg.'" style="vertical-align:'.$valign.'px;'.' display: inline-block ;" alt="'.$text.'" title="'.$text.'"/>';
    }


    /**
     * Parse formulas from a given text and replace them with images
     *
     * 1) the content of the math tags (<m></m>) are extracted in the $t variable
     *    (you can replace <m></m> by your own tag).
     * 2) the "mathimage" function replaces the $t code by <img src=...></img>
     *    according to this method :
     *   - if the image corresponding to the formula doesn't exist in the $dirimg
     *     cache directory (detectimg($nameimg)=0), the script creates the image
     *     and returns the "<img src=...></img>" code.
     *   - otherwise, the script returns only the <img src=...></img>" code.
     *
     * To align correctly the formula image with the text, the "valign" parameter
     * of the image is required.
     * That's why a parameter (1000+valign) is recorded in the name of the image
     * file (the "detectimg" function returns this parameter if the image exists
     * in the cache directory)
     * To be sure that the name of the image file is unique and to allow the script
     * to retrieve the valign parameter without re-creating the image, the syntax
     * of the image filename is: math_(1000+valign)_md5(formulatext.size).png.
     * (1000+valign is used instead of valign directly to avoid a negative number)
     *
     * @param string $text
     * @param int $size
     * @param string $pathtoimg
     * @return string
     */
    public function mathfilter($text, $size, $pathtoimg) {
        $text=stripslashes($text);
        $size=max($size, 10);
        $size=min($size, 24);
        preg_match_all("|<m>(.*?)</m>|", $text, $regs, PREG_SET_ORDER);
        foreach ($regs as $math) {
            $t=str_replace('<m>', '', $math[0]);
            $t=str_replace('</m>', '', $t);
            $code=$this->mathimage(trim($t), $size, $pathtoimg);
            $text = str_replace($math[0], $code, $text);
        }
        return $text;
    }

    /**
     * Cleans and parses a formula into an array of tokens?
     *
     * @param string $expression The formular
     * @return array
     */
    protected function tableau_expression($expression) {
        $e = str_replace('_', ' _ ', $expression);
        $e = str_replace('{(}', '{ }', $e);
        $e = str_replace('{)}', '{ }', $e);
        $t = token_get_all("<"."?php \$formula=$e ?".">");
        $extraits = array();
        $result=array();
        //stupid code but token_get_all bug in some php versions
        $d=0;
        for ($i = 0; $i < count($t); $i++) {
            if (is_array($t[$i])) $t[$i] = $t[$i][1];
            if (ereg("formula", $t[$i])) {
                $d=$i+2;
                break;
            }
        }

        for ($i = $d; $i < count($t) - 1; $i++) {
            if (is_array($t[$i])) $t[$i] = $t[$i][1];
            if ($t[$i] == '<=') $t[$i] = 'le';
            elseif ($t[$i] == '!=') $t[$i] = 'ne';
            elseif ($t[$i] == '<>') $t[$i] = 'ne';
            elseif ($t[$i] == '>=') $t[$i] = 'ge';
            elseif ($t[$i] == '--') {
                $t[$i] = '-';
                $t[$i+1] = '-' . $t[$i+1];
            }
            elseif ($t[$i] == '++') $t[$i] = '+';
            elseif ($t[$i] == '-') {
                if ($t[$i - 1] == '^' || $t[$i - 1] == '_' || $t[$i - 1] == '*' || $t[$i - 1] == '/' || $t[$i - 1] == '+' || $t[$i - 1] == '(') {
                    $t[$i] = '';
                    if (is_array($t[$i+1])) $t[$i+1][1] = '-' . $t[$i+1][1];
                    else $t[$i+1] = '-' . $t[$i+1];
                }
            }
            if (trim($t[$i]) != '') $extraits[] = $t[$i];
        }
        for ($i = 0; $i < count($extraits); $i++) {
            $result[]=new PMP_expression_texte($extraits[$i]);
        }
        return $result;
    }


}






