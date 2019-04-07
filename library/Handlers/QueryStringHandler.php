<?php

namespace aegis\dolos\Handlers;

use aegis\dolos\Wrappers\QueryStringKeyWrapper;
use aegis\dolos\Wrappers\QueryStringValueWrapper;

class QueryStringHandler extends Handler
{
    public function __construct($value = '')
    {
        parent::__construct($value);
        $this->setName(str_replace(__NAMESPACE__ . '\\', '', __CLASS__));
        $this->pushWrapper(new QueryStringKeyWrapper());
        $this->pushWrapper(new QueryStringValueWrapper());
    }
}