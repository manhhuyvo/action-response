<?php

declare(strict_types=1);

namespace Tests\DataProvider\ErrorsList;

require_once "vendor/autoload.php";

use Illuminate\Support\Str;

final class FlexibleErrorsProvider
{
    public static function singleErrorsList(): array
    {
        $validCases = self::singleErrorsListValid();
        $invalidCases = self::singleErrorsListInvalid();

        return array_merge($validCases, $invalidCases);
    }

    private static function singleErrorsListValid(): array
    {
        $data = [
            Str::random(),
            Str::random(),
            Str::random(),
        ];

        $expected = $data;

        return [
            'valid list with string item' => [$data, $expected],
        ];
    }

    private static function singleErrorsListInvalid(): array
    {
        // Data contains empty item
        $dataEmptyItem = [
            Str::random(),
            null,
            Str::random(),
        ];

        $expectedFromDataEmptyItem = [
            $dataEmptyItem[0],
            $dataEmptyItem[2],
        ];
        
        // Data contains non-string item
        $dataNonStringItem = [
            Str::random(),
            123,
            [
                Str::random(),
            ],
        ];

        $expectedFromDataNonStringItem = [
            $dataNonStringItem[0],
        ];

        return [
            'invalid list contains empty item' => [$dataEmptyItem, $expectedFromDataEmptyItem],
            'invalid list contains non-string items' => [$dataNonStringItem, $expectedFromDataNonStringItem],
        ];
    }
}