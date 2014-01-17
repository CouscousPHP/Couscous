#!/usr/bin/env php
<?php

use Couscous\CLI\GenerateCommand;
use Couscous\CLI\PreviewCommand;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$application = new Application();
$application->add(new GenerateCommand);
$application->add(new PreviewCommand());
$application->run();
