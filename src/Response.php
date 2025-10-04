<?php

declare(strict_types=1);

namespace ManhHuyVo\ActionResponse;

require_once 'vendor/autoload.php';

use ManhHuyVo\Enums\ResponseStatus;
use Illuminate\Support\Arr;

class Response
{
    public function __construct(
        protected ?string $status = '',
        protected ?string $message = '',
        protected ?array $errors = [],
        protected ?array $data = []
    ) {}

    /**
     * Make a new response instance with Success status
     * Should be used to indicate that an action is successful
     * 
     * @return Response
     */
    public static function success(): self
    {
        return new self(ResponseStatus::Success->value);
    }

    /**
     * Make a new response instance with Snooze status
     * Should be used to indicate that an action is not applicable and should be skip 
     * 
     * @return Response
     */
    public static function snooze(): self
    {
        return new self(ResponseStatus::Snooze->value);
    }

    /**
     * Make a new response instance with Error status
     * Should be used to indicate that an action has failed to be executed 
     * 
     * @return Response
     */
    public static function error(): self
    {
        return new self(ResponseStatus::Error->value);
    }

    /**
     * Check whether this response is snooze
     * 
     * @return bool
     */
    public function isSnooze(): bool
    {
        return $this->status === ResponseStatus::Snooze->value;
    }

    /**
     * Check whether this response is successful
     * 
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->status === ResponseStatus::Success->value;
    }

    /**
     * Check whether this response is error
     * 
     * @return bool
     */
    public function isError(): bool
    {
        return $this->status === ResponseStatus::Error->value;
    }

    /**
     * Set the status code of this response
     * 
     * @param string|null $status
     * @return Response
     */
    public function status(?string $status = ''): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set the message of this response
     * 
     * @param string|null $status
     * @return Response
     */
    public function message(?string $message = ''): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set the errors list of this response
     * 
     * @param array|null $errors
     * @return Response
     */
    public function errors(?array $errors = []): self
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Set the data attached with this response
     * 
     * @param array|null $data
     * @return Response
     */
    public function data(?array $data = []): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the status of this response
     * 
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Get the message of this response
     * 
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the errors list of this response
     * 
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get the data of this response. If a key is provided, its value will be returned using dot notation
     * 
     * @param string|null $key
     * @return mixed
     */
    public function getData(?string $key = ''): mixed
    {
        if (empty($key)) {
            return $this->data;
        }

        $data = Arr::dot($this->data);

        return $data[$key] ?? null;
    }
}