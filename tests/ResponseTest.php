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
}
