<?php
/***************************************************************************
 *   copyright            : (C) 2005 by Pascal Brachet - France            *
 *   pbrachet_NOSPAM_xm1math.net (replace _NOSPAM_ by @)                   *
 *   http://www.xm1math.net/phpmathpublisher/                              *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/

namespace RL\PhpMathPublisher;

/**
 * \RL\PhpMathPublisher\Helper
 *
 * @author Pascal Brachet <pbrachet@xm1math.net>
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license GPLv2
 */
class Helper
{

    /**
     * @var string
     */
    public $dirFonts;
    /**
     * @var string
     */
    public $dirImg;
    /**
     * @var int
     */
    public $backR;
    /**
     * @var int
     */
    public $backG;
    /**
     * @var int
     */
    public $backB;
    /**
     * @var int
     */
    public $fontR;
    /**
     * @var int
     */
    public $fontG;
    /**
     * @var int
     */
    public $fontB;
    /**
     * @var bool
     */
    public $transparent;
    /**
     * @var array
     */
    public $symbols;
    /**
     * @var array
     */
    public $mathFonts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dirFonts = __DIR__ . "/fonts";
        $this->dirImg = __DIR__ . "/images";
        $this->backR = 255;
        $this->backG = 255;
        $this->backB = 255;
        $this->fontR = 0;
        $this->fontG = 0;
        $this->fontB = 0;
        $this->transparent = false;
        $this->symbols = array(
            '~' => ' ',
            'alpha' => '&#174;',
            'beta' => '&#175;',
            'gamma' => '&#176;',
            'delta' => '&#177;',
            'epsilon' => '&#178;',
            'varepsilon' => '&#34;',
            'zeta' => '&#179;',
            'eta' => '&#180;',
            'theta' => '&#181;',
            'vartheta' => '&#35;',
            'iota' => '&#182;',
            'kappa' => '&#183;',
            'lambda' => '&#184;',
            'mu' => '&#185;',
            'nu' => '&#186;',
            'xi' => '&#187;',
            'pi' => '&#188;',
            'varpi' => '&#36;',
            'rho' => '&#189;',
            'varrho' => '&#37;',
            'sigma' => '&#190;',
            'varsigma' => '&#38;',
            'tau' => '&#191;',
            'upsilon' => '&#192;',
            'phi' => '&#193;',
            'varphi' => '&#39;',
            'chi' => '&#194;',
            'psi' => '&#195;',
            'omega' => '&#33;',
            'Gamma' => '&#161;',
            'Lambda' => '&#164;',
            'Sigma' => '&#167;',
            'Psi' => '&#170;',
            'Delta' => '&#162;',
            'Xi' => '&#165;',
            'Upsilon' => '&#168;',
            'Omega' => '&#173;',
            'Theta' => '&#163;',
            'Pi' => '&#166;',
            'Phi' => '&#169;',
            'infty' => '&#8734;',
            'ne' => '&#8800;',
            '*' => '&#215;',
            'in' => '&#8712;',
            'notin' => '&#8713;',
            'forall' => '&#8704;',
            'exists' => '&#8707;',
            'notexists' => '&#8708;',
            'partial' => '&#8706;',
            'approx' => '&#8776;',
            'left' => '&#8592;',
            'right' => '&#8594;',
            'leftright' => '&#8596;',
            'doubleleft' => '&#8656;',
            'doubleright' => '&#8658;',
            'doubleleftright' => '&#8660;',
            'nearrow' => '&#8599;',
            'searrow' => '&#8601;',
            'pm' => '&#177;',
            'bbR' => '&#8477;',
            'bbN' => '&#8469;',
            'bbZ' => '&#8484;',
            'bbC' => '&#8450;',
            'inter' => '&#8898;',
            'union' => '&#8899;',
            'ortho' => '&#8869;',
            'parallel' => '&#8741;',
            'backslash' => '&#92;',
            'prime' => '&#39;',
            'wedge' => '&#8896;',
            'vert' => '&#8741;',
            'subset' => '&#8834;',
            'notsubset' => '&#8836;',
            'circ' => '&#8728;',
            'varnothing' => '&#248;',
            'cdots' => '&#8943;',
            'vdots' => '&#8942;',
            'ddots' => '&#8945;', //operateurs
            'le' => '&#54;',
            'ge' => '&#62;',
            '<' => '&#60;',
            '>' => '&#62;', //parentheses
            '(' => '&#179;',
            ')' => '&#180;',
            '[' => '&#104;',
            ']' => '&#105;',
            'lbrace' => '&#40;',
            'rbrace' => '&#41;', //autres
            '_hat' => '&#99;',
            '_racine' => '&#113;',
            '_integrale' => '&#82;',
            '_dintegrale' => '&#8748;',
            '_tintegrale' => '&#8749;',
            '_ointegrale' => '&#72;',
            '_produit' => '&#81;',
            '_somme' => '&#80;',
            '_intersection' => '&#84;',
            '_reunion' => '&#83;',
            '_lim' => 'lim', //fonctions
            'arccos' => 'arccos',
            'ker' => 'ker',
            'arcsin' => 'arcsin',
            'lg' => 'lg',
            'arctan' => 'arctan',
            'arg' => 'arg',
            'cos' => 'cos',
            'cosh' => 'cosh',
            'ln' => 'ln',
            'cot' => 'cot',
            'log' => 'log',
            'coth' => 'coth',
            'max' => 'max',
            'csc' => 'csc',
            'min' => 'min',
            'deg' => 'deg',
            'det' => 'det',
            'sec' => 'sec',
            'dim' => 'dim',
            'sin' => 'sin',
            'exp' => 'exp',
            'sinh' => 'sinh',
            'gcd' => 'gcd',
            'sup' => 'sup',
            'hom' => 'hom',
            'tan' => 'tan',
            'inf' => 'inf',
            'tanh' => 'tanh',
        );
        $this->mathFonts = array(
            '~' => 'FreeSerif',
            'alpha' => 'cmmi10',
            'beta' => 'cmmi10',
            'gamma' => 'cmmi10',
            'delta' => 'cmmi10',
            'epsilon' => 'cmmi10',
            'varepsilon' => 'cmmi10',
            'zeta' => 'cmmi10',
            'eta' => 'cmmi10',
            'theta' => 'cmmi10',
            'vartheta' => 'cmmi10',
            'iota' => 'cmmi10',
            'kappa' => 'cmmi10',
            'lambda' => 'cmmi10',
            'mu' => 'cmmi10',
            'nu' => 'cmmi10',
            'xi' => 'cmmi10',
            'pi' => 'cmmi10',
            'varpi' => 'cmmi10',
            'rho' => 'cmmi10',
            'varrho' => 'cmmi10',
            'sigma' => 'cmmi10',
            'varsigma' => 'cmmi10',
            'tau' => 'cmmi10',
            'upsilon' => 'cmmi10',
            'phi' => 'cmmi10',
            'varphi' => 'cmmi10',
            'chi' => 'cmmi10',
            'psi' => 'cmmi10',
            'omega' => 'cmmi10',
            'Gamma' => 'cmr10',
            'Lambda' => 'cmr10',
            'Sigma' => 'cmr10',
            'Psi' => 'cmr10',
            'Delta' => 'cmr10',
            'Xi' => 'cmr10',
            'Upsilon' => 'cmr10',
            'Omega' => 'cmr10',
            'Theta' => 'cmr10',
            'Pi' => 'cmr10',
            'Phi' => 'cmr10',
            'infty' => 'FreeSerif',
            'ne' => 'FreeSerif',
            '*' => 'FreeSerif',
            'in' => 'FreeSerif',
            'notin' => 'FreeSerif',
            'forall' => 'FreeSerif',
            'exists' => 'FreeSerif',
            'notexists' => 'FreeSerif',
            'partial' => 'FreeSerif',
            'approx' => 'FreeSerif',
            'left' => 'FreeSerif',
            'right' => 'FreeSerif',
            'leftright' => 'FreeSerif',
            'doubleleft' => 'FreeSerif',
            'doubleright' => 'FreeSerif',
            'doubleleftright' => 'FreeSerif',
            'nearrow' => 'FreeSerif',
            'searrow' => 'FreeSerif',
            'pm' => 'FreeSerif',
            'bbR' => 'FreeSerif',
            'bbN' => 'FreeSerif',
            'bbZ' => 'FreeSerif',
            'bbC' => 'FreeSerif',
            'inter' => 'FreeSerif',
            'union' => 'FreeSerif',
            'ortho' => 'FreeSerif',
            'parallel' => 'FreeSerif',
            'backslash' => 'FreeSerif',
            'prime' => 'FreeSerif',
            'wedge' => 'FreeSerif',
            'vert' => 'FreeSerif',
            'subset' => 'FreeSerif',
            'notsubset' => 'FreeSerif',
            'circ' => 'FreeSerif',
            'varnothing' => 'FreeSerif',
            'cdots' => 'FreeSerif',
            'vdots' => 'FreeSerif',
            'ddots' => 'FreeSerif', //operateurs
            'le' => 'msam10',
            'ge' => 'msam10',
            '<' => 'cmmi10',
            '>' => 'cmmi10', //parentheses
            '(' => 'cmex10',
            ')' => 'cmex10',
            '[' => 'cmex10',
            ']' => 'cmex10',
            'lbrace' => 'cmex10',
            'rbrace' => 'cmex10', //autres
            '_hat' => 'cmex10',
            '_racine' => 'cmex10',
            '_integrale' => 'cmex10',
            '_dintegrale' => 'FreeSerif',
            '_tintegrale' => 'FreeSerif',
            '_ointegrale' => 'cmex10',
            '_produit' => 'cmex10',
            '_somme' => 'cmex10',
            '_intersection' => 'cmex10',
            '_reunion' => 'cmex10',
            '_lim' => 'cmr10', //fonctions
            'arccos' => 'cmr10',
            'ker' => 'cmr10',
            'arcsin' => 'cmr10',
            'lg' => 'cmr10',
            'arctan' => 'cmr10',
            'arg' => 'cmr10',
            'cos' => 'cmr10',
            'cosh' => 'cmr10',
            'ln' => 'cmr10',
            'cot' => 'cmr10',
            'log' => 'cmr10',
            'coth' => 'cmr10',
            'max' => 'cmr10',
            'csc' => 'cmr10',
            'min' => 'cmr10',
            'deg' => 'cmr10',
            'det' => 'cmr10',
            'sec' => 'cmr10',
            'dim' => 'cmr10',
            'sin' => 'cmr10',
            'exp' => 'cmr10',
            'sinh' => 'cmr10',
            'gcd' => 'cmr10',
            'sup' => 'cmr10',
            'hom' => 'cmr10',
            'tan' => 'cmr10',
            'inf' => 'cmr10',
            'tanh' => 'cmr10'
        );


    }

    /**
     * @param $str
     * @return int
     */
    public function isNumber($str)
    {
        return preg_match("#^[0-9]#", $str);
    }

    /**
     * @param $expression
     * @return array
     */
    public function tableExpression($expression)
    {
        $e = str_replace('_', ' _ ', $expression);
        $e = str_replace('{(}', '{ }', $e);
        $e = str_replace('{)}', '{ }', $e);
        $t = token_get_all("<?php \$formula=$e ?" . ">");
        $extracts = array();
        $result = array();
        //stupid code but token_get_all bug in some php versions
        $d = 0;
        for ($i = 0; $i < count($t); $i++) {
            if (is_array($t[$i])) {
                $t[$i] = $t[$i][1];
            }
            if (preg_match("#formula#", $t[$i])) {
                $d = $i + 2;
                break;
            }
        }
        for ($i = $d; $i < count($t) - 1; $i++) {
            if (is_array($t[$i])) {
                $t[$i] = $t[$i][1];
            }
            if ($t[$i] == '<=') {
                $t[$i] = 'le';
            } elseif ($t[$i] == '!=') {
                $t[$i] = 'ne';
            } elseif ($t[$i] == '<>') {
                $t[$i] = 'ne';
            } elseif ($t[$i] == '>=') {
                $t[$i] = 'ge';
            } elseif ($t[$i] == '--') {
                $t[$i] = '-';
                $t[$i + 1] = '-' . $t[$i + 1];
            } elseif ($t[$i] == '++') {
                $t[$i] = '+';
            } elseif ($t[$i] == '-') {
                if ($t[$i - 1] == '^' || $t[$i - 1] == '_' || $t[$i - 1] == '*' || $t[$i - 1] == '/' || $t[$i - 1] == '+' || $t[$i - 1] == '(') {
                    $t[$i] = '';
                    if (is_array($t[$i + 1])) {
                        $t[$i + 1][1] = '-' . $t[$i + 1][1];
                    } else {
                        $t[$i + 1] = '-' . $t[$i + 1];
                    }
                }
            }
            if (trim($t[$i]) != '') {
                $extracts[] = $t[$i];
            }
        }
        for ($i = 0; $i < count($extracts); $i++) {
            $result[] = new TextExpression($extracts[$i], $this);
        }

        return $result;
    }

    // ugly hack, but GD is not very good with truetype fonts (especially with latex fonts)
    /**
     * @param $text
     * @param $high
     * @return resource
     */
    public function displaySymbol($text, $high)
    {
        $symbols = $this->getSymbols();
        $fontsMath = $this->getMathFonts();
        $dirFonts = $this->getDirFonts();
        $text = trim(stripslashes($text));
        switch ($text) {
            case '':
                $img = imagecreate(1, max($high, 1));
                $white = $this->getBackColor($img);
                imagefilledrectangle($img, 0, 0, 1, $high, $white);
                break;
            case '~':
                $img = imagecreate(1, max($high, 1));
                $white = $this->getBackColor($img);
                imagefilledrectangle($img, 0, 0, 1, $high, $white);
                break;
            case 'vert':
                $img = imagecreate(6, max($high, 1));
                $black = $this->getFontColor($img);
                $white = $this->getBackColor($img);
                imagefilledrectangle($img, 0, 0, 6, $high, $white);
                imagefilledrectangle($img, 2, 0, 2, $high, $black);
                imagefilledrectangle($img, 4, 0, 4, $high, $black);
                break;
            case '|':
                $img = imagecreate(5, max($high, 1));
                $black = $this->getFontColor($img);
                $white = $this->getBackColor($img);
                imagefilledrectangle($img, 0, 0, 5, $high, $white);
                imagefilledrectangle($img, 2, 0, 2, $high, $black);
                break;
            case 'right':
                $font = $dirFonts . "/" . $fontsMath[$text] . ".ttf";
                $t = 16;
                $text = $symbols[$text];
                $tmpDim = imagettfbbox($t, 0, $font, $text);
                $tmpWidth = abs($tmpDim[2] - $tmpDim[0]) + 2;
                $tmpHeight = abs($tmpDim[3] - $tmpDim[5]) + 2;
                $tmpImg = imagecreate(max($tmpWidth, 1), max($tmpHeight, 1));
                $tmpBlack = $this->getFontColor($tmpImg);
                $tmpWhite = $this->getBackColor($tmpImg);
                imagefilledrectangle($tmpImg, 0, 0, $tmpWidth, $tmpHeight, $tmpWhite);
                imagettftext($tmpImg, $t, 0, 0, $tmpHeight, $tmpBlack, $font, $text);
                $allWhite = true;
                $sx = $sy = $ex = $ey = -1;
                for ($y = 0; $y < $tmpHeight; $y++) {
                    for ($x = 0; $x < $tmpWidth; $x++) {
                        $rgb = imagecolorat($tmpImg, $x, $y);
                        if ($rgb != $tmpWhite) {
                            $allWhite = false;
                            if ($sy == -1) {
                                $sy = $y;
                            } else {
                                $ey = $y;
                            }

                            if ($sx == -1) {
                                $sx = $x;
                            } else {
                                if ($x < $sx) {
                                    $sx = $x;
                                } else {
                                    if ($x > $ex) {
                                        $ex = $x;
                                    }
                                }
                            }
                        }
                    }
                }
                $nx = abs($ex - $sx);
                $ny = abs($ey - $sy);
                $img = imagecreate(max($nx + 4, 1), max($ny + 4, 1));
                $white = $this->getBackColor($img);
                imagefilledrectangle($img, 0, 0, $nx + 4, $ny + 4, $white);
                imagecopy($img, $tmpImg, 2, 2, $sx, $sy, min($nx + 2, $tmpWidth - $sx), min($ny + 2, $tmpHeight - $sy));
                break;
            case '_hat':
                $font = $dirFonts . "/" . $fontsMath[$text] . ".ttf";
                $t = $high;
                $text = $symbols[$text];
                $tmpDim = imagettfbbox($t, 0, $font, $text);
                $tmpWidth = abs($tmpDim[2] - $tmpDim[0]);
                $tmpHeight = abs($tmpDim[3] - $tmpDim[5]) * 4;
                $tmpImg = imagecreate(max($tmpWidth, 1), max($tmpHeight, 1));
                $tmpBlack = $this->getFontColor($tmpImg);
                $tmpWhite = $this->getBackColor($tmpImg);
                imagefilledrectangle($tmpImg, 0, 0, $tmpWidth, $tmpHeight, $tmpWhite);
                imagettftext($tmpImg, $t, 0, 0, $tmpHeight, $tmpBlack, $font, $text);
                $allWhite = true;
                $img = $tmpImg;
                $sx = $sy = $ex = $ey = -1;
                for ($y = 0; $y < $tmpHeight; $y++) {
                    for ($x = 0; $x < $tmpWidth; $x++) {
                        $rgb = imagecolorat($tmpImg, $x, $y);
                        if ($rgb != $tmpWhite) {
                            $allWhite = false;
                            if ($sy == -1) {
                                $sy = $y;
                            } else {
                                $ey = $y;
                            }

                            if ($sx == -1) {
                                $sx = $x;
                            } else {
                                if ($x < $sx) {
                                    $sx = $x;
                                } else {
                                    if ($x > $ex) {
                                        $ex = $x;
                                    }
                                }
                            }
                        }
                    }
                }
                $nx = abs($ex - $sx);
                $ny = abs($ey - $sy);
                $img = imagecreate(max($nx + 4, 1), max($ny + 4, 1));
                $white = $this->getBackColor($img);
                imagefilledrectangle($img, 0, 0, $nx + 4, $ny + 4, $white);
                imagecopy($img, $tmpImg, 2, 2, $sx, $sy, min($nx + 2, $tmpWidth - $sx), min($ny + 2, $tmpHeight - $sy));
                break;
            case '_dintegrale':
            case '_tintegrale':
            if (isset($fontsMath[$text])) {
                $font = $dirFonts . "/" . $fontsMath[$text] . ".ttf";
            } elseif ($this->isNumber($text)) {
                $font = $dirFonts . "/cmr10.ttf";
            } else {
                $font = $dirFonts . "/cmmi10.ttf";
            }
                $t = 6;
            if (isset($symbols[$text])) {
                $text = $symbols[$text];
            }
                do {
                    $tmpDim = imagettfbbox($t, 0, $font, $text);
                    $t += 1;
                } while ((abs($tmpDim[3] - $tmpDim[5]) < 1.2 * $high));
                $tmpWidth = abs($tmpDim[2] - $tmpDim[0]) * 2;
                $tmpHeight = abs($tmpDim[3] - $tmpDim[5]) * 2;
                $tmpImg = imagecreate(max($tmpWidth, 1), max($tmpHeight, 1));
                $tmpBlack = $this->getFontColor($tmpImg);
                $tmpWhite = $this->getBackColor($tmpImg);
                imagefilledrectangle($tmpImg, 0, 0, $tmpWidth, $tmpHeight, $tmpWhite);
                imagettftext($tmpImg, $t, 0, 5, $tmpHeight / 2, $tmpBlack, $font, $text);
                $img = $tmpImg;
                $allWhite = true;
                $sx = $sy = $ex = $ey = -1;
                for ($y = 0; $y < $tmpHeight; $y++) {
                    for ($x = 0; $x < $tmpWidth; $x++) {
                        $rgb = imagecolorat($tmpImg, $x, $y);
                        if ($rgb != $tmpWhite) {
                            $allWhite = false;
                            if ($sy == -1) {
                                $sy = $y;
                            } else {
                                $ey = $y;
                            }

                            if ($sx == -1) {
                                $sx = $x;
                            } else {
                                if ($x < $sx) {
                                    $sx = $x;
                                } else {
                                    if ($x > $ex) {
                                        $ex = $x;
                                    }
                                }
                            }
                        }
                    }
                }
                $nx = abs($ex - $sx);
                $ny = abs($ey - $sy);
                if ($allWhite) {
                    $img = imagecreate(1, max($high, 1));
                    $white = $this->getBackColor($img);
                    imagefilledrectangle($img, 0, 0, 1, $high, $white);
                } else {
                    $img = imagecreate(max($nx + 4, 1), max($ny + 4, 1));
                    $white = $this->getBackColor($img);
                    imagefilledrectangle($img, 0, 0, $nx + 4, $ny + 4, $white);
                    imagecopy(
                        $img,
                        $tmpImg,
                        2,
                        2,
                        $sx,
                        $sy,
                        min($nx + 2, $tmpWidth - $sx),
                        min($ny + 2, $tmpHeight - $sy)
                    );
                }
                break;
            default:
                if (isset($fontsMath[$text])) {
                    $font = $dirFonts . "/" . $fontsMath[$text] . ".ttf";
                } elseif ($this->isNumber($text)) {
                    $font = $dirFonts . "/cmr10.ttf";
                } else {
                    $font = $dirFonts . "/cmmi10.ttf";
                }
                $t = 6;
                if (isset($symbols[$text])) {
                    $text = $symbols[$text];
                }
                do {
                    $tmpDim = imagettfbbox($t, 0, $font, $text);
                    $t += 1;
                } while ((abs($tmpDim[3] - $tmpDim[5]) < $high));
                $tmpWidth = abs($tmpDim[2] - $tmpDim[0]) * 2;
                $tmpHeight = abs($tmpDim[3] - $tmpDim[5]) * 2;
                $tmpImg = imagecreate(max($tmpWidth, 1), max($tmpHeight, 1));
                $tmpBlack = $this->getFontColor($tmpImg);
                $tmpWhite = $this->getBackColor($tmpImg);
                imagefilledrectangle($tmpImg, 0, 0, $tmpWidth, $tmpHeight, $tmpWhite);
                imagettftext($tmpImg, $t, 0, 0, $tmpHeight / 4, $tmpBlack, $font, $text);
                // 	imagettftext($tmpImg, $t, 0,5,5,$tmpBlack, $font,$text);
                //	$img=$tmpImg;
                $allWhite = true;
                $sx = $sy = $ex = $ey = -1;
                for ($y = 0; $y < $tmpHeight; $y++) {
                    for ($x = 0; $x < $tmpWidth; $x++) {
                        $rgb = imagecolorat($tmpImg, $x, $y);
                        if ($rgb != $tmpWhite) {
                            $allWhite = false;
                            if ($sy == -1) {
                                $sy = $y;
                            } else {
                                $ey = $y;
                            }

                            if ($sx == -1) {
                                $sx = $x;
                            } else {
                                if ($x < $sx) {
                                    $sx = $x;
                                } else {
                                    if ($x > $ex) {
                                        $ex = $x;
                                    }
                                }
                            }
                        }
                    }
                }
                $nx = abs($ex - $sx);
                $ny = abs($ey - $sy);
                if ($allWhite) {
                    $img = imagecreate(1, max($high, 1));
                    $white = $this->getBackColor($img);
                    imagefilledrectangle($img, 0, 0, 1, $high, $white);
                } else {
                    $img = imagecreate(max($nx + 4, 1), max($ny + 4, 1));
                    $white = $this->getBackColor($img);
                    imagefilledrectangle($img, 0, 0, $nx + 4, $ny + 4, $white);
                    imagecopy(
                        $img,
                        $tmpImg,
                        2,
                        2,
                        $sx,
                        $sy,
                        min($nx + 2, $tmpWidth - $sx),
                        min($ny + 2, $tmpHeight - $sy)
                    );
                }
                break;
        }

        //$rouge=imagecolorallocate($img,255,0,0);
        //ImageRectangle($img,0,0,ImageSX($img)-1,ImageSY($img)-1,$rouge);
        return $img;
    }

    /**
     * @param $text
     * @param $size
     * @return resource
     */
    public function displayText($text, $size)
    {
        $dirFonts = $this->getDirFonts();
        $size = max($size, 6);
        $text = stripslashes($text);
        $font = $dirFonts . "/cmr10.ttf";
        $textHeight = 'dg' . $text;
        $heightDim = imagettfbbox($size, 0, $font, $textHeight);
        $widthDim = imagettfbbox($size, 0, $font, $text);
        $dx = max($widthDim[2], $widthDim[4]) - min($widthDim[0], $widthDim[6]) + ceil($size / 8);
        $dy = max($heightDim[1], $heightDim[3]) - min($heightDim[5], $heightDim[7]) + ceil($size / 8);
        $img = imagecreate(max($dx, 1), max($dy, 1));
        $black = $this->getFontColor($img);
        $white = $this->getBackColor($img);
        imagefilledrectangle($img, 0, 0, $dx, $dy, $white);
        //ImageRectangle($img,0,0,$dx-1,$dy-1,$black);
        imagettftext($img, $size, $angle, 0, -min($heightDim[5], $heightDim[7]), $black, $font, $text);

        return $img;
    }

    /**
     * @param $text
     * @param $size
     * @return resource
     */
    public function displayMath($text, $size)
    {
        $size = max($size, 6);
        $symbols = $this->getSymbols();
        $fontsMath = $this->getMathFonts();
        $dirFonts = $this->getDirFonts();
        $text = stripslashes($text);
        if (isset($fontsMath[$text])) {
            $font = $dirFonts . "/" . $fontsMath[$text] . ".ttf";
        } elseif (preg_match("#[a-zA-Z]#", $text)) {
            $font = $dirFonts . "/cmmi10.ttf";
        } else {
            $font = $dirFonts . "/cmr10.ttf";
        }
        if (isset($symbols[$text])) {
            $text = $symbols[$text];
        }
        $textHeight = 'dg' . $text;
        $heightDim = imagettfbbox($size, 0, $font, $textHeight);
        $widthDim = imagettfbbox($size, 0, $font, $text);
        $dx = max($widthDim[2], $widthDim[4]) - min($widthDim[0], $widthDim[6]) + ceil($size / 8);
        $dy = max($heightDim[1], $heightDim[3]) - min($heightDim[5], $heightDim[7]) + ceil($size / 8);
        $img = imagecreate(max($dx, 1), max($dy, 1));
        $black = $this->getFontColor($img);
        $white = $this->getBackColor($img);
        imagefilledrectangle($img, 0, 0, $dx, $dy, $white);
        //ImageRectangle($img,0,0,$dx-1,$dy-1,$black);
        imagettftext($img, $size, 0, 0, -min($heightDim[5], $heightDim[7]), $black, $font, $text);

        return $img;
    }

    /**
     * @param $height
     * @param $style
     * @return resource
     */
    public function parenthesis($height, $style)
    {
        $image = $this->displaySymbol($style, $height);

        return $image;
    }

    /**
     * @param $image1
     * @param $base1
     * @param $image2
     * @param $base2
     * @return resource
     */
    public function alignment2($image1, $base1, $image2, $base2)
    {
        $width1 = imagesx($image1);
        $height1 = imagesy($image1);
        $width2 = imagesx($image2);
        $height2 = imagesy($image2);
        $top = max($base1, $base2);
        $bottom = max($height1 - $base1, $height2 - $base2);
        $width = $width1 + $width2;
        $height = $top + $bottom;
        $result = imagecreate(max($width, 1), max($height, 1));
        $white = $this->getBackColor($result);
        imagefilledrectangle($result, 0, 0, $width - 1, $height - 1, $white);
        imagecopy($result, $image1, 0, $top - $base1, 0, 0, $width1, $height1);
        imagecopy($result, $image2, $width1, $top - $base2, 0, 0, $width2, $height2);

        //ImageRectangle($result,0,0,$width-1,$height-1,$black);
        return $result;
    }

    /**
     * @param $image1
     * @param $base1
     * @param $image2
     * @param $base2
     * @param $image3
     * @param $base3
     * @return resource
     */
    public function alignment3($image1, $base1, $image2, $base2, $image3, $base3)
    {
        $width1 = imagesx($image1);
        $height1 = imagesy($image1);
        $width2 = imagesx($image2);
        $height2 = imagesy($image2);
        $width3 = imagesx($image3);
        $height3 = imagesy($image3);
        $top = max($base1, $base2, $base3);
        $bottom = max($height1 - $base1, $height2 - $base2, $height3 - $base3);
        $width = $width1 + $width2 + $width3;
        $height = $top + $bottom;
        $result = imagecreate(max($width, 1), max($height, 1));
        $black = $this->getFontColor($result);
        $white = $this->getBackColor($result);
        imagefilledrectangle($result, 0, 0, $width - 1, $height - 1, $white);
        imagecopy($result, $image1, 0, $top - $base1, 0, 0, $width1, $height1);
        imagecopy($result, $image2, $width1, $top - $base2, 0, 0, $width2, $height2);
        imagecopy($result, $image3, $width1 + $width2, $top - $base3, 0, 0, $width3, $height3);

        //ImageRectangle($result,0,0,$width-1,$height-1,$black);
        return $result;
    }

    /**
     * Set the background color
     *
     * @param int $R
     * @param int $G
     * @param int $B
     */
    public function setBack($R, $G, $B) {
        $this->backR = $R;
        $this->backG = $G;
        $this->backB = $B;
    }

    /**
     * Set the font color
     *
     * @param int $R
     * @param int $G
     * @param int $B
     */
    public function setFont($R, $G, $B) {
        $this->fontR = $R;
        $this->fontG = $G;
        $this->fontB = $B;
    }

    /**
     * Get the background color allocated for the given image ressource
     *
     * @param resource $img
     * @return int
     */
    public function getBackColor($img) {
        $back = imagecolorallocate($img, $this->backR, $this->backG, $this->backB);
        if($this->transparent) {
            $back = imagecolortransparent($img, $back);
        }
        return $back;
    }

    /**
     * Get the font color allocated for the given image ressource
     *
     * @param resource $img
     * @return int
     */
    public function getFontColor($img) {
        return imagecolorallocate($img, $this->fontR, $this->fontG, $this->fontB);
    }


    /**
     * @param $dirFonts
     */
    public function setDirFonts($dirFonts)
    {
        $this->dirFonts = $dirFonts;
    }

    /**
     * @return string
     */
    public function getDirFonts()
    {
        return $this->dirFonts;
    }

    /**
     * @param $dirImg
     */
    public function setDirImg($dirImg)
    {
        $this->dirImg = $dirImg;
    }

    /**
     * @return string
     */
    public function getDirImg()
    {
        return $this->dirImg;
    }

    /**
     * @param $mathFonts
     */
    public function setMathFonts($mathFonts)
    {
        $this->mathFonts = $mathFonts;
    }

    /**
     * @return array
     */
    public function getMathFonts()
    {
        return $this->mathFonts;
    }

    /**
     * @param $symbols
     */
    public function setSymbols($symbols)
    {
        $this->symbols = $symbols;
    }

    /**
     * @return array
     */
    public function getSymbols()
    {
        return $this->symbols;
    }

    /**
     * @param $transparent
     */
    public function setTransparent($transparent)
    {
        $this->transparent = $transparent;
    }

    /**
     * @return bool
     */
    public function getTransparent()
    {
        return $this->transparent;
    }

}
