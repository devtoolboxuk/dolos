<?php

namespace aegis\dolos\Handlers;

use aegis\dolos\Wrappers\SecurityWrapper;

class SecurityHandler extends Handler
{
    public function __construct($value = '')
    {
        parent::__construct($value);
        $this->setName(str_replace(__NAMESPACE__ . '\\', '', __CLASS__));
        $this->pushWrapper(new SecurityWrapper());
    }
}