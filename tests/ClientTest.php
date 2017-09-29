<?php

namespace Mingalevme\Tests\Predis;

use Mingalevme\Predis\Client;

class ClientTest extends TestCase
{
    /**
     * 
     * @return Client
     */
    protected function getClient()
    {
        return new Client();
    }
    
    protected function getKey()
    {
        return __CLASS__;
    }

    public function setUp()
    {
        $this->getClient()->del($this->getKey());
    }

    public function testZlpush()
    {
        $clinet = $this->getClient();
        
        $clinet->zadd($this->getKey(), -microtime(true), 'one');
        $clinet->zlpush($this->getKey(), 'two', 'three');
        $clinet->zlpush($this->getKey(), 'four');
        
        $elements = $clinet->zrange($this->getKey(), 0, -1);
        
        $this->assertSame(['four', 'three', 'two', 'one'], $elements);
    }

    public function testZrpush()
    {
        $clinet = $this->getClient();
        
        $clinet->zadd($this->getKey(), microtime(true), 'one');
        $clinet->zrpush($this->getKey(), 'two', 'three');
        $clinet->zrpush($this->getKey(), 'four');
        
        $elements = $clinet->zrange($this->getKey(), 0, -1);
        
        $this->assertSame(array_reverse(['four', 'three', 'two', 'one']), $elements);
    }

    public function testZlpop()
    {
        $clinet = $this->getClient();
        
        $clinet->zadd($this->getKey(), 1, 'one', 2, 'two', 3, 'three');
        
        $this->assertSame('one', $clinet->zlpop($this->getKey()));
        $this->assertSame('two', $clinet->zlpop($this->getKey()));
        $this->assertSame('three', $clinet->zlpop($this->getKey()));
        $this->assertSame(null, $clinet->zlpop($this->getKey()));
    }

    public function testZrpop()
    {
        $clinet = $this->getClient();
        
        $clinet->zadd($this->getKey(), 1, 'one', 2, 'two', 3, 'three');
        
        $this->assertSame('three', $clinet->zrpop($this->getKey()));
        $this->assertSame('two', $clinet->zrpop($this->getKey()));
        $this->assertSame('one', $clinet->zrpop($this->getKey()));
        $this->assertSame(null, $clinet->zrpop($this->getKey()));
    }
    
    public function testTransaction()
    {
        $client = $this->getClient();
        
        $responses = $client->transaction(function ($tx) {
            $tx->zlpush($this->getKey(), 'one');
            $tx->zrpush($this->getKey(), 'two', 'three');
            $tx->zlpop($this->getKey());
            $tx->zlpop($this->getKey());
            $tx->zrpop($this->getKey());
            $tx->zrpop($this->getKey());
        });
        
        $this->assertSame($responses[0], 1);
        $this->assertSame($responses[1], 2);
        $this->assertSame($responses[2], 'one');
        $this->assertSame($responses[3], 'two');
        $this->assertSame($responses[4], 'three');
        $this->assertSame($responses[5], null);
    }
}
