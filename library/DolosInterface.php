<?php

namespace devtoolboxuk\dolos;

interface DolosInterface
{
    public function pushHandler($handler);

    public function process();

    public function getScore();

    public function toArray();

    public function getResult();

}
