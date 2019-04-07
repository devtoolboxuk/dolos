<?php

namespace devtoolboxuk\dolos\Wrappers;

class UrlWrapper extends Wrapper
{

    private $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

    public function process()
    {
        $this->initWrapper($this->setLocalName());

        if (filter_var($this->getReference(), FILTER_VALIDATE_URL) || preg_match($this->reg_exUrl, $this->getReference())) {
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