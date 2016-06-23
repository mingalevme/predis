<?php

namespace Mingalevme\Predis;

class Client extends \Predis\Client
{
    public function zrpush(string $key, string $value)
    {
        return $this->zadd($key, 'NX', microtime(true), $value);
    }
    
    public function zlpush(string $key, string $value)
    {
        return $this->zadd($key, 'NX', -microtime(true), $value);
    }
    
    public function zlpop($key)
    {
        return $this->zpop($key, 0, 0);
    }
    
    public function zrpop($key)
    {
        return $this->zpop($key, -1, -1);
    }
    
    protected function zpop($key, $start, $end)
    {
        while ($result = $this->zrange($key, $start, $end)) {
            $value = $result[0];
            
            if ($this->zrem($key, $value) === 0) {
                continue;
            } else {
                return $value;
            }
        }
        
        return null;
    }
}

