<?php

namespace Mingalevme\Predis\Command;

class ZSetPushHead extends ZSetPush
{
    /**
     * {@inheritdoc}
     */
    protected $placement = 'head';
    
    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return parent::getArguments();
    }
}
