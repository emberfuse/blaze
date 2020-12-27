<?php

namespace Tests\Concerns;

use PHPUnit\Framework\Assert;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;

trait HasMacros
{
    /**
     * Register macro methods to base test case.
     *
     * @return void
     */
    protected function registerMacros(): void
    {
        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
        });

        Collection::macro('assertContains', function ($value) {
            return Assert::assertTrue($this->contains($value));
        });

        Collection::macro('assertDoesNotContain', function ($value) {
            return Assert::assertFalse($this->contains($value));
        });
    }
}
