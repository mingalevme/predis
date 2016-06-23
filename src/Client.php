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
     * @param string $value2 [Optional]
     * @param string $valueN [Optional]
     * @return int The length of the list after the push operations.
     */
    //public function zrpush($key, $value /*, $value2 = null, $value3 = null */)
    /*{
        return $this->zpush($key, +1, array_slice(func_get_args(), 1));
    }*/
    
    /**
     * Insert all the specified values at the head of the list stored at key.
     * If key does not exist, it is created as empty list before performing the push operations.
     * 
     * @param string $key
     * @param string $value
     * @param string $value2 [Optional]
     * @param string $valueN [Optional]
     * @return int The length of the list after the push operations.
     */
    //public function zlpush($key, $value /*, $value2 = null, $value3 = null */)
    /*{
        return $this->zpush($key, -1, array_slice(func_get_args(), 1));
    }*/
    
    /**
     * Insert all the specified values at the head or tail of the stored list at key.
     * 
     * @param type $key
     * @param type $multiplier
     * @param type $value
     * @param string $value2 [Optional]
     * @param string $valueN [Optional]
     * @return int The length of the list after the push operations.
     */
    //protected function zpush($key, $multiplier, $value /*, $value2 = null, $value3 = null */)
    /*{
        $args = [$key, 'NX'];
        
        foreach (range(2, func_num_args() - 1) as $i) {
            $args[] = $multiplier * microtime(true);
            $args[] = func_get_arg($i);
        }
        
        return call_user_func_array([$this, 'zadd'], $args);
    }*/

    /**
     * Removes and returns the first element of the stored list at key.
     * 
     * @param string $key
     * @return string|null
     */
    public function zlpop($key)
    {
        return $this->zpop($key, 0, 0);
    }
    
    /**
     * Removes and returns the last element of the stored list at key.
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
    
    /**
     * {@inheritdoc}
     */
    public function createCommand($commandID, $arguments = array())
    {
        if (in_array(strtolower($commandID), ['zrpush', 'zlpush'])) {
            if (count($arguments) < 2) {
                throw new \InvalidArgumentException('Not enough arguments');
            }
            
            $args = [$arguments[0], 'NX'];
            
            foreach (array_slice($arguments, 1) as $arg) {
                $args[] = strtolower($commandID) === 'zrpush' ? microtime(true) : -microtime(true);
                $args[] = $arg;
            }
            
            return $this->getProfile()->createCommand('zadd', $args);
        } else {
            return $this->getProfile()->createCommand($commandID, $arguments);  
        }
    }
}

