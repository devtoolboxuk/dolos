<?php

namespace aegis\dolos;

use aegis\dolos\Handlers\EmailHandler;
use aegis\dolos\Handlers\TextHandler;
use PHPUnit\Framework\TestCase;

class DetectionTest extends TestCase
{

    function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }


    function testUrlDetection()
    {
        $dolos = new Detect();
        $dolos->setOptions($this->getOptions());
        $data = '<span>http://dev-toolbox.co.uk';
        $detection = $dolos
            ->resetHandlers()
            ->pushHandler(new TextHandler($data));
        $this->assertEquals(2,$detection->getScore());

    }

    function getOptions()
    {
        echo "\n";
        $options = [
            'config' => [
                'threshold' => 100,
                'hashing' => [
                    'key' => 'test_key'
                ]
            ],
            'Detection' => [
                'Rules' => [
                    'html' => [
                        'active' => 1,
                        'score' => '46',
                        'params' => ''
                    ],
                    'DisposableEmail' => [
                        'active' => 1,
                        'score' => '46',
                        'params' => ''
                    ],
                    'bot' => [
                        'active' => 1,
                        'params' => 'sensu'
                    ]
                ]
            ]
        ];

        return $options;
    }

    function testEmailDetection()
    {

        $dolos = new Detect();
        $dolos->setOptions($this->getOptions());

        $email_data = 'rob@shotmail.ru';

        $detection = $dolos
            ->resetHandlers()
            ->pushHandler(new EmailHandler($email_data));
        $this->assertEquals(46,$detection->getScore());

    }
}
