<?php

namespace Mingalevme\Predis;

use Mingalevme\Predis\Command\ZSetPopLast;
use Mingalevme\Predis\Command\ZSetPopFirst;
use Mingalevme\Predis\Command\ZSetPushHead;
use Mingalevme\Predis\Command\ZSetPushTail;

class Client extends \Predis\Client
{
    /**
     * @param mixed $parameters Connection parameters for one or more servers.
     * @param mixed $options    Options to configure some behaviours of the client.
     */
    public function __construct($parameters = null, $options = null)
    {
        parent::__construct($parameters, $options);
        $this->getProfile()->defineCommand('zlpop', ZSetPopFirst::class);
        $this->getProfile()->defineCommand('zrpop', ZSetPopLast::class);
        $this->getProfile()->defineCommand('zlpush', ZSetPushHead::class);
        $this->getProfile()->defineCommand('zrpush', ZSetPushTail::class);
        
    }
}
