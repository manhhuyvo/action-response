<?php

declare(strict_types=1);

namespace Tests\DataProvider\ErrorsBag;

require_once "vendor/autoload.php";

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class FlexibleErrorsProvider
{
    public static function singleErrorsBag(): array
    {
        return array_merge(
            self::singleErrorsBagValid(),
            self::singleErrorsBagInvalid(),
        );

        return array_merge($validCases, $invalidCases);
    }

    public static function assocErrorsBag(): array
    {
        return array_merge(
            self::assocErrorsBagValid(),
            self::assocErrorsBagInvalid(),
        );
    }

    private static function assocErrorsBagValid(): array
    {
        return array_merge(
            self::assocErrorsBagValidTwoLevelAssocData(),
            self::assocErrorsBagValidMultiLevelsAssocData(),
        );
    }

    private static function assocErrorsBagValidTwoLevelAssocData(): array
    {
        $data = [
            'field_one' => [
                Str::random(),
                Str::random(),
                Str::random(),
            ],
            'field_two' => [
                Str::random(),
                Str::random(),
                Str::random(),
            ],
        ];

        $expected = [
            'field_one' => $data['field_one'],
            'field_two' => $data['field_two'],
        ];

        return [
            'valid list contains two-level associative array' => [$data, $expected],
        ];
    }

    private static function assocErrorsBagValidMultiLevelsAssocData(): array
    {
        $data = [
            'field_one' => [
                'sub_field_one' => ['one', 'two', 'three'],
                'sub_field_two' => 'four',
            ],
            'field_two' => [
                'sub_field_one' => [
                    'another' => ['one', 'two', 'three']
                ],
                'sub_field_two' => 'four',
            ],
        ];

        $expected = [
            'field_one' => ['one', 'two', 'three', 'four'],
            'field_two' => ['one', 'two', 'three', 'four'],
        ];

        return [
            'valid list contains multi-levels associative arrays' => [$data, $expected],
        ];
    }

    private static function assocErrorsBagInvalid(): array
    {   
        return array_merge(
            self::assocErrorsBagInvalidEmptyMessage(),
            self::assocErrorsBagInvalidMultiLevelsAssocData(),
        );
    }

    private static function assocErrorsBagInvalidEmptyMessage(): array
    {
        $data = [
            'field_one' => null,
            'field_two' => '',
            'field_three' => Str::random(),
            'field_four' => [
                Str::random(),
                Str::random(),
            ],
        ];

        $expected = [
            'field_three' => Arr::wrap($data['field_three']),
            'field_four' => Arr::wrap($data['field_four']),
        ];

        return [
            'invalid list contains empty message' => [$data, $expected],
        ];
    }

    private static function assocErrorsBagInvalidMultiLevelsAssocData(): array
    {
        $data = [
            'field_one' => [
                'sub_field_one' => ['one', 'two', 'three'],
                'sub_field_two' => [],
                'sub_field_three' => 'four',
            ],
            'field_two' => [
                'sub_field_one' => [
                    'another' => ['one', 'two', 'three']
                ],
                'sub_field_two' => [
                    [],
                    [],
                ],
                'sub_field_three' => 'four',
            ],
        ];

        $expected = [
            'field_one' => ['one', 'two', 'three', 'four'],
            'field_two' => ['one', 'two', 'three', 'four'],
        ];

        return [
            'invalid list contains multi-levels associative arrays' => [$data, $expected],
        ];
    }

    private static function singleErrorsBagValid(): array
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

    private static function singleErrorsBagInvalid(): array
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