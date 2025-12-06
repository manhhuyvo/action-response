<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use ManhHuyVo\ActionResponse\Response;
use ManhHuyVo\ActionResponse\Enums\ResponseStatus;

class ResponseTest extends TestCase
{
    public function testResponseCanBeInstantiatedWithDefaultValues()
    {
        $response = new Response();

        $this->assertSame('', $response->getStatus());
        $this->assertSame('', $response->getMessage());
        $this->assertSame([], $response->getErrors());
        $this->assertSame([], $response->getData());
    }

    public function testSuccessResponseIsBuiltCorrectly()
    {
        $response = Response::success();

        $this->assertSame(ResponseStatus::Success->value, $response->getStatus());
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isError());
        $this->assertFalse($response->isSnooze());
    }

    public function testErrorResponseIsBuiltCorrectly()
    {
        $response = Response::error();

        $this->assertSame(ResponseStatus::Error->value, $response->getStatus());
        $this->assertTrue($response->isError());
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isSnooze());
    }

    public function testSnoozeResponseIsBuiltCorrectly()
    {
        $response = Response::snooze();

        $this->assertSame(ResponseStatus::Snooze->value, $response->getStatus());
        $this->assertTrue($response->isSnooze());
        $this->assertFalse($response->isError());
        $this->assertFalse($response->isSuccessful());
    }

    public function testStatusCanBeSet()
    {
        $response = new Response();
        $response->status('custom');

        $this->assertSame('custom', $response->getStatus());
    }

    public function testMessageCanBeSet()
    {
        $response = new Response();
        $response->message('Hello world');

        $this->assertSame('Hello world', $response->getMessage());
    }

    public function testErrorsAreFilteredDedupedAndCleaned()
    {
        $response = new Response();

        $response->errors(['err1', '', 'err1', 'err2', null]);

        $this->assertSame(['err1', 'err2'], $response->getErrors());
    }

    public function testDataCanBeSet()
    {
        $data = ['a' => 1, 'b' => 2];

        $response = new Response();
        $response->data($data);

        $this->assertSame($data, $response->getData());
    }

    public function testNestedDataCanBeAccessedUsingDotNotation()
    {
        $response = new Response();

        $response->data([
            'user' => [
                'profile' => [
                    'name' => 'Eric'
                ]
            ]
        ]);

        $this->assertSame('Eric', $response->getData('user.profile.name'));
    }

    public function testDotNotationReturnsNullWhenKeyDoesNotExist()
    {
        $response = new Response();
        $response->data(['foo' => ['bar' => 123]]);

        $this->assertNull($response->getData('foo.missing'));
    }

    public function testGetDataReturnsEntireArrayWhenKeyIsEmpty()
    {
        $data = ['x' => 1, 'y' => 2];

        $response = new Response();
        $response->data($data);

        $this->assertSame($data, $response->getData(''));
    }

    public function testStaticConstructorsOnlySetStatus()
    {
        $response = Response::success();

        $this->assertSame(ResponseStatus::Success->value, $response->getStatus());
        $this->assertSame('', $response->getMessage());
        $this->assertSame([], $response->getErrors());
        $this->assertSame([], $response->getData());
    }

    public function testEmptyErrorsArrayProducesEmptyErrors()
    {
        $response = new Response();
        $response->errors([]);

        $this->assertSame([], $response->getErrors());
    }

    public function testErrorsAllowNonStringValues()
    {
        $response = new Response();

        $object = (object)['x' => 1];

        $response->errors([
            123,
            ['nested'],
            $object,
        ]);

        $this->assertEquals([123, ['nested'], $object], $response->getErrors());
    }

    public function testDataStoresNestedArraysAsIs()
    {
        $structure = [
            'a' => [
                'b' => [
                    'c' => 10
                ]
            ]
        ];

        $response = new Response();
        $response->data($structure);

        $this->assertSame($structure, $response->getData());
    }

    public function testGetDataSupportsNumericIndexes()
    {
        $response = new Response();
        $response->data(['items' => ['a', 'b', 'c']]);

        $this->assertSame('b', $response->getData('items.1'));
    }

    public function testAllSettersAreChainable()
    {
        $response = (new Response())
            ->status('ok')
            ->message('done')
            ->errors(['x'])
            ->data(['y' => 1]);

        $this->assertSame('ok', $response->getStatus());
        $this->assertSame('done', $response->getMessage());
        $this->assertSame(['x'], $response->getErrors());
        $this->assertSame(['y' => 1], $response->getData());
    }

    public function testStaticConstructorsProduceIndependentInstances()
    {
        $r1 = Response::success()->message('first');
        $r2 = Response::success()->message('second');

        $this->assertNotSame($r1, $r2);
        $this->assertSame('first', $r1->getMessage());
        $this->assertSame('second', $r2->getMessage());
    }

    public function testToArrayReturnsCorrectStructureWithDefaultValues(): void
    {
        $response = new Response();

        $expected = [
            'status' => '',
            'message' => '',
            'errors' => [],
            'data' => [],
        ];

        $this->assertSame($expected, $response->toArray());
    }

    public function testToArrayReturnsCorrectValuesWhenSet(): void
    {
        $response = (new Response())
            ->status('success')
            ->message('Operation completed')
            ->errors(['error1', 'error2'])
            ->data(['key' => 'value']);

        $expected = [
            'status' => 'success',
            'message' => 'Operation completed',
            'errors' => ['error1', 'error2'],
            'data' => ['key' => 'value'],
        ];

        $this->assertSame($expected, $response->toArray());
    }

    public function testToJsonReturnsValidJsonWithDefaultValues(): void
    {
        $response = new Response();

        $expectedJson = json_encode([
            'status' => '',
            'message' => '',
            'errors' => [],
            'data' => [],
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->toJson());
    }

    public function testToJsonReturnsValidJsonWithCustomValues(): void
    {
        $response = (new Response())
            ->status('error')
            ->message('Something went wrong')
            ->errors(['errorA'])
            ->data(['user' => ['name' => 'John']]);

        $expectedJson = json_encode([
            'status' => 'error',
            'message' => 'Something went wrong',
            'errors' => ['errorA'],
            'data' => ['user' => ['name' => 'John']],
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->toJson());
    }

}
