<?php

namespace devtoolboxuk\dolos\Handlers;

use devtoolboxuk\dolos\Wrappers\HtmlWrapper;
use devtoolboxuk\dolos\Wrappers\UrlWrapper;

class TextHandler extends Handler
{
    public function __construct($value = '')
    {
        parent::__construct($value);
        $this->setName(str_replace(__NAMESPACE__ . '\\', '', __CLASS__));

        $this->pushWrapper(new HtmlWrapper());
        $this->pushWrapper(new UrlWrapper());
    }
}