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
 * \RL\PhpMathPublisher\TextExpression
 *
 * @author Pascal Brachet <pbrachet@xm1math.net>
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license GPLv2
 */
class TextExpression extends Expression
{
    /**
     * Constructor
     *
     * @param string $exp
     * @param Helper $helper
     */
    public function __construct($exp, Helper $helper)
    {
        $this->helper = $helper;
        $this->text = $exp;
    }

    /**
     * @param $size
     */
    public function draw($size)
    {
        $this->image = $this->helper->displayMath($this->text, $size);
        $this->verticalBased = imagesy($this->image) / 2;
    }
}
