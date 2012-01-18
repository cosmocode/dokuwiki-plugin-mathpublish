<?php
class PMP_expression_texte extends PMP_expression {

    /**
     *
     *
     * @param unknown $exp
     */
    public function __construct($exp) {
        parent::__construct();
        $this->texte = $exp;
    }


    /**
     *
     *
     * @param unknown $taille
     */
    public function dessine($taille) {
        $this->image = $this->affiche_math($this->texte, $taille);
        $this->base_verticale = imagesy($this->image) / 2;
    }


}
