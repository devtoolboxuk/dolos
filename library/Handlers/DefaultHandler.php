<?php

namespace devtoolboxuk\dolos\Handlers;

class DefaultHandler extends Handler
{
    public function __construct($type, $value = '')
    {
        parent::__construct($value);
        $this->setName($type);
    }
}