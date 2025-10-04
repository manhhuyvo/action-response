<?php

declare(strict_types=1);

namespace Tests\DataProvider\ErrorsList;

require_once "vendor/autoload.php";

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class FlexibleErrorsProvider
{
    public static function singleErrorsList(): array
    {
        return array_merge(
            self::singleErrorsListValid(),
            self::singleErrorsListInvalid(),
        );

        return array_merge($validCases, $invalidCases);
    }

    public static function assocErrorsListInvalid(): array
    {   
        return array_merge(
            self::assocErrorsListInvalidEmptyMessage(),
            self::assocErrorsListInvalidMultiLevelsAssocData(),
        );
    }

    private static function assocErrorsListInvalidEmptyMessage(): array
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

    private static function assocErrorsListInvalidMultiLevelsAssocData(): array
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