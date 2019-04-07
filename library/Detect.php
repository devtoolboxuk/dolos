<?php

namespace aegis\dolos;

use aegis\dolos\handlers\Handler;
use ReflectionClass;

class Detect extends AbstractDetection implements DolosInterface
{
    private static $instance = null;
    protected $handlers = [];
    protected $references = [];

    public function resetHandlers()
    {
        $this->references = [];
        $this->handlers = [];
        self::$instance = null;
        return $this;
    }

    public function __call($method, $arguments = [])
    {
        $handlers = new Handler($arguments);
        $handler = $handlers->build($method, $arguments);
        $this->pushHandler($handler);
        return $this;
    }

    public function pushHandler($handler)
    {
        array_unshift($this->handlers, $handler);
        $this->clearResults();
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->process()->toArray();
    }

    public function process()
    {
        foreach ($this->handlers as $handler) {
            array_unshift($this->references, ['name' => $handler->getName(), 'value' => $handler->getValue()]);
            $this->processWrappers($handler);
        }

        if (self::$instance === null) {
            $reflection = new ReflectionClass('aegis\\dolos\\Models\\DetectionModel');
            self::$instance = $reflection->newInstance($this->references, $this->score, $this->result);
        }

        return self::$instance;
    }

    public function hasScore()
    {
        return $this->process()->hasScore();
    }

    public function getResult()
    {
        return $this->process()->getResult();
    }

    public function getScore()
    {
        return $this->process()->getScore();
    }

}