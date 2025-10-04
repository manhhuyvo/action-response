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

    public static function success(): self
    {
        return new self(ResponseStatus::Success->value);
    }

    public static function snooze(): self
    {
        return new self(ResponseStatus::Snooze->value);
    }

    public static function error(): self
    {
        return new self(ResponseStatus::Error->value);
    }

    public function isSnooze(): bool
    {
        return $this->status == ResponseStatus::Snooze->value;
    }

    public function isSuccessful(): bool
    {
        return $this->status == ResponseStatus::Success->value;
    }

    public function status(?string $status = ''): self
    {
        $this->status = $status;

        return $this;
    }

    public function message(?string $message = ''): self
    {
        $this->message = $message;

        return $this;
    }

    public function errors(?array $errors = []): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function data(?array $data = []): self
    {
        $this->data = $data;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getData(?string $key = ''): mixed
    {
        if (empty($key)) {
            return $this->data;
        }

        $data = Arr::dot($this->data);

        return $data[$key] ?? null;
    }
}