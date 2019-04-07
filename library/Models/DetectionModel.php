<?php

namespace aegis\dolos\Models;

class DetectionModel
{
    /**
     * @var array
     */
    private $references = [];

    /**
     * @var int
     */
    private $score = 0;

    /**
     * @var array
     */
    private $result = [];

    /**
     * DetectModel constructor.
     * @param array $references
     * @param int $score
     * @param array $result
     */
    function __construct($references = [], $score = 0, $result = [])
    {
        $this->references = $references;
        $this->score = $score;
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'results' => [
                "array" => $this->decodedResult(),
                'string' => $this->getResult(),
            ],
            'references' => $this->getReferences(),
            'score' => $this->getScore()
        ];
    }

    /**
     * @return bool
     */
    public function hasScore()
    {
        return ($this->getScore() > 0) ? true : false;
    }


    /**
     * @return array
     */
    private function decodedResult()
    {
        return $this->result;
    }

    /**
     * @return false|string
     */
    public function getResult()
    {
        return json_encode($this->result);
    }

    /**
     * @return array
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }
}