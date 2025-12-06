# ActionResponse

A lightweight and expressive response helper designed to provide a consistent structure when returning action results in PHP applications.

This package includes:

- A `Response` class with a fluent API
- A `ResponseStatus` enum (`success`, `error`, `snooze`)
- Convenience constructors (`success()`, `error()`, `snooze()`)
- Helpers for attaching messages, errors, data, and retrieving nested data via dot notation

---

## 📦 Installation

Install via Composer:

```bash
composer require manhhuyvo/action-response
```

---

## 🚀 Basic Usage

### Create a **success** response

```php
use ManhHuyVo\ActionResponse\Response;

$response = Response::success()
    ->message('Action completed successfully.')
    ->data(['foo' => 'bar']);

return $response;
```

### Create an **error** response

```php
$response = Response::error()
    ->message('Unable to process request.')
    ->errors(['Invalid input', 'Another error']);

return $response;
```

### Create a **snooze** response  
Useful when the action should be skipped or is not applicable.

```php
$response = Response::snooze()
    ->message('Skipping this action for now.');

return $response;
```

---

## 🧱 Response Structure

A `Response` object contains:

| Property | Type | Description |
|---------|------|-------------|
| `status` | string | One of `success`, `error`, `snooze` |
| `message` | string | Optional explanation |
| `errors` | array | Unique list of errors (duplicates removed automatically) |
| `data` | array | Optional payload |

Example structure:

```php
[
    'status' => 'success',
    'message' => 'Action completed successfully.',
    'errors' => [],
    'data' => [
        'foo' => 'bar'
    ],
]
```

---

## 🔍 Status Checking

```php
$response->isSuccessful(); // true / false
$response->isError();      // true / false
$response->isSnooze();     // true / false
```

---

## 🎯 Setting Values Manually

```php
$response = (new Response())
    ->status('success')
    ->message('Done')
    ->data(['x' => 1])
    ->errors([]);

return $response;
```

---

## 📥 Accessing Nested Data (Dot Notation)

If your data is nested:

```php
$response = Response::success()->data([
    'user' => [
        'profile' => [
            'email' => 'john@example.com'
        ],
    ],
]);
```

You can fetch nested values:

```php
$response->getData('user.profile.email');

// "john@example.com"
```

---

## 🧩 ResponseStatus Enum

```php
enum ResponseStatus: string
{
    case Success = 'success';
    case Error   = 'error';
    case Snooze  = 'snooze';
}
```

---

## 🧪 Running Tests

If you are developing locally, run PHPUnit:

```bash
vendor/bin/phpunit --testdox
```

---

## 📚 Example Usage in a Real Action

```php
use ManhHuyVo\ActionResponse\Response;

function processOrder(array $order): Response
{
    if (empty($order)) {
        return Response::error()
            ->message('Order cannot be empty.')
            ->errors(['order_empty']);
    }

    if ($order['status'] === 'skipped') {
        return Response::snooze()
            ->message('Order skipped.');
    }

    return Response::success()
        ->message('Order processed.')
        ->data(['id' => 123]);
}
```

---

## 📝 License

MIT License.
