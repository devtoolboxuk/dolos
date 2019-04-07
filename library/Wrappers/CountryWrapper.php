<?php

namespace devtoolboxuk\dolos\Wrappers;

class CountryWrapper extends Wrapper
{

    private $detected = 0;
    private $score = 0;

    public function process()
    {
        $this->initWrapper($this->setLocalName());

        $this->detect();

        if ($this->detected > 0) {
            $this->setScore($this->score);
            $this->setResult();
        }
    }

    private function setLocalName()
    {
        $name = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
        return str_replace('Wrapper', '', $name);
    }

    private function detect()
    {
        $params = $this->getParams();
        if (is_array($params)) {
            foreach ($params as $param) {
                if ($param != '') {
                    $zip = explode(":", $param);
                    if (strpos(strtolower($this->sanitizeReference()), strtolower($zip[0])) !== false) {
                        $this->score = isset($zip[1]) ? (int)filter_var($zip[1], FILTER_SANITIZE_NUMBER_INT) : $this->getRealScore();
                        if ($this->score > 0) {
                            $this->detected++;
                        }
                    }
                }
            }
        }
    }

}