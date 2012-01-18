<?php

/**
 * PHPMathPublisher math expression class
 * 
 * @license GPL 2
 * @author  Pascal Brachet <pbrachet [at] xm1math.net>
 * @author  Andreas Gohr <gohr@cosmocode.de>
 * @link    http://www.xm1math.net/phpmathpublisher/
 */
class PMP_expression_math extends PMP_expression{

    protected $noeuds;

    /**
     *
     *
     * @param unknown $exp
     */
    public function __construct($exp){
        parent::__construct();
        $this->texte = "&$";
        $this->noeuds = $exp;
        $this->noeuds = $this->parse();
    }

    /**
     *
     *
     * @return unknown
     */
    protected function parse(){
        if(count($this->noeuds) <= 3)
            return $this->noeuds;
        $ret = array();
        $parentheses = array();
        for($i = 0; $i < count($this->noeuds); $i++){
            if($this->noeuds[$i]->texte == '(' || $this->noeuds[$i]->texte == '{'){
                array_push($parentheses, $i);
            }elseif($this->noeuds[$i]->texte == ')' || $this->noeuds[$i]->texte == '}'){
                $pos = array_pop($parentheses);
                if(count($parentheses) == 0){
                    $sub = array_slice($this->noeuds, $pos + 1, $i - $pos - 1);
                    if($this->noeuds[$i]->texte == ')'){
                        $ret[] = new PMP_expression_math(array(new PMP_expression_texte("("), new PMP_expression_math($sub), new PMP_expression_texte(")")));
                    }else{
                        $ret[] = new PMP_expression_math($sub);
                    }
                }
            }elseif(count($parentheses) == 0){
                $ret[] = $this->noeuds[$i];
            }
        }
        $ret = $this->traite_fonction($ret, 'sqrt', 1);
        $ret = $this->traite_fonction($ret, 'vec', 1);
        $ret = $this->traite_fonction($ret, 'overline', 1);
        $ret = $this->traite_fonction($ret, 'underline', 1);
        $ret = $this->traite_fonction($ret, 'hat', 1);
        $ret = $this->traite_fonction($ret, 'int', 3);
        $ret = $this->traite_fonction($ret, 'doubleint', 3);
        $ret = $this->traite_fonction($ret, 'tripleint', 3);
        $ret = $this->traite_fonction($ret, 'oint', 3);
        $ret = $this->traite_fonction($ret, 'prod', 3);
        $ret = $this->traite_fonction($ret, 'sum', 3);
        $ret = $this->traite_fonction($ret, 'bigcup', 3);
        $ret = $this->traite_fonction($ret, 'bigcap', 3);
        $ret = $this->traite_fonction($ret, 'delim', 3);
        $ret = $this->traite_fonction($ret, 'lim', 2);
        $ret = $this->traite_fonction($ret, 'root', 2);
        $ret = $this->traite_fonction($ret, 'matrix', 3);
        $ret = $this->traite_fonction($ret, 'tabular', 3);

        $ret = $this->traite_operation($ret, '^');
        $ret = $this->traite_operation($ret, 'over');
        $ret = $this->traite_operation($ret, '_');
        $ret = $this->traite_operation($ret, 'under');
        $ret = $this->traite_operation($ret, '*');
        $ret = $this->traite_operation($ret, '/');
        $ret = $this->traite_operation($ret, '+');
        $ret = $this->traite_operation($ret, '-');
        return $ret;
    }

    /**
     *
     *
     * @param unknown $noeuds
     * @param unknown $operation
     * @return unknown
     */
    protected function traite_operation($noeuds, $operation){
        do{
            $change = false;
            if(count($noeuds) <= 3)
                return $noeuds;
            $ret = array();
            for($i = 0; $i < count($noeuds); $i++){
                if(!$change && $i < count($noeuds) - 2 && $noeuds[$i + 1]->texte == $operation){
                    $ret[] = new PMP_expression_math(array($noeuds[$i], $noeuds[$i + 1], $noeuds[$i + 2]));
                    $i += 2;
                    $change = true;
                }
                else
                    $ret[] = $noeuds[$i];
            }
            $noeuds = $ret;
        }


        while($change);
        return $ret;
    }

    /**
     *
     *
     * @param unknown $noeuds
     * @param unknown $fonction
     * @param unknown $nbarg
     * @return unknown
     */
    protected function traite_fonction($noeuds, $fonction, $nbarg){
        if(count($noeuds) <= $nbarg + 1)
            return $noeuds;
        $ret = array();
        for($i = 0; $i < count($noeuds); $i++){
            if($i < count($noeuds) - $nbarg && $noeuds[$i]->texte == $fonction){
                $a = array();
                for($j = $i; $j <= $i + $nbarg; $j++)
                    $a[] = $noeuds[$j];
                $ret[] = new PMP_expression_math($a);
                $i += $nbarg;
            }
            else
                $ret[] = $noeuds[$i];
        }
        return $ret;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    public function dessine($taille){
        switch(count($this->noeuds)){
            case 1:
                $this->noeuds[0]->dessine($taille);
                $this->image = $this->noeuds[0]->image;
                $this->base_verticale = $this->noeuds[0]->base_verticale;
                break;
            case 2:
                switch($this->noeuds[0]->texte){
                    case 'sqrt':
                        $this->dessine_racine($taille);
                        break;
                    case 'vec':
                        $this->dessine_vecteur($taille);
                        break;
                    case 'overline':
                        $this->dessine_overline($taille);
                        break;
                    case 'underline':
                        $this->dessine_underline($taille);
                        break;
                    case 'hat':
                        $this->dessine_chapeau($taille);
                        break;
                    default:
                        $this->dessine_expression($taille);
                        break;
                }
                break;
            case 3:
                if($this->noeuds[0]->texte == "lim"){
                    $this->dessine_limite($taille);
                }elseif($this->noeuds[0]->texte == "root"){
                    $this->dessine_root($taille);
                }else{
                    switch($this->noeuds[1]->texte){
                        case '/':
                            $this->dessine_fraction($taille);
                            break;
                        case '^':
                            $this->dessine_exposant($taille);
                            break;
                        case 'over':
                            $this->dessine_dessus($taille);
                            break;
                        case '_':
                            $this->dessine_indice($taille);
                            break;
                        case 'under':
                            $this->dessine_dessous($taille);
                            break;
                        default:
                            $this->dessine_expression($taille);
                            break;
                    }
                }
                break;
            case 4:
                switch($this->noeuds[0]->texte){
                    case 'int':
                        $this->dessine_grandoperateur($taille, '_integrale');
                        break;
                    case 'doubleint':
                        $this->dessine_grandoperateur($taille, '_dintegrale');
                        break;
                    case 'tripleint':
                        $this->dessine_grandoperateur($taille, '_tintegrale');
                        break;
                    case 'oint':
                        $this->dessine_grandoperateur($taille, '_ointegrale');
                        break;
                    case 'sum':
                        $this->dessine_grandoperateur($taille, '_somme');
                        break;
                    case 'prod':
                        $this->dessine_grandoperateur($taille, '_produit');
                        break;
                    case 'bigcap':
                        $this->dessine_grandoperateur($taille, '_intersection');
                        break;
                    case 'bigcup':
                        $this->dessine_grandoperateur($taille, '_reunion');
                        break;
                    case 'delim':
                        $this->dessine_delimiteur($taille);
                        break;
                    case 'matrix':
                        $this->dessine_matrice($taille);
                        break;
                    case 'tabular':
                        $this->dessine_tableau($taille);
                        break;
                    default:
                        $this->dessine_expression($taille);
                        break;
                }
                break;
            default:
                $this->dessine_expression($taille);
                break;
        }
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_expression($taille){
        $largeur = 1;
        $hauteur = 1;
        $dessus = 1;
        $dessous = 1;
        for($i = 0; $i < count($this->noeuds); $i++){
            if($this->noeuds[$i]->texte != '(' && $this->noeuds[$i]->texte != ')'){
                $this->noeuds[$i]->dessine($taille);
                $img[$i] = $this->noeuds[$i]->image;
                $base[$i] = $this->noeuds[$i]->base_verticale;
                $dessus = max($base[$i], $dessus);
                $dessous = max(imagesy($img[$i]) - $base[$i], $dessous);
            }
        }
        $hauteur = $dessus + $dessous;
        $paro = $this->parenthese(max($dessus, $dessous) * 2, "(");
        $parf = $this->parenthese(max($dessus, $dessous) * 2, ")");
        for($i = 0; $i < count($this->noeuds); $i++){
            if(!isset($img[$i])){
                if($this->noeuds[$i]->texte == "(")
                    $img[$i] = $paro;
                else
                    $img[$i] = $parf;
                $dessus = max(imagesy($img[$i]) / 2, $dessus);
                $base[$i] = imagesy($img[$i]) / 2;
                $dessous = max(imagesy($img[$i]) - $base[$i], $dessous);
                $hauteur = max(imagesy($img[$i]), $hauteur);
            }
            $largeur+=imagesx($img[$i]);
        }
        $this->base_verticale = $dessus;
        $result = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($result, 0, 0, 0);
        $blanc = imagecolorallocate($result, 255, 255, 255);
        $blanc = imagecolortransparent($result, $blanc);
        imagefilledrectangle($result, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        $pos = 0;
        for($i = 0; $i < count($img); $i++){
            if(isset($img[$i])){
                imagecopy($result, $img[$i], $pos, $dessus - $base[$i], 0, 0, imagesx($img[$i]), imagesy($img[$i]));
                $pos += imagesx($img[$i]);
            }
        }
        $this->image = $result;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_fraction($taille){
        $this->noeuds[0]->dessine($taille * 0.9);
        $img1 = $this->noeuds[0]->image;
        $base1 = $this->noeuds[0]->base_verticale;
        $this->noeuds[2]->dessine($taille * 0.9);
        $img2 = $this->noeuds[2]->image;
        $base2 = $this->noeuds[2]->base_verticale;
        $hauteur1 = imagesy($img1);
        $hauteur2 = imagesy($img2);
        $largeur1 = imagesx($img1);
        $largeur2 = imagesx($img2);
        $largeur = max($largeur1, $largeur2);
        $hauteur = $hauteur1 + $hauteur2 + 4;
        $result = imagecreate(max($largeur + 5, 1), max($hauteur, 1));
        $noir = imagecolorallocate($result, 0, 0, 0);
        $blanc = imagecolorallocate($result, 255, 255, 255);
        $blanc = imagecolortransparent($result, $blanc);
        $this->base_verticale = $hauteur1 + 2;
        imagefilledrectangle($result, 0, 0, $largeur + 4, $hauteur - 1, $blanc);
        imagecopy($result, $img1, ($largeur - $largeur1) / 2, 0, 0, 0, $largeur1, $hauteur1);
        imageline($result, 0, $this->base_verticale, $largeur, $this->base_verticale, $noir);
        imagecopy($result, $img2, ($largeur - $largeur2) / 2, $hauteur1 + 4, 0, 0, $largeur2, $hauteur2);
        $this->image = $result;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_exposant($taille){
        $this->noeuds[0]->dessine($taille);
        $img1 = $this->noeuds[0]->image;
        $base1 = $this->noeuds[0]->base_verticale;
        $this->noeuds[2]->dessine($taille * 0.8);
        $img2 = $this->noeuds[2]->image;
        $base2 = $this->noeuds[2]->base_verticale;
        $hauteur1 = imagesy($img1);
        $hauteur2 = imagesy($img2);
        $largeur1 = imagesx($img1);
        $largeur2 = imagesx($img2);
        $largeur = $largeur1 + $largeur2;
        if($hauteur1 >= $hauteur2){
            $hauteur = ceil($hauteur2 / 2 + $hauteur1);
            $this->base_verticale = $hauteur2 / 2 + $base1;
            $result = imagecreate(max($largeur, 1), max($hauteur, 1));
            $noir = imagecolorallocate($result, 0, 0, 0);
            $blanc = imagecolorallocate($result, 255, 255, 255);
            $blanc = imagecolortransparent($result, $blanc);
            imagefilledrectangle($result, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
            imagecopy($result, $img1, 0, ceil($hauteur2 / 2), 0, 0, $largeur1, $hauteur1);
            imagecopy($result, $img2, $largeur1, 0, 0, 0, $largeur2, $hauteur2);
        }else{
            $hauteur = ceil($hauteur1 / 2 + $hauteur2);
            $this->base_verticale = $hauteur2 - $base1 + $hauteur1 / 2;
            $result = imagecreate(max($largeur, 1), max($hauteur, 1));
            $noir = imagecolorallocate($result, 0, 0, 0);
            $blanc = imagecolorallocate($result, 255, 255, 255);
            $blanc = imagecolortransparent($result, $blanc);
            imagefilledrectangle($result, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
            imagecopy($result, $img1, 0, ceil($hauteur2 - $hauteur1 / 2), 0, 0, $largeur1, $hauteur1);
            imagecopy($result, $img2, $largeur1, 0, 0, 0, $largeur2, $hauteur2);
        }
        $this->image = $result;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_indice($taille){
        $this->noeuds[0]->dessine($taille);
        $img1 = $this->noeuds[0]->image;
        $base1 = $this->noeuds[0]->base_verticale;
        $this->noeuds[2]->dessine($taille * 0.8);
        $img2 = $this->noeuds[2]->image;
        $base2 = $this->noeuds[2]->base_verticale;
        $hauteur1 = imagesy($img1);
        $hauteur2 = imagesy($img2);
        $largeur1 = imagesx($img1);
        $largeur2 = imagesx($img2);
        $largeur = $largeur1 + $largeur2;
        if($hauteur1 >= $hauteur2){
            $hauteur = ceil($hauteur2 / 2 + $hauteur1);
            $this->base_verticale = $base1;
            $result = imagecreate(max($largeur, 1), max($hauteur, 1));
            $noir = imagecolorallocate($result, 0, 0, 0);
            $blanc = imagecolorallocate($result, 255, 255, 255);
            $blanc = imagecolortransparent($result, $blanc);
            imagefilledrectangle($result, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
            imagecopy($result, $img1, 0, 0, 0, 0, $largeur1, $hauteur1);
            imagecopy($result, $img2, $largeur1, ceil($hauteur1 - $hauteur2 / 2), 0, 0, $largeur2, $hauteur2);
        }else{
            $hauteur = ceil($hauteur1 / 2 + $hauteur2);
            $this->base_verticale = $base1;
            $result = imagecreate(max($largeur, 1), max($hauteur, 1));
            $noir = imagecolorallocate($result, 0, 0, 0);
            $blanc = imagecolorallocate($result, 255, 255, 255);
            $blanc = imagecolortransparent($result, $blanc);
            imagefilledrectangle($result, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
            imagecopy($result, $img1, 0, 0, 0, 0, $largeur1, $hauteur1);
            imagecopy($result, $img2, $largeur1, ceil($hauteur1 / 2), 0, 0, $largeur2, $hauteur2);
        }
        $this->image = $result;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_racine($taille){
        $this->noeuds[1]->dessine($taille);
        $imgexp = $this->noeuds[1]->image;
        $baseexp = $this->noeuds[1]->base_verticale;
        $largeurexp = imagesx($imgexp);
        $hauteurexp = imagesy($imgexp);

        $imgrac = $this->affiche_symbol("_racine", $hauteurexp + 2);
        $largeurrac = imagesx($imgrac);
        $hauteurrac = imagesy($imgrac);
        $baserac = $hauteurrac / 2;

        $largeur = $largeurrac + $largeurexp;
        $hauteur = max($hauteurexp, $hauteurrac);
        $result = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($result, 0, 0, 0);
        $blanc = imagecolorallocate($result, 255, 255, 255);
        $blanc = imagecolortransparent($result, $blanc);
        imagefilledrectangle($result, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagecopy($result, $imgrac, 0, 0, 0, 0, $largeurrac, $hauteurrac);
        imagecopy($result, $imgexp, $largeurrac, $hauteur - $hauteurexp, 0, 0, $largeurexp, $hauteurexp);
        imagesetthickness($result, 1);
        imageline($result, $largeurrac - 2, 2, $largeurrac + $largeurexp + 2, 2, $noir);
        $this->base_verticale = $hauteur - $hauteurexp + $baseexp;
        $this->image = $result;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_root($taille){
        $this->noeuds[1]->dessine($taille * 0.6);
        $imgroot = $this->noeuds[1]->image;
        $baseroot = $this->noeuds[1]->base_verticale;
        $largeurroot = imagesx($imgroot);
        $hauteurroot = imagesy($imgroot);

        $this->noeuds[2]->dessine($taille);
        $imgexp = $this->noeuds[2]->image;
        $baseexp = $this->noeuds[2]->base_verticale;
        $largeurexp = imagesx($imgexp);
        $hauteurexp = imagesy($imgexp);

        $imgrac = $this->affiche_symbol("_racine", $hauteurexp + 2);
        $largeurrac = imagesx($imgrac);
        $hauteurrac = imagesy($imgrac);
        $baserac = $hauteurrac / 2;

        //$largeur=$largeurrac+$largeurexp;
        $netwidthroot = max($largeurroot - 0.3 * $hauteurrac, 0);
        $largeur = $largeurrac + $largeurexp + $netwidthroot;
        $hauteur = max($hauteurexp, $hauteurrac);
        $result = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($result, 0, 0, 0);
        $blanc = imagecolorallocate($result, 255, 255, 255);
        $blanc = imagecolortransparent($result, $blanc);
        imagefilledrectangle($result, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagecopy($result, $imgrac, $netwidthroot, 0, 0, 0, $largeurrac, $hauteurrac);
        imagecopy($result, $imgexp, $largeurrac + $netwidthroot, $hauteur - $hauteurexp, 0, 0, $largeurexp, $hauteurexp);
        imagesetthickness($result, 1);
        imageline($result, $largeurrac + $netwidthroot - 2, 2, $largeurrac + $netwidthroot + $largeurexp + 2, 2, $noir);
        imagecopy($result, $imgroot, 0, 0, 0, 0, $largeurroot, $hauteurroot);
        $this->base_verticale = $hauteur - $hauteurexp + $baseexp;
        $this->image = $result;
    }

    /**
     *
     *
     * @param unknown $taille
     * @param unknown $caractere
     */
    protected function dessine_grandoperateur($taille, $caractere){
        $this->noeuds[1]->dessine($taille * 0.8);
        $img1 = $this->noeuds[1]->image;
        $base1 = $this->noeuds[1]->base_verticale;
        $this->noeuds[2]->dessine($taille * 0.8);
        $img2 = $this->noeuds[2]->image;
        $base2 = $this->noeuds[2]->base_verticale;
        $this->noeuds[3]->dessine($taille);
        $imgexp = $this->noeuds[3]->image;
        $baseexp = $this->noeuds[3]->base_verticale;
        //borneinf
        $largeur1 = imagesx($img1);
        $hauteur1 = imagesy($img1);
        //bornesup
        $largeur2 = imagesx($img2);
        $hauteur2 = imagesy($img2);
        //expression
        $hauteurexp = imagesy($imgexp);
        $largeurexp = imagesx($imgexp);
        //caractere
        $imgsymbole = $this->affiche_symbol($caractere, $baseexp * 1.8); //max($baseexp,$hauteurexp-$baseexp)*2);
        $largeursymbole = imagesx($imgsymbole);
        $hauteursymbole = imagesy($imgsymbole);
        $basesymbole = $hauteursymbole / 2;

        $hauteurgauche = $hauteursymbole + $hauteur1 + $hauteur2;
        $largeurgauche = max($largeursymbole, $largeur1, $largeur2);
        $imggauche = imagecreate(max($largeurgauche, 1), max($hauteurgauche, 1));
        $noir = imagecolorallocate($imggauche, 0, 0, 0);
        $blanc = imagecolorallocate($imggauche, 255, 255, 255);
        $blanc = imagecolortransparent($imggauche, $blanc);
        imagefilledrectangle($imggauche, 0, 0, $largeurgauche - 1, $hauteurgauche - 1, $blanc);
        imagecopy($imggauche, $imgsymbole, ($largeurgauche - $largeursymbole) / 2, $hauteur2, 0, 0, $largeursymbole, $hauteursymbole);
        imagecopy($imggauche, $img2, ($largeurgauche - $largeur2) / 2, 0, 0, 0, $largeur2, $hauteur2);
        imagecopy($imggauche, $img1, ($largeurgauche - $largeur1) / 2, $hauteur2 + $hauteursymbole, 0, 0, $largeur1, $hauteur1);
        $imgfin = $this->alignement2($imggauche, $basesymbole + $hauteur2, $imgexp, $baseexp);
        $this->image = $imgfin;
        $this->base_verticale = max($basesymbole + $hauteur2, $baseexp + $hauteur2);
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_dessus($taille){
        $this->noeuds[2]->dessine($taille * 0.8);
        $imgsup = $this->noeuds[2]->image;
        $basesup = $this->noeuds[2]->base_verticale;
        $this->noeuds[0]->dessine($taille);
        $imgexp = $this->noeuds[0]->image;
        $baseexp = $this->noeuds[0]->base_verticale;
        //expression
        $largeurexp = imagesx($imgexp);
        $hauteurexp = imagesy($imgexp);
        //bornesup
        $largeursup = imagesx($imgsup);
        $hauteursup = imagesy($imgsup);
        //fin
        $hauteur = $hauteurexp + $hauteursup;
        $largeur = max($largeursup, $largeurexp) + ceil($taille / 8);
        $imgfin = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($imgfin, 0, 0, 0);
        $blanc = imagecolorallocate($imgfin, 255, 255, 255);
        $blanc = imagecolortransparent($imgfin, $blanc);
        imagefilledrectangle($imgfin, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagecopy($imgfin, $imgsup, ($largeur - $largeursup) / 2, 0, 0, 0, $largeursup, $hauteursup);
        imagecopy($imgfin, $imgexp, ($largeur - $largeurexp) / 2, $hauteursup, 0, 0, $largeurexp, $hauteurexp);
        $this->image = $imgfin;
        $this->base_verticale = $baseexp + $hauteursup;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_dessous($taille){
        $this->noeuds[2]->dessine($taille * 0.8);
        $imginf = $this->noeuds[2]->image;
        $baseinf = $this->noeuds[2]->base_verticale;
        $this->noeuds[0]->dessine($taille);
        $imgexp = $this->noeuds[0]->image;
        $baseexp = $this->noeuds[0]->base_verticale;
        //expression
        $largeurexp = imagesx($imgexp);
        $hauteurexp = imagesy($imgexp);
        //borneinf
        $largeurinf = imagesx($imginf);
        $hauteurinf = imagesy($imginf);
        //fin
        $hauteur = $hauteurexp + $hauteurinf;
        $largeur = max($largeurinf, $largeurexp) + ceil($taille / 8);
        $imgfin = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($imgfin, 0, 0, 0);
        $blanc = imagecolorallocate($imgfin, 255, 255, 255);
        $blanc = imagecolortransparent($imgfin, $blanc);
        imagefilledrectangle($imgfin, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagecopy($imgfin, $imgexp, ($largeur - $largeurexp) / 2, 0, 0, 0, $largeurexp, $hauteurexp);
        imagecopy($imgfin, $imginf, ($largeur - $largeurinf) / 2, $hauteurexp, 0, 0, $largeurinf, $hauteurinf);
        $this->image = $imgfin;
        $this->base_verticale = $baseexp;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_matrice($taille){
        $padding = 8;
        $nbligne = $this->noeuds[1]->noeuds[0]->texte;
        $nbcolonne = $this->noeuds[2]->noeuds[0]->texte;
        $largeur_case = 0;
        $hauteur_case = 0;

        for($ligne = 0; $ligne < $nbligne; $ligne++){
            $hauteur_ligne[$ligne] = 0;
            $dessus_ligne[$ligne] = 0;
        }
        for($col = 0; $col < $nbcolonne; $col++){
            $largeur_colonne[$col] = 0;
        }
        $i = 0;
        for($ligne = 0; $ligne < $nbligne; $ligne++){
            for($col = 0; $col < $nbcolonne; $col++){
                if($i < count($this->noeuds[3]->noeuds)){
                    $this->noeuds[3]->noeuds[$i]->dessine($taille * 0.9);
                    $img[$i] = $this->noeuds[3]->noeuds[$i]->image;
                    $base[$i] = $this->noeuds[3]->noeuds[$i]->base_verticale;
                    $dessus_ligne[$ligne] = max($base[$i], $dessus_ligne[$ligne]);
                    $largeur[$i] = imagesx($img[$i]);
                    $hauteur[$i] = imagesy($img[$i]);
                    $hauteur_ligne[$ligne] = max($hauteur_ligne[$ligne], $hauteur[$i]);
                    $largeur_colonne[$col] = max($largeur_colonne[$col], $largeur[$i]);
                }
                $i++;
            }
        }

        $hauteurfin = 0;
        $largeurfin = 0;
        for($ligne = 0; $ligne < $nbligne; $ligne++){
            $hauteurfin+=$hauteur_ligne[$ligne] + $padding;
        }
        for($col = 0; $col < $nbcolonne; $col++){
            $largeurfin+=$largeur_colonne[$col] + $padding;
        }
        $hauteurfin-=$padding;
        $largeurfin-=$padding;
        $imgfin = imagecreate(max($largeurfin, 1), max($hauteurfin, 1));
        $noir = imagecolorallocate($imgfin, 0, 0, 0);
        $blanc = imagecolorallocate($imgfin, 255, 255, 255);
        $blanc = imagecolortransparent($imgfin, $blanc);
        imagefilledrectangle($imgfin, 0, 0, $largeurfin - 1, $hauteurfin - 1, $blanc);
        $i = 0;
        $h = $padding / 2 - 1;
        for($ligne = 0; $ligne < $nbligne; $ligne++){
            $l = $padding / 2 - 1;
            for($col = 0; $col < $nbcolonne; $col++){
                if($i < count($this->noeuds[3]->noeuds)){
                    imagecopy($imgfin, $img[$i], $l + ceil($largeur_colonne[$col] - $largeur[$i]) / 2, $h + $dessus_ligne[$ligne] - $base[$i], 0, 0, $largeur[$i], $hauteur[$i]);
                    //ImageRectangle($imgfin,$l,$h,$l+$largeur_colonne[$col],$h+$hauteur_ligne[$ligne],$noir);
                }
                $l+=$largeur_colonne[$col] + $padding;
                $i++;
            }
            $h+=$hauteur_ligne[$ligne] + $padding;
        }
        //ImageRectangle($imgfin,0,0,$largeurfin-1,$hauteurfin-1,$noir);
        $this->image = $imgfin;
        $this->base_verticale = imagesy($imgfin) / 2;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_tableau($taille){
        $padding = 8;
        $typeligne = $this->noeuds[1]->noeuds[0]->texte;
        $typecolonne = $this->noeuds[2]->noeuds[0]->texte;
        $nbligne = strlen($typeligne) - 1;
        $nbcolonne = strlen($typecolonne) - 1;
        $largeur_case = 0;
        $hauteur_case = 0;

        for($ligne = 0; $ligne < $nbligne; $ligne++){
            $hauteur_ligne[$ligne] = 0;
            $dessus_ligne[$ligne] = 0;
        }
        for($col = 0; $col < $nbcolonne; $col++){
            $largeur_colonne[$col] = 0;
        }
        $i = 0;
        for($ligne = 0; $ligne < $nbligne; $ligne++){
            for($col = 0; $col < $nbcolonne; $col++){
                if($i < count($this->noeuds[3]->noeuds)){
                    $this->noeuds[3]->noeuds[$i]->dessine($taille * 0.9);
                    $img[$i] = $this->noeuds[3]->noeuds[$i]->image;
                    $base[$i] = $this->noeuds[3]->noeuds[$i]->base_verticale;
                    $dessus_ligne[$ligne] = max($base[$i], $dessus_ligne[$ligne]);
                    $largeur[$i] = imagesx($img[$i]);
                    $hauteur[$i] = imagesy($img[$i]);
                    $hauteur_ligne[$ligne] = max($hauteur_ligne[$ligne], $hauteur[$i]);
                    $largeur_colonne[$col] = max($largeur_colonne[$col], $largeur[$i]);
                }
                $i++;
            }
        }

        $hauteurfin = 0;
        $largeurfin = 0;
        for($ligne = 0; $ligne < $nbligne; $ligne++){
            $hauteurfin+=$hauteur_ligne[$ligne] + $padding;
        }
        for($col = 0; $col < $nbcolonne; $col++){
            $largeurfin+=$largeur_colonne[$col] + $padding;
        }
        $imgfin = imagecreate(max($largeurfin, 1), max($hauteurfin, 1));
        $noir = imagecolorallocate($imgfin, 0, 0, 0);
        $blanc = imagecolorallocate($imgfin, 255, 255, 255);
        $blanc = imagecolortransparent($imgfin, $blanc);
        imagefilledrectangle($imgfin, 0, 0, $largeurfin - 1, $hauteurfin - 1, $blanc);
        $i = 0;
        $h = $padding / 2 - 1;
        if(substr($typeligne, 0, 1) == "1")
            imageline($imgfin, 0, 0, $largeurfin - 1, 0, $noir);
        for($ligne = 0; $ligne < $nbligne; $ligne++){
            $l = $padding / 2 - 1;
            if(substr($typecolonne, 0, 1) == "1")
                imageline($imgfin, 0, $h - $padding / 2, 0, $h + $hauteur_ligne[$ligne] + $padding / 2, $noir);
            for($col = 0; $col < $nbcolonne; $col++){
                if($i < count($this->noeuds[3]->noeuds)){
                    imagecopy($imgfin, $img[$i], $l + ceil($largeur_colonne[$col] - $largeur[$i]) / 2, $h + $dessus_ligne[$ligne] - $base[$i], 0, 0, $largeur[$i], $hauteur[$i]);
                    if(substr($typecolonne, $col + 1, 1) == "1")
                        imageline($imgfin, $l + $largeur_colonne[$col] + $padding / 2, $h - $padding / 2, $l + $largeur_colonne[$col] + $padding / 2, $h + $hauteur_ligne[$ligne] + $padding / 2, $noir);
                }
                $l+=$largeur_colonne[$col] + $padding;
                $i++;
            }
            if(substr($typeligne, $ligne + 1, 1) == "1")
                imageline($imgfin, 0, $h + $hauteur_ligne[$ligne] + $padding / 2, $largeurfin - 1, $h + $hauteur_ligne[$ligne] + $padding / 2, $noir);
            $h+=$hauteur_ligne[$ligne] + $padding;
        }
        $this->image = $imgfin;
        $this->base_verticale = imagesy($imgfin) / 2;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_vecteur($taille){
        //expression
        $this->noeuds[1]->dessine($taille);
        $imgexp = $this->noeuds[1]->image;
        $baseexp = $this->noeuds[1]->base_verticale;
        $largeurexp = imagesx($imgexp);
        $hauteurexp = imagesy($imgexp);
        //fleche
        $imgsup = $this->affiche_symbol("right", 16);
        $largeursup = imagesx($imgsup);
        $hauteursup = imagesy($imgsup);
        //fin
        $hauteur = $hauteurexp + $hauteursup;
        $largeur = $largeurexp;
        $imgfin = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($imgfin, 0, 0, 0);
        $blanc = imagecolorallocate($imgfin, 255, 255, 255);
        $blanc = imagecolortransparent($imgfin, $blanc);
        imagefilledrectangle($imgfin, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagecopy($imgfin, $imgsup, $largeur - 6, 0, $largeursup - 6, 0, $largeursup, $hauteursup);
        imagesetthickness($imgfin, 1);
        imageline($imgfin, 0, 6, $largeur - 4, 6, $noir);
        imagecopy($imgfin, $imgexp, ($largeur - $largeurexp) / 2, $hauteursup, 0, 0, $largeurexp, $hauteurexp);
        $this->image = $imgfin;
        $this->base_verticale = $baseexp + $hauteursup;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_overline($taille){
        //expression
        $this->noeuds[1]->dessine($taille);
        $imgexp = $this->noeuds[1]->image;
        $baseexp = $this->noeuds[1]->base_verticale;
        $largeurexp = imagesx($imgexp);
        $hauteurexp = imagesy($imgexp);

        $hauteur = $hauteurexp + 2;
        $largeur = $largeurexp;
        $imgfin = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($imgfin, 0, 0, 0);
        $blanc = imagecolorallocate($imgfin, 255, 255, 255);
        $blanc = imagecolortransparent($imgfin, $blanc);
        imagefilledrectangle($imgfin, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagesetthickness($imgfin, 1);
        imageline($imgfin, 0, 1, $largeur, 1, $noir);
        imagecopy($imgfin, $imgexp, 0, 2, 0, 0, $largeurexp, $hauteurexp);
        $this->image = $imgfin;
        $this->base_verticale = $baseexp + 2;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_underline($taille){
        //expression
        $this->noeuds[1]->dessine($taille);
        $imgexp = $this->noeuds[1]->image;
        $baseexp = $this->noeuds[1]->base_verticale;
        $largeurexp = imagesx($imgexp);
        $hauteurexp = imagesy($imgexp);

        $hauteur = $hauteurexp + 2;
        $largeur = $largeurexp;
        $imgfin = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($imgfin, 0, 0, 0);
        $blanc = imagecolorallocate($imgfin, 255, 255, 255);
        $blanc = imagecolortransparent($imgfin, $blanc);
        imagefilledrectangle($imgfin, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagesetthickness($imgfin, 1);
        imageline($imgfin, 0, $hauteurexp + 1, $largeur, $hauteurexp + 1, $noir);
        imagecopy($imgfin, $imgexp, 0, 0, 0, 0, $largeurexp, $hauteurexp);
        $this->image = $imgfin;
        $this->base_verticale = $baseexp;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_chapeau($taille){

        $imgsup = $this->affiche_symbol("_hat", $taille);

        $this->noeuds[1]->dessine($taille);
        $imgexp = $this->noeuds[1]->image;
        $baseexp = $this->noeuds[1]->base_verticale;
        //expression
        $largeurexp = imagesx($imgexp);
        $hauteurexp = imagesy($imgexp);
        //bornesup
        $largeursup = imagesx($imgsup);
        $hauteursup = imagesy($imgsup);
        //fin
        $hauteur = $hauteurexp + $hauteursup;
        $largeur = max($largeursup, $largeurexp) + ceil($taille / 8);
        $imgfin = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($imgfin, 0, 0, 0);
        $blanc = imagecolorallocate($imgfin, 255, 255, 255);
        $blanc = imagecolortransparent($imgfin, $blanc);
        imagefilledrectangle($imgfin, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagecopy($imgfin, $imgsup, ($largeur - $largeursup) / 2, 0, 0, 0, $largeursup, $hauteursup);
        imagecopy($imgfin, $imgexp, ($largeur - $largeurexp) / 2, $hauteursup, 0, 0, $largeurexp, $hauteurexp);
        $this->image = $imgfin;
        $this->base_verticale = $baseexp + $hauteursup;
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_limite($taille){
        $imglim = $this->affiche_math("_lim", $taille);
        $largeurlim = imagesx($imglim);
        $hauteurlim = imagesy($imglim);
        $baselim = $hauteurlim / 2;

        $this->noeuds[1]->dessine($taille * 0.8);
        $imginf = $this->noeuds[1]->image;
        $baseinf = $this->noeuds[1]->base_verticale;
        $largeurinf = imagesx($imginf);
        $hauteurinf = imagesy($imginf);

        $this->noeuds[2]->dessine($taille);
        $imgexp = $this->noeuds[2]->image;
        $baseexp = $this->noeuds[2]->base_verticale;
        $largeurexp = imagesx($imgexp);
        $hauteurexp = imagesy($imgexp);

        $hauteur = $hauteurlim + $hauteurinf;
        $largeur = max($largeurinf, $largeurlim) + ceil($taille / 8);
        $imgfin = imagecreate(max($largeur, 1), max($hauteur, 1));
        $noir = imagecolorallocate($imgfin, 0, 0, 0);
        $blanc = imagecolorallocate($imgfin, 255, 255, 255);
        $blanc = imagecolortransparent($imgfin, $blanc);
        imagefilledrectangle($imgfin, 0, 0, $largeur - 1, $hauteur - 1, $blanc);
        imagecopy($imgfin, $imglim, ($largeur - $largeurlim) / 2, 0, 0, 0, $largeurlim, $hauteurlim);
        imagecopy($imgfin, $imginf, ($largeur - $largeurinf) / 2, $hauteurlim, 0, 0, $largeurinf, $hauteurinf);

        $this->image = $this->alignement2($imgfin, $baselim, $imgexp, $baseexp);
        $this->base_verticale = max($baselim, $baseexp);
    }

    /**
     *
     *
     * @param unknown $taille
     */
    protected function dessine_delimiteur($taille){
        $this->noeuds[2]->dessine($taille);
        $imgexp = $this->noeuds[2]->image;
        $baseexp = $this->noeuds[2]->base_verticale;
        $hauteurexp = imagesy($imgexp);
        if($this->noeuds[1]->texte == "&$")
            $imggauche = $this->parenthese($hauteurexp, $this->noeuds[1]->noeuds[0]->texte);
        else
            $imggauche = $this->parenthese($hauteurexp, $this->noeuds[1]->texte);
        $basegauche = imagesy($imggauche) / 2;
        if($this->noeuds[3]->texte == "&$")
            $imgdroit = $this->parenthese($hauteurexp, $this->noeuds[3]->noeuds[0]->texte);
        else
            $imgdroit = $this->parenthese($hauteurexp, $this->noeuds[3]->texte);
        $basedroit = imagesy($imgdroit) / 2;
        $this->image = $this->alignement3($imggauche, $basegauche, $imgexp, $baseexp, $imgdroit, $basedroit);
        $this->base_verticale = max($basegauche, $baseexp, $basedroit);
    }

}

