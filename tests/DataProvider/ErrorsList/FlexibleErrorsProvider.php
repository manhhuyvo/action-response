<?php

declare(strict_types=1);

namespace Tests\DataProvider\ErrorsList;

require_once "vendor/autoload.php";

use Illuminate\Support\Str;

final class FlexibleErrorsProvider
{
    public static function singleErrorsListValid(): array
    {
        $data = [
            Str::random(),
            Str::random(),
            Str::random(),
        ];

        $expected = $data;

        return [
            $data,
            $expected,
        ];
    }

    public static function singleErrorsListInvalid(): array
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
            //[$dataEmptyItem, $expectedFromDataEmptyItem],
            [$dataNonStringItem, $expectedFromDataNonStringItem],
        ];
    }
}