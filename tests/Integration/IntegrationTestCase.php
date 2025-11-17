<?php

declare(strict_types = 1);

namespace Integration;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    public static bool $migrated = false;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        if (!self::$migrated) {
            Artisan::call('migrate');
            self::$migrated = true;
        }
    }
}
