<?php

namespace Mingalevme\Predis;

class Client extends \Predis\Client
{
    /**
     * Insert all the specified values at the tail of the list stored at key.
     * If key does not exist, it is created as empty list before performing the push operation. 
     * 
     * @param string $key
     * @param string $value
     * @return int The length of the list after the push operations.
     */
    public function zrpush($key, $value)
    {
        return $this->zadd($key, 'NX', microtime(true), $value);
    }
    
    /**
     * Insert all the specified values at the head of the list stored at key.
     * If key does not exist, it is created as empty list before performing the push operations.
     * 
     * @param string $key
     * @param string $value
     * @return int The length of the list after the push operations.
     */
    public function zlpush($key, $value)
    {
        return $this->zadd($key, 'NX', -microtime(true), $value);
    }
    
    /**
     * Removes and returns the first element of the list stored at key.
     * 
     * @param string $key
     * @return string|null
     */
    public function zlpop($key)
    {
        return $this->zpop($key, 0, 0);
    }
    
    /**
     * Removes and returns the last element of the list stored at key.
     * 
     * @param string $key
     * @return string|null
     */
    public function zrpop($key)
    {
        return $this->zpop($key, -1, -1);
    }
    
    /**
     * Removes and returns the range of elements between $start and $end.
     * 
     * @param type $key
     * @param int $start
     * @param int $end
     * @return string|null
     */
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

