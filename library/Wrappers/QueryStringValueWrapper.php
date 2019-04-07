<?php

namespace aegis\dolos\Wrappers;

class QueryStringValueWrapper extends Wrapper
{

    private $queryArray = [];

    public function process()
    {
        $this->initWrapper($this->setLocalName());

        $this->getQueryString();

        list($key, $value) = array_pad(explode('|', $this->getReference()), 2, null);

        if (isset($this->queryArray[$key])) {
            if ($this->queryArray[$key] == $value) {
                $this->setScore($this->getRealScore());
                $this->setResult();
            }
        }
    }

    private function setLocalName()
    {
        $name = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
        return str_replace('Wrapper', '', $name);
    }


    private function getQueryString()
    {
        if (isset($_SERVER["QUERY_STRING"])) {
            parse_str($_SERVER["QUERY_STRING"], $this->queryArray);
        }
    }

}