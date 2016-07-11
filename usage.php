<?php

require_once 'Cylesoft/TextGen1000/Generator.php';

// Define your sentence, with anything between double underscores considered a "node".
$sentence = 'The __thing___capitalize doesn\'t __feeling__ your __thing___uppercase in this __thing__scape, you __thing__-loving __thing__-__thing__';
// In that example, the "nodes" are __thing__ and __feeling__
// Notice that you can have modifiers on certain nodes by adding another underscore and a modifier name, i.e. _uppercase, making __thing___uppercase
// That modifier will be run when generating a new sentence, every time.

// Next, define your corpus of nodes to actual word possibilities.
// When a new sentence is generated, these words may be used.
$corpus = [
    'thing' => [
        'duck',
        'cat',
        'dog',
        'wolf',
    ],
    'feeling' => [
        'like',
        'care about',
        'bend',
        'hear',
    ],
];

// Finally, instantiate a new generator with your input sentence and your corpus of possibilities.
$generator = new \Cylesoft\TextGen1000\Generator($sentence, $corpus);

// Generate as many as you want, each will be different.
echo $generator->generate() . "\n";
echo $generator->generate() . "\n";
echo $generator->generate() . "\n";

// you can use this via commandline like
// $ php usage.php
