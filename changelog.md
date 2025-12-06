
---

### `CHANGELOG.md`

```markdown
# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2025-12-06

- Initial release
- Basic `Response` class with:
  - `success()`, `error()`, `snooze()` factory methods
  - `isSuccessful()`, `isError()`, `isSnooze()` status checks
  - Fluent setters: `status()`, `message()`, `errors()`, `data()`
  - Getters: `getStatus()`, `getMessage()`, `getErrors()`, `getData()`
  - `toArray()`, `toJson()` for object conversion
