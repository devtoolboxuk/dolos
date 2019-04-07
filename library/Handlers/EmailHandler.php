<?php

namespace devtoolboxuk\dolos\Handlers;

use devtoolboxuk\dolos\Wrappers\DisposableEmailWrapper;
use devtoolboxuk\dolos\Wrappers\EmailWrapper;

class EmailHandler extends Handler
{
    public function __construct($value = '')
    {
        parent::__construct($value);
        $this->setName(str_replace(__NAMESPACE__ . '\\', '', __CLASS__));

        $this->pushWrapper(new DisposableEmailWrapper());
        $this->pushWrapper(new EmailWrapper());
    }
}