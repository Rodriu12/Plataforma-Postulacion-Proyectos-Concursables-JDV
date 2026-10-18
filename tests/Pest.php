<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\E2E\PantherTestCase;
use Tests\TestCase;

uses(TestCase::class)->in('Feature');
uses(RefreshDatabase::class)->in('Feature');
uses(PantherTestCase::class)->in('E2E');
