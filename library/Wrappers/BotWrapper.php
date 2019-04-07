<?php

namespace devtoolboxuk\dolos\Wrappers;

class BotWrapper extends Wrapper
{

    private $userAgentFound = 0;

    public function process()
    {
        $this->initWrapper($this->setLocalName());

        $this->detectBot();

        if ($this->userAgentFound > 0) {
            $this->setScore($this->getRealScore());
            $this->setResult();
        }
    }

    private function setLocalName()
    {
        $name = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
        return str_replace('Wrapper', '', $name);
    }

    private function detectBot()
    {

        if ($this->getReference() == '') {
            $this->setReference($this->getUserAgent());
        }

        $params = $this->getParams();
        if ($params) {
            foreach ($params as $param) {
                if (strpos(strtolower($this->getReference()), $param) !== false) {
                    $this->userAgentFound++;
                }
            }
        }
    }

    private function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';
    }

}