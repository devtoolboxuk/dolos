<?php

namespace aegis\dolos;

abstract class AbstractDetection
{

    protected $options = [];
    protected $results = [];
    protected $result = [];
    protected $score = 0;

    protected function processWrappers($handler)
    {
        $options = $this->getOption('Detection');

        foreach ($handler->getWrappers() as $wrapper) {

            $wrapper->setOptions($handler->getValue(), $options['Rules']);
            $wrapper->process();
            $this->addResult($wrapper->getScore(), $wrapper->getResult());
        }
    }

    protected function clearResults()
    {
        $this->results = [];
        $this->result = [];
        $this->score = 0;
    }

    private function getOption($name)
    {
        if (!$this->hasOption($name)) {
            return null;
        }

        return $this->options[$name];
    }

    private function hasOption($name)
    {
        return isset($this->options[$name]);
    }

    protected function addResult($score, $result)
    {
        if (is_array($result)) {
            $this->addScore($score);
            $this->result = array_merge($this->result, $result);
        }
        return $this;
    }

    /**
     * @param $score
     * @return $this
     */
    private function addScore($score)
    {
        $this->score += $score;
        return $this;
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @param string $file
     * @return $this
     */
    public function setOptions($options = [], $file = '')
    {

        $basic_options = yaml_parse_file(__DIR__ . '/Options.yml');
        $options = static::arrayMergeRecursiveDistinct($basic_options, $options);

        if ($file != '') {
            $other_options = yaml_parse_file($file);
            $options = static::arrayMergeRecursiveDistinct($other_options, $options);
        }

        $this->options = $options;
        return $this;
    }


    /**
     * @param array $merged
     * @param array $array2
     * @return array
     */
    private function arrayMergeRecursiveDistinct($merged = [], $array2 = [])
    {
        if (empty($array2)) {
            return $merged;
        }

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }

}