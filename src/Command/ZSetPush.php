<?php

namespace Mingalevme\Predis\Command;

abstract class ZSetPush extends Command
{
    /**
     * @var string
     */
    protected $placement;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'ZADD';
    }
    
    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        if (count(parent::getArguments()) < 2) {
            throw new \InvalidArgumentException('Not enough arguments');
        }
            
        $arguments = [$this->getArgument(0), 'NX'];

        list($_, $sec) = explode(" ", microtime());
        $usec = intval(substr($_, 2, -2));

        foreach (array_slice(parent::getArguments(), 1) as $index => $arg) {
            if ($this->placement === 'tail') {
                $score = sprintf('%d.%d', $sec, $usec + $index);
            } elseif ($this->placement === 'head') {
                $score = sprintf('-%d.%d', $sec, $usec + $index);
            } else {
                throw new \InvalidArgumentException("Invalid placement");
            }
            $arguments[] = $score;
            $arguments[] = $arg;
        }

        return $arguments;
    }
}
