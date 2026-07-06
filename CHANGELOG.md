# Changelog

All notable changes to `cristianovalenca/pami` will be documented in this file.

This project is a fork of [`marcelog/PAMI`](https://github.com/marcelog/PAMI),
maintained for PHP 8.x. The format is based on
[Keep a Changelog](https://keepachangelog.com/en/1.0.0/) and this project adheres
to [Semantic Versioning](https://semver.org/).

## [Unreleased]

## [2.0.4] - 2026-07-07

### Fixed
- `ComplexResponse` table parsing called `$event->getTableName()`, a method that
  did not exist — any AMI event whose name contained `TableStart`/`TableEnd` would
  raise a fatal "call to undefined method". Added `EventMessage::getTableName()`.
- `ComplexResponse::getTableNames()` called `array_keys()` on a possibly-null
  property (TypeError on PHP 8 when no table had been collected).
- `urldecode(null)` deprecations in `AsyncAGIEvent`, `AsyncAGIExecEvent` and
  `AsyncAGIStartEvent`.

### Added
- GitHub Actions CI matrix over PHP 8.1–8.4, plus a coverage job (~98% line
  coverage).
- Data-driven `Test_AllActions` / `Test_AllEvents` suites that instantiate every
  Action/Event and assert no PHP 8 deprecation is emitted.
- `SECURITY.md`, `CONTRIBUTING.md` and this `CHANGELOG.md`.

### Changed
- Centralized the socket/stream test mocks into `test/Helpers/StreamMock.php`
  (loaded from the test bootstrap) so every test suite runs standalone.

## [2.0.3] - 2026-07-06

### Fixed
- Replaced `FILTER_SANITIZE_STRING` (removed in PHP 8.x) with a `strip_tags`
  predicate that preserves the original return behaviour.
- Guarded possibly-null `getKey()`/`getMessage()` results before string functions
  (`stristr`, `strlen`, `implode`, `preg_match`) to avoid the PHP 8.1 null
  deprecation.
- Removed the undeclared dynamic properties `$eventsCount` and `$_lastActionId`
  (deprecated in PHP 8.2); the latter was also a latent reset bug.
- `ClientImpl::open()` now checks the login response's `isSuccess()` directly; the
  previous success/error closure was silently dropped by `send()`.
- `VarSet` parsing no longer overwrites the variable name with `null`, so
  `getVariableName()` works again.

### Changed
- Modernized dev dependencies (PHPUnit `^9.6`, dropped abandoned packages) and
  widened the supported PHP constraint to `>=7.1`.
- Converted the existing Client/Actions/Events test suites to PHPUnit 9.

## [2.0.2] and earlier

See the [git history](https://github.com/cristianovalenca/PAMI/commits/master) and
the upstream [`marcelog/PAMI`](https://github.com/marcelog/PAMI) changelog.

[Unreleased]: https://github.com/cristianovalenca/PAMI/compare/v2.0.4...HEAD
[2.0.4]: https://github.com/cristianovalenca/PAMI/compare/v2.0.3...v2.0.4
[2.0.3]: https://github.com/cristianovalenca/PAMI/releases/tag/v2.0.3
