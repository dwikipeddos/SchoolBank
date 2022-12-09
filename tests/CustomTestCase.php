<?php

namespace Tests;

use App\Traits\Testing\ActingAs;

abstract class CustomTestCase extends TestCase
{
    use CreatesApplication, ActingAs;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reinsertRolesPermission();
    }
}
