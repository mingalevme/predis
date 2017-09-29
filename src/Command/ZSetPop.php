<?php

namespace Mingalevme\Predis\Command;

abstract class ZSetPop extends Command
{
    /**
     * {@inheritdoc}
     */
    public function setRawArguments(array $arguments)
    {
        if (count($arguments) !== 1) {
            throw new \InvalidArgumentException("Invalid arguments count");
        } else {
            return parent::setRawArguments($arguments);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        $key = parent::getArgument(0);
        
        if (!$key) {
            throw new \InvalidArgumentException("Key is missed");
        } else {
            return [$key];
        }
    }
    
    /**
     * Get the Lua script for popping the next job off of the queue.
     *
     * KEYS[1] - The key to pop elements from
     * KEYS[2] - Index to start from
     * KEYS[3] - Index to end from
     *
     * @return string
     */
    protected function getZpopScript()
    {
        return <<<'LUA'
local range = redis.call('zrange', KEYS[1], KEYS[2], KEYS[3])

while #range > 0 do
    if (redis.call('zrem', KEYS[1], range[1]) > 0) then
        return range[1]
    end
    range = redis.call('zrange', KEYS[1], KEYS[2], KEYS[3])
end

return nil
LUA;
    }
}
