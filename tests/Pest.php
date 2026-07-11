<?php

use Tests\TestCase;

pest()->extend(TestCase::class)
    ->in('Feature', 'Unit');

pest()->printer()->compact();
