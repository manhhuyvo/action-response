<?php

declare(strict_types=1);

namespace Tests\Components;

require_once "vendor/autoload.php";

use ManhHuyVo\ActionResponse\Components\ErrorsBag;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\DataProvider\ErrorsBag\FlexibleErrorsProvider;

class ErrorsBagTest extends TestCase
{
    public function testCreateFlexibleEmptyErrorsBag(): void
    {
        $errorsBag = ErrorsBag::flexible();

        $errors = $errorsBag->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertFalse($errorsBag->isStrict());
        $this->assertEmpty($errors->toArray());
        $this->assertSame(0, $errors->count());
    }

    public function testCreateStrictEmptyErrorsBag(): void
    {
        $errorsBag = ErrorsBag::strict();

        $errors = $errorsBag->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertEmpty($errors->toArray());
        $this->assertSame(0, $errors->count());
        $this->assertTrue($errorsBag->isStrict());
    }

    public function testCreateErrorsBagFromEmptyArray(): void
    {
        $errorsBag = ErrorsBag::fromErrors();

        $errors = $errorsBag->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertFalse($errorsBag->isStrict());
        $this->assertEmpty($errors->toArray());
        $this->assertSame(0, $errors->count());
    }

    #[DataProviderExternal(FlexibleErrorsProvider::class, 'singleErrorsBag')]
    public function testCreateFlexibleErrorsBagWithSingleArray(array $data, array $expected): void
    {
        $errorsBag = ErrorsBag::flexible($data);

        $errors = $errorsBag->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertFalse($errorsBag->isStrict());
        $this->assertSame($expected, $errors->toArray());
        $this->assertSame(count($expected), $errors->count());

        $errorsBag = ErrorsBag::fromErrors($data);

        $errors = $errorsBag->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertFalse($errorsBag->isStrict());
        $this->assertSame($expected, $errors->toArray());
        $this->assertSame(count($expected), $errors->count());
    }

    #[DataProviderExternal(FlexibleErrorsProvider::class, 'assocErrorsBag')]
    public function testCreateFlexibleErrorsBagWithAssociativeArrays(array $data, array $expected): void
    {
        $errorsBag = ErrorsBag::flexible($data);

        $errors = $errorsBag->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertFalse($errorsBag->isStrict());
        $this->assertSame($expected, $errors->toArray());
        $this->assertSame(count($expected), $errors->count());

        
        $errorsBag = ErrorsBag::fromErrors($data);

        $errors = $errorsBag->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertFalse($errorsBag->isStrict());
        $this->assertSame($expected, $errors->toArray());
        $this->assertSame(count($expected), $errors->count());
    }
}