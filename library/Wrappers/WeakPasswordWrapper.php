<?php

namespace aegis\dolos\Wrappers;

class WeakPasswordWrapper extends Wrapper
{

    public function process()
    {
        $this->initWrapper($this->setLocalName());

    }

    private function setLocalName()
    {
        $name = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
        return str_replace('Wrapper', '', $name);
    }

}