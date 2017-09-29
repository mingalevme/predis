<?php

namespace Mingalevme\Predis\Command;

class ZSetPushTail extends ZSetPush
{
    /**
     * {@inheritdoc}
     */
    protected $placement = 'tail';
    
    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return parent::getArguments();
    }
}
