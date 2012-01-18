<?php

/**
 * PHPMathPublisher base expression class
 * 
 * @license GPL 2
 * @author  Pascal Brachet <pbrachet [at] xm1math.net>
 * @author  Andreas Gohr <gohr@cosmocode.de>
 * @link    http://www.xm1math.net/phpmathpublisher/
 */
class PMP_expression{

    protected $texte;
    public $image;
    public $base_verticale;
    protected $dirfonts;

    /**
     * Constructor
     *
     * Sets default directories
     */
    public function __construct(){
        $this->dirfonts = dirname(__FILE__)."/fonts";
    }

    /**
     * Mathematical symbol names
     */
    protected static $symboles = array(
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
        'ddots' => '&#8945;',
        //operateurs
        'le' => '&#54;',
        'ge' => '&#62;',
        '<' => '&#60;',
        '>' => '&#62;',
        //parentheses
        '(' => '&#179;',
        ')' => '&#180;',
        '[' => '&#104;',
        ']' => '&#105;',
        'lbrace' => '&#40;',
        'rbrace' => '&#41;',
        //autres
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
        '_lim' => 'lim',
        //fonctions
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
        'tanh' => 'tanh'
    );

    /**
     * Symbol to font assignment
     */
    protected static $fontesmath = array(
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
        'ddots' => 'FreeSerif',
        //operateurs
        'le' => 'msam10',
        'ge' => 'msam10',
        '<' => 'cmmi10',
        '>' => 'cmmi10',
        //parentheses
        '(' => 'cmex10',
        ')' => 'cmex10',
        '[' => 'cmex10',
        ']' => 'cmex10',
        'lbrace' => 'cmex10',
        'rbrace' => 'cmex10',
        //autres
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
        '_lim' => 'cmr10',
        //fonctions
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

    /**
     *
     *
     * @param unknown $str
     * @return unknown
     */
    protected function est_nombre($str){
        return ereg("^[0-9]", $str);
    }

    /**
     * ugly hack, but GD is not very good with truetype fonts (especially with latex fonts)
     *
     * @param unknown $texte
     * @param unknown $haut
     * @return unknown
     */
    protected function affiche_symbol($texte, $haut){
        $texte = trim(stripslashes($texte));
        switch($texte){
            case '':
                $img = imagecreate(1, max($haut, 1));
                $blanc = imagecolorallocate($img, 255, 255, 255);
                $blanc = imagecolortransparent($img, $blanc);
                imagefilledrectangle($img, 0, 0, 1, $haut, $blanc);
                break;
            case '~':
                $img = imagecreate(1, max($haut, 1));
                $blanc = imagecolorallocate($img, 255, 255, 255);
                $blanc = imagecolortransparent($img, $blanc);
                imagefilledrectangle($img, 0, 0, 1, $haut, $blanc);
                break;
            case 'vert':
                $img = imagecreate(6, max($haut, 1));
                $blanc = imagecolorallocate($img, 255, 255, 255);
                $blanc = imagecolortransparent($img, $blanc);
                $noir = imagecolorallocate($img, 0, 0, 0);
                imagefilledrectangle($img, 0, 0, 6, $haut, $blanc);
                imagefilledrectangle($img, 2, 0, 2, $haut, $noir);
                imagefilledrectangle($img, 4, 0, 4, $haut, $noir);
                break;
            case '|':
                $img = imagecreate(5, max($haut, 1));
                $blanc = imagecolorallocate($img, 255, 255, 255);
                $blanc = imagecolortransparent($img, $blanc);
                $noir = imagecolorallocate($img, 0, 0, 0);
                imagefilledrectangle($img, 0, 0, 5, $haut, $blanc);
                imagefilledrectangle($img, 2, 0, 2, $haut, $noir);
                break;
            case 'right':
                $font = $this->dirfonts."/".$this->fontesmath[$texte].".ttf";
                $t = 16;
                $texte = $this->symboles[$texte];
                $tmp_dim = imagettfbbox($t, 0, $font, $texte);
                $tmp_largeur = abs($tmp_dim[2] - $tmp_dim[0]) + 2;
                $tmp_hauteur = abs($tmp_dim[3] - $tmp_dim[5]) + 2;
                $tmp_img = imagecreate(max($tmp_largeur, 1), max($tmp_hauteur, 1));
                $tmp_noir = imagecolorallocate($tmp_img, 0, 0, 0);
                $tmp_blanc = imagecolorallocate($tmp_img, 255, 255, 255);
                $tmp_blanc = imagecolortransparent($tmp_img, $tmp_blanc);
                imagefilledrectangle($tmp_img, 0, 0, $tmp_largeur, $tmp_hauteur, $tmp_blanc);
                imagettftext($tmp_img, $t, 0, 0, $tmp_hauteur, $tmp_noir, $font, $texte);
                $toutblanc = true;
                $sx = $sy = $ex = $ey = -1;
                for($y = 0; $y < $tmp_hauteur; $y++){
                    for($x = 0; $x < $tmp_largeur; $x++){
                        $rgb = imagecolorat($tmp_img, $x, $y);
                        if($rgb != $tmp_blanc){
                            $toutblanc = false;
                            if($sy == -1)
                                $sy = $y;
                            else
                                $ey = $y;

                            if($sx == -1)
                                $sx = $x;
                            else{
                                if($x < $sx)
                                    $sx = $x;
                                else if($x > $ex)
                                    $ex = $x;
                            }
                        }
                    }
                }
                $nx = abs($ex - $sx);
                $ny = abs($ey - $sy);
                $img = imagecreate(max($nx + 4, 1), max($ny + 4, 1));
                $blanc = imagecolorallocate($img, 255, 255, 255);
                $blanc = imagecolortransparent($img, $blanc);
                imagefilledrectangle($img, 0, 0, $nx + 4, $ny + 4, $blanc);
                imagecopy($img, $tmp_img, 2, 2, $sx, $sy, min($nx + 2, $tmp_largeur - $sx), min($ny + 2, $tmp_hauteur - $sy));
                break;
            case '_hat':
                $font = $this->dirfonts."/".$this->fontesmath[$texte].".ttf";
                $t = $haut;
                $texte = $this->symboles[$texte];
                $tmp_dim = imagettfbbox($t, 0, $font, $texte);
                $tmp_largeur = abs($tmp_dim[2] - $tmp_dim[0]);
                $tmp_hauteur = abs($tmp_dim[3] - $tmp_dim[5]) * 4;
                $tmp_img = imagecreate(max($tmp_largeur, 1), max($tmp_hauteur, 1));
                $tmp_noir = imagecolorallocate($tmp_img, 0, 0, 0);
                $tmp_blanc = imagecolorallocate($tmp_img, 255, 255, 255);
                $tmp_blanc = imagecolortransparent($tmp_img, $tmp_blanc);
                imagefilledrectangle($tmp_img, 0, 0, $tmp_largeur, $tmp_hauteur, $tmp_blanc);
                imagettftext($tmp_img, $t, 0, 0, $tmp_hauteur, $tmp_noir, $font, $texte);
                $toutblanc = true;
                $img = $tmp_img;
                $sx = $sy = $ex = $ey = -1;
                for($y = 0; $y < $tmp_hauteur; $y++){
                    for($x = 0; $x < $tmp_largeur; $x++){
                        $rgb = imagecolorat($tmp_img, $x, $y);
                        if($rgb != $tmp_blanc){
                            $toutblanc = false;
                            if($sy == -1)
                                $sy = $y;
                            else
                                $ey = $y;

                            if($sx == -1)
                                $sx = $x;
                            else{
                                if($x < $sx)
                                    $sx = $x;
                                else if($x > $ex)
                                    $ex = $x;
                            }
                        }
                    }
                }
                $nx = abs($ex - $sx);
                $ny = abs($ey - $sy);
                $img = imagecreate(max($nx + 4, 1), max($ny + 4, 1));
                $blanc = imagecolorallocate($img, 255, 255, 255);
                $blanc = imagecolortransparent($img, $blanc);
                imagefilledrectangle($img, 0, 0, $nx + 4, $ny + 4, $blanc);
                imagecopy($img, $tmp_img, 2, 2, $sx, $sy, min($nx + 2, $tmp_largeur - $sx), min($ny + 2, $tmp_hauteur - $sy));
                break;
            case '_dintegrale':
            case '_tintegrale':
                if(isset($this->fontesmath[$texte]))
                    $font = $this->dirfonts."/".$this->fontesmath[$texte].".ttf";
                elseif($this->est_nombre($texte))
                    $font = $this->dirfonts."/cmr10.ttf";
                else
                    $font = $this->dirfonts."/cmmi10.ttf";
                $t = 6;
                if(isset($this->symboles[$texte]))
                    $texte = $this->symboles[$texte];
                do{
                    $tmp_dim = imagettfbbox($t, 0, $font, $texte);
                    $t+=1;
                }while((abs($tmp_dim[3] - $tmp_dim[5]) < 1.2 * $haut));
                $tmp_largeur = abs($tmp_dim[2] - $tmp_dim[0]) * 2;
                $tmp_hauteur = abs($tmp_dim[3] - $tmp_dim[5]) * 2;
                $tmp_img = imagecreate(max($tmp_largeur, 1), max($tmp_hauteur, 1));
                $tmp_noir = imagecolorallocate($tmp_img, 0, 0, 0);
                $tmp_blanc = imagecolorallocate($tmp_img, 255, 255, 255);
                $tmp_blanc = imagecolortransparent($tmp_img, $tmp_blanc);
                imagefilledrectangle($tmp_img, 0, 0, $tmp_largeur, $tmp_hauteur, $tmp_blanc);
                imagettftext($tmp_img, $t, 0, 5, $tmp_hauteur / 2, $tmp_noir, $font, $texte);
                $img = $tmp_img;
                $toutblanc = true;
                $sx = $sy = $ex = $ey = -1;
                for($y = 0; $y < $tmp_hauteur; $y++){
                    for($x = 0; $x < $tmp_largeur; $x++){
                        $rgb = imagecolorat($tmp_img, $x, $y);
                        if($rgb != $tmp_blanc){
                            $toutblanc = false;
                            if($sy == -1)
                                $sy = $y;
                            else
                                $ey = $y;

                            if($sx == -1)
                                $sx = $x;
                            else{
                                if($x < $sx)
                                    $sx = $x;
                                else if($x > $ex)
                                    $ex = $x;
                            }
                        }
                    }
                }
                $nx = abs($ex - $sx);
                $ny = abs($ey - $sy);
                if($toutblanc){
                    $img = imagecreate(1, max($haut, 1));
                    $blanc = imagecolorallocate($img, 255, 255, 255);
                    $blanc = imagecolortransparent($img, $blanc);
                    imagefilledrectangle($img, 0, 0, 1, $haut, $blanc);
                }else{
                    $img = imagecreate(max($nx + 4, 1), max($ny + 4, 1));
                    $blanc = imagecolorallocate($img, 255, 255, 255);
                    $blanc = imagecolortransparent($img, $blanc);
                    imagefilledrectangle($img, 0, 0, $nx + 4, $ny + 4, $blanc);
                    imagecopy($img, $tmp_img, 2, 2, $sx, $sy, min($nx + 2, $tmp_largeur - $sx), min($ny + 2, $tmp_hauteur - $sy));
                }
                break;
            default:
                if(isset($this->fontesmath[$texte]))
                    $font = $this->dirfonts."/".$this->fontesmath[$texte].".ttf";
                elseif($this->est_nombre($texte))
                    $font = $this->dirfonts."/cmr10.ttf";
                else
                    $font = $this->dirfonts."/cmmi10.ttf";
                $t = 6;
                if(isset($this->symboles[$texte]))
                    $texte = $this->symboles[$texte];
                do{
                    $tmp_dim = imagettfbbox($t, 0, $font, $texte);
                    $t+=1;
                }while((abs($tmp_dim[3] - $tmp_dim[5]) < $haut));
                $tmp_largeur = abs($tmp_dim[2] - $tmp_dim[0]) * 2;
                $tmp_hauteur = abs($tmp_dim[3] - $tmp_dim[5]) * 2;
                $tmp_img = imagecreate(max($tmp_largeur, 1), max($tmp_hauteur, 1));
                $tmp_noir = imagecolorallocate($tmp_img, 0, 0, 0);
                $tmp_blanc = imagecolorallocate($tmp_img, 255, 255, 255);
                $tmp_blanc = imagecolortransparent($tmp_img, $tmp_blanc);
                imagefilledrectangle($tmp_img, 0, 0, $tmp_largeur, $tmp_hauteur, $tmp_blanc);
                imagettftext($tmp_img, $t, 0, 0, $tmp_hauteur / 4, $tmp_noir, $font, $texte);
                //  ImageTTFText($tmp_img, $t, 0,5,5,$tmp_noir, $font,$texte);
                //  $img=$tmp_img;
                $toutblanc = true;
                $sx = $sy = $ex = $ey = -1;
                for($y = 0; $y < $tmp_hauteur; $y++){
                    for($x = 0; $x < $tmp_largeur; $x++){
                        $rgb = imagecolorat($tmp_img, $x, $y);
                        if($rgb != $tmp_blanc){
                            $toutblanc = false;
                            if($sy == -1)
                                $sy = $y;
                            else
                                $ey = $y;

                            if($sx == -1)
                                $sx = $x;
                            else{
                                if($x < $sx)
                                    $sx = $x;
                                else if($x > $ex)
                                    $ex = $x;
                            }
                        }
                    }
                }
                $nx = abs($ex - $sx);
                $ny = abs($ey - $sy);
                if($toutblanc){
                    $img = imagecreate(1, max($haut, 1));
                    $blanc = imagecolorallocate($img, 255, 255, 255);
                    $blanc = imagecolortransparent($img, $blanc);
                    imagefilledrectangle($img, 0, 0, 1, $haut, $blanc);
                }else{
                    $img = imagecreate(max($nx + 4, 1), max($ny + 4, 1));
                    $blanc = imagecolorallocate($img, 255, 255, 255);
                    $blanc = imagecolortransparent($img, $blanc);
                    imagefilledrectangle($img, 0, 0, $nx + 4, $ny + 4, $blanc);
                    imagecopy($img, $tmp_img, 2, 2, $sx, $sy, min($nx + 2, $tmp_largeur - $sx), min($ny + 2, $tmp_hauteur - $sy));
                }
                break;
        }
        //$rouge=ImageColorAllocate($img,255,0,0);
        //ImageRectangle($img,0,0,ImageSX($img)-1,ImageSY($img)-1,$rouge);
        return $img;
    }

    /**
     *
     *
     * @param unknown $texte
     * @param unknown $taille
     * @return unknown
     * @fixme not used anywhere?
     */
    protected function affiche_texte($texte, $taille){
        $taille = max($taille, 6);
        $texte = stripslashes($texte);
        $font = $this->dirfonts."/cmr10.ttf";
        $htexte = 'dg'.$texte;
        $hdim = imagettfbbox($taille, 0, $font, $htexte);
        $wdim = imagettfbbox($taille, 0, $font, $texte);
        $dx = max($wdim[2], $wdim[4]) - min($wdim[0], $wdim[6]) + ceil($taille / 8);
        $dy = max($hdim[1], $hdim[3]) - min($hdim[5], $hdim[7]) + ceil($taille / 8);
        $img = imagecreate(max($dx, 1), max($dy, 1));
        $noir = imagecolorallocate($img, 0, 0, 0);
        $blanc = imagecolorallocate($img, 255, 255, 255);
        $blanc = imagecolortransparent($img, $blanc);
        imagefilledrectangle($img, 0, 0, $dx, $dy, $blanc);
        //ImageRectangle($img,0,0,$dx-1,$dy-1,$noir);
        imagettftext($img, $taille, $angle, 0, -min($hdim[5], $hdim[7]), $noir, $font, $texte);
        return $img;
    }

    /**
     *
     *
     * @param unknown $texte
     * @param unknown $taille
     * @return unknown
     */
    protected function affiche_math($texte, $taille){
        $taille = max($taille, 6);
        $texte = stripslashes($texte);
        if(isset($this->fontesmath[$texte]))
            $font = $this->dirfonts."/".$this->fontesmath[$texte].".ttf";
        elseif(ereg("[a-zA-Z]", $texte))
            $font = $this->dirfonts."/FreeSerifItalic.ttf";
        else
            $font = $this->dirfonts."/FreeSerif.ttf";
        if(isset($this->symboles[$texte]))
            $texte = $this->symboles[$texte];
        $htexte = 'dg'.$texte;
        $hdim = imagettfbbox($taille, 0, $font, $htexte);
        $wdim = imagettfbbox($taille, 0, $font, $texte);
        $dx = max($wdim[2], $wdim[4]) - min($wdim[0], $wdim[6]) + ceil($taille / 8);
        $dy = max($hdim[1], $hdim[3]) - min($hdim[5], $hdim[7]) + ceil($taille / 8);
        $img = imagecreate(max($dx, 1), max($dy, 1));
        $noir = imagecolorallocate($img, 0, 0, 0);
        $blanc = imagecolorallocate($img, 255, 255, 255);
        $blanc = imagecolortransparent($img, $blanc);
        imagefilledrectangle($img, 0, 0, $dx, $dy, $blanc);
        //ImageRectangle($img,0,0,$dx-1,$dy-1,$noir);
        imagettftext($img, $taille, 0, 0, -min($hdim[5], $hdim[7]), $noir, $font, $texte);
        return $img;
    }

    /**
     *
     *
     * @param unknown $hauteur
     * @param unknown $style
     * @return unknown
     */
    protected function parenthese($hauteur, $style){
        $image = $this->affiche_symbol($style, $hauteur);
        return $image;
    }

    /**
     *
     *
     * @param unknown $image1
     * @param unknown $base1
     * @param unknown $image2
     * @param unknown $base2
     * @return unknown
     */
    protected function alignement2($image1, $base1, $image2, $base2){
        $largeur1 = imagesx($image1);
        $hauteur1 = imagesy($image1);
        $largeur2 = imagesx($image2);
        $hauteur2 = imagesy($image2);
        $dessus = max($base1, $base2);
        $dessous = max($hauteur1 - $base1, $hauteur2 - $base2);
        $largeur = $largeur1 + $largeur2;
        $hauteur = $dessus + $dessous;
        $result = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($result, 0, 0, 0);
        $blanc = imagecolorallocate($result, 255, 255, 255);
        $blanc = imagecolortransparent($result, $blanc);
        imagefilledrectangle($result, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagecopy($result, $image1, 0, $dessus - $base1, 0, 0, $largeur1, $hauteur1);
        imagecopy($result, $image2, $largeur1, $dessus - $base2, 0, 0, $largeur2, $hauteur2);
        //ImageRectangle($result,0,0,$largeur-1,$hauteur-1,$noir);
        return $result;
    }

    /**
     *
     *
     * @param unknown $image1
     * @param unknown $base1
     * @param unknown $image2
     * @param unknown $base2
     * @param unknown $image3
     * @param unknown $base3
     * @return unknown
     */
    protected function alignement3($image1, $base1, $image2, $base2, $image3, $base3){
        $largeur1 = imagesx($image1);
        $hauteur1 = imagesy($image1);
        $largeur2 = imagesx($image2);
        $hauteur2 = imagesy($image2);
        $largeur3 = imagesx($image3);
        $hauteur3 = imagesy($image3);
        $dessus = max($base1, $base2, $base3);
        $dessous = max($hauteur1 - $base1, $hauteur2 - $base2, $hauteur3 - $base3);
        $largeur = $largeur1 + $largeur2 + $largeur3;
        $hauteur = $dessus + $dessous;
        $result = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($result, 0, 0, 0);
        $blanc = imagecolorallocate($result, 255, 255, 255);
        $blanc = imagecolortransparent($result, $blanc);
        imagefilledrectangle($result, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagecopy($result, $image1, 0, $dessus - $base1, 0, 0, $largeur1, $hauteur1);
        imagecopy($result, $image2, $largeur1, $dessus - $base2, 0, 0, $largeur2, $hauteur2);
        imagecopy($result, $image3, $largeur1 + $largeur2, $dessus - $base3, 0, 0, $largeur3, $hauteur3);
        //ImageRectangle($result,0,0,$largeur-1,$hauteur-1,$noir);
        return $result;
    }

}

