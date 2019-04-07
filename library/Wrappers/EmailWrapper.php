<?php

namespace aegis\dolos\Wrappers;

class EmailWrapper extends Wrapper
{

    public function process()
    {
        $this->initWrapper($this->setLocalName());

        if (!filter_var($this->getReference(), FILTER_VALIDATE_EMAIL)) {
            $this->setScore($this->getRealScore());
            $this->setResult();
        }
    }

    private function setLocalName()
    {
        $name = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
        return str_replace('Wrapper', '', $name);
    }

}