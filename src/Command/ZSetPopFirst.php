<?php

namespace Mingalevme\Predis\Command;

class ZSetPopFirst extends ZSetPop
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'EVAL';
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return [$this->getZpopScript(), 3, parent::getArguments()[0], 0, 0];
    }
}
