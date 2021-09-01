<?php

class ConfigFileNotFoundException extends \Exception 
{
    public function __construct(string $exchangeName)
    {
        $this->message = "The config file for " . $exchangeName . " was not found";
    }
}