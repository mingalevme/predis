<?php

namespace Mingalevme\Predis\Command;

abstract class Command extends \Predis\Command\Command
{
    /**
     * {@inheritdoc}
     */
    public function setArguments(array $arguments)
    {
        $this->setRawArguments($this->filterArguments($arguments));
    }
}
