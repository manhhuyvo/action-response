<?php

namespace ManhHuyVo\ActionResponse\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ErrorsList
{
    private array $errors = [];

    private bool $strict = false;

    public function __construct(array|string|null $errors = [], bool $strict = false)
    {
        $this->setErrors($errors);
        $this->strict = $strict;
    }

    public static function fromErrors(array|string|null $errors = []): self
    {
        return self::flexible($errors);
    }

    public static function strict(array|string|null $errors = []): self
    {
        return new self(errors: $errors, strict: true);
    }

    public static function flexible(array|string|null $errors = []): self
    {
        return new self(errors: $errors, strict: false);
    }

    public function setErrors(array|string|null $errors = []): self
    {
        $this->errors = $this->sanitizeErrors($errors);

        return $this;
    }

    public function getErrors(): Collection
    {
        return collect($this->errors);
    }

    public function isEmpty(): bool
    {
        return empty($this->errors);
    }

    public function count(): int
    {
        return count($this->errors);
    }

    public function hasKey(string|int $key): bool
    {
        return ! is_null($this->findMessageByKey($key));
    }

    public function hasMessage(string $message): bool
    {
        return ! is_null($this->findKeyByMessage($message));
    }

    public function findMessageByKey(string|int $key): array|string|null
    {
        return Arr::get($this->errors, $key);
    }

    public function findKeyByMessage(string $message): array|string|int|null
    {
        if (empty($message)) {
            return null;
        }

        if (Arr::isAssoc($this->errors)) {
            return array_find(
                $this->errors,
                fn (array $messages) => collect($messages)
                    ->filter(fn (string $errorMessage) => Str::of($message)->is($errorMessage, true))
                    ->isNotEmpty()
            );
        }

        return array_find($this->errors, fn (string $errorMessage) => Str::of($message)->is($errorMessage, true));
    }

    public function appendError(array|string|null $errors = []): self
    {
        $errors = $this->sanitizeErrors($errors);
        
        // Current errors list is a single array
        if (! Arr::isAssoc($this->errors)) {
            // If the new error(s) is associative array
            // This means it conflicts with the current errors list
            if (Arr::isAssoc($errors)) {
                if ($this->isStrict()) {
                    throw new InvalidArgumentException('The provided error(s) is an associative array, which conflicts with the current error messages.');
                }

                $errors = collect($errors)
                    ->flatten()
                    ->values()
                    ->toArray();
            }
            
            $errors = collect($this->errors)
                ->push(...$errors)
                ->unique()
                ->values()
                ->toArray();
        } else {
            // If the new error(s) is a single array
            // This means it conflicts with the current errors list
            if (! Arr::isAssoc($errors)) {
                if ($this->isStrict()) {
                    throw new InvalidArgumentException('The provided error(s) is not an associative array, which conflicts with the current error messages.');
                }

                $errors = [
                    'custom_errors' => $errors,
                ];
            }

            $errors = array_merge($this->errors, $errors);
        }

        $this->setErrors($errors);

        return $this;
    }

    private function sanitizeErrors(array|string|null $errors = []): array
    {
        if (is_null($errors)) {
            $errors = [];
        }

        $errors = Arr::wrap($errors);

        if (! Arr::isAssoc($errors)) {
            $errors = $this->sanitizeSingleErrors($errors);
        } else {
            $errors = $this->sanitizeAssocErrors($errors);
        }

        return $errors;
    }

    private function sanitizeSingleErrors(array $errors = []): array
    {
        if ($this->isStrict()) {
            foreach ($errors as $index => $error) {
                if (empty($error)) {
                    throw new InvalidArgumentException("The error message at index #{$index} is empty.");
                }

                if (! is_string($error)) {
                    throw new InvalidArgumentException("The error message at index #{$index} is not a valid string.");
                }
            }
        }

        return collect($errors)
            ->filter(fn (mixed $error) => ! empty($errors) && is_string($error))
            ->unique()
            ->values()
            ->toArray();
    }

    private function sanitizeAssocErrors(array $errors = []): array
    {
        if ($this->isStrict()) {
            $index = 0;
            foreach ($errors as $key => $value) {
                if (empty($error)) {
                    throw new InvalidArgumentException("The error value at index #{$index} is empty.");
                }

                if (! is_string($key)) {
                    throw new InvalidArgumentException("The error key at index #{$index} is not a string.");
                }

                if (! is_string($value)) {
                    throw new InvalidArgumentException("The error value at index #{$index} is not a valid string.");
                }

                $index++;
            }

            return $errors;
        }

        return collect($errors)
            ->map(
                fn (mixed $value) => (! is_array($value) && ! is_string($value)) || empty($value)
                    ? null
                    : $this->sanitizeSingleErrors(Arr::flatten(Arr::wrap($value)))
            )
            ->reject(fn (mixed $value, string|int $key) => ! is_string($key) || empty($value))
            ->toArray();
    }

    public function isStrict(): bool
    {
        return $this->strict;
    }

    public function toArray(): array
    {
        return $this->errors;
    }
}