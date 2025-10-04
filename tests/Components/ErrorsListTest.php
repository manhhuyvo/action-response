<?php

declare(strict_types=1);

namespace Tests\Components;

require_once "vendor/autoload.php";

use ManhHuyVo\ActionResponse\Components\ErrorsList;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;
use Symfony\Component\VarDumper\VarDumper;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\DataProvider\ErrorsList\FlexibleErrorsProvider;

class ErrorsListTest extends TestCase
{
    public function testCreateFlexibleEmptyErrorsList(): void
    {
        $errorsList = ErrorsList::flexible();

        $errors = $errorsList->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertFalse($errorsList->isStrict());
        $this->assertEmpty($errors->toArray());
        $this->assertSame(0, $errors->count());
    }

    public function testCreateStrictEmptyErrorsList(): void
    {
        $errorsList = ErrorsList::strict();

        $errors = $errorsList->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertEmpty($errors->toArray());
        $this->assertSame(0, $errors->count());
        $this->assertTrue($errorsList->isStrict());
    }

    public function testCreateErrorsListFromEmptyArray(): void
    {
        $errorsList = ErrorsList::fromErrors();

        $errors = $errorsList->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertFalse($errorsList->isStrict());
        $this->assertEmpty($errors->toArray());
        $this->assertSame(0, $errors->count());
    }

    #[DataProviderExternal(FlexibleErrorsProvider::class, 'singleErrorsList')]
    public function testCreateFlexibleErrorsListWithSingleArray(array $data, array $expected): void
    {
        $errorsList = ErrorsList::flexible($data);

        $errors = $errorsList->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertFalse($errorsList->isStrict());
        $this->assertSame($expected, $errors->toArray());
        $this->assertSame(count($expected), $errors->count());

        $errorsList = ErrorsList::fromErrors($data);

        $errors = $errorsList->getErrors();

        $this->assertInstanceOf(Collection::class, $errors);
        $this->assertIsArray($errors->toArray());
        $this->assertFalse($errorsList->isStrict());
        $this->assertSame($expected, $errors->toArray());
        $this->assertSame(count($expected), $errors->count());
    }
}