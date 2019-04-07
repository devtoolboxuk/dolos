<?php

namespace aegis\dolos\Wrappers;

use EmailChecker\EmailChecker;

class DisposableEmailWrapper extends Wrapper
{

    public function process()
    {
        $this->initWrapper($this->setLocalName());

        $checker = new EmailChecker();
        if (!$checker->isValid($this->getReference())) {
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