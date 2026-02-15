<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;

$scssFile = __DIR__ . '/../scss/style.scss';
$cssFile = __DIR__ . '/../public/css/style.css';

$compiler = new Compiler();
try {
    $result = $compiler->compileString(file_get_contents($scssFile))->getCss();
} catch (SassException $e) {

}

$cssDir = dirname($cssFile);
if (!is_dir($cssDir)) {
    mkdir($cssDir, 0755, true);
}
file_put_contents($cssFile, $result);

echo "SCSS compiled successfully: {$cssFile}\n";
