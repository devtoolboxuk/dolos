<?php

namespace aegis\dolos\Wrappers;

abstract class Wrapper
{

    private $options = [];
    private $results = [];
    private $score = 0;
    private $realScore = 0;
    private $params = [];
    private $name;
    private $active;
    private $reference;

    function pushResult($result)
    {
        array_unshift($this->results, $result);
    }

    function getResult()
    {
        return $this->results;
    }

    public function getRealScore()
    {
        if (!$this->hasRealScore()) {
            return 0;
        }

        return $this->realScore;
    }

    private function hasRealScore()
    {
        return isset($this->realScore);
    }

    function setOptions($reference, $options = [])
    {
        $this->reference = $reference;
        $this->options = $options;
        return $this;
    }

    function sanitizeReference()
    {
        return str_replace(" ", "", strip_tags(trim($this->getReference())));
    }


    function getReference()
    {
        return $this->reference;
    }

    protected function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    public function inParams($name)
    {
        if (in_array($name, $this->getParams())) {
            return true;
        }
        return false;
    }

    protected function getParams()
    {
        return $this->params;
    }

    protected function setParams($params)
    {
        $this->params = explode("|", $params);
        return $this;
    }

    public function getRuleOption($name, $score)
    {
        if (!$this->hasRuleOption($name)) {
            return $score;
        }

        return $this->options[$this->name][$name];
    }

    public function hasRuleOption($name)
    {
        return isset($this->options[$this->name][$name]);
    }

    protected function initWrapper($name)
    {
        $this->setName($name);
        $this->setRules();
    }

    function setRules()
    {
        $this->options = $this->getOption($this->getName());

        $this->setRealScore($this->getOption('score'));
        $this->setActive($this->getOption('active'));
        $this->setParams($this->getOption('params'));
    }

    public function getOption($name)
    {
        if (!$this->hasOption($name)) {
            return null;
        }

        return $this->options[$name];
    }

    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }

    function getName()
    {
        return $this->name;
    }

    protected function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    private function setRealScore($score)
    {
        $this->realScore = $score;
    }

    private function setActive($active)
    {
        $this->active = $active;
    }

    protected function setResult()
    {
        if ($this->getActive()) {
            $this->results[$this->getName()] = $this->getScore();
            $this->score = $this->getScore();
        } else {
            $this->score = 0;
        }
        return $this;
    }

    private function getActive()
    {
        return $this->active;
    }

    public function getScore()
    {
        if (!$this->hasScore()) {
            return 0;
        }

        return $this->score;
    }

    protected function setScore($score)
    {
        $this->score = $score;
    }

    private function hasScore()
    {
        return isset($this->score);
    }

}