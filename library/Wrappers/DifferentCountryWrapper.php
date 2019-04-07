<?php

namespace aegis\dolos\Wrappers;

class DifferentCountryWrapper extends Wrapper
{

    public function process()
    {
        $this->initWrapper($this->setLocalName());
        list($chosenCountry, $detectedCountry) = explode('|', $this->getReference());
        if ($chosenCountry != $detectedCountry) {
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