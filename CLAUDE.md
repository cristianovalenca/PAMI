# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

PAMI (PHP Asterisk Manager Interface) is a library that talks to an Asterisk AMI
over TCP/TLS. It is event-driven and uses an observer/listener pattern: you send
**Actions** and receive **Events** and **Responses**. This repo is a fork of
`marcelog/PAMI` (package name `cristianovalenca/pami`), maintained for PHP 8.x.

PSR-4 autoload: `PAMI\` → `src/PAMI`.

## Commands

```bash
composer install                       # install dev deps (PHPUnit 9.6, etc.)
composer test                          # run the whole suite (alias for phpunit)
vendor/bin/phpunit                     # same, uses phpunit.xml.dist at repo root
vendor/bin/phpunit --testsuite Message # one suite: Message | Client | Actions | Events | Compat
vendor/bin/phpunit --filter varset_exposes_variable_name_and_value   # one test by method name
composer cs                            # phpcs PSR12 over src (not enforced in CI)
composer test-coverage                 # text coverage report (needs pcov/xdebug)
```

Requires PHP 8.x locally (developed against 8.4). PHPUnit 4-era config still lives
in `test/resources/` but is dead — the active config is the root `phpunit.xml.dist`
with bootstrap `test/bootstrap.php`.

**Coverage can't be measured locally** on the default Laravel Herd PHP — it ships
no pcov/xdebug and there is no `pecl`/`phpize`. Measure it in CI instead: push and
read the **Coverage** job in `.github/workflows/tests.yml` (runs PHP 8.3 + pcov).
Baseline is ~98% lines; don't let a change drop it.

## Commits

- **Never reference Claude Code, Claude, or any AI assistant in commits.** No
  `Co-Authored-By: Claude ...` trailer, no "Generated with Claude Code" line, no
  mention in the subject or body. Write commit messages as if authored by hand.
- This repo is a fork; keep changes focused and the test suite green before
  committing (`composer test` must pass).

## Architecture

The whole library revolves around one message hierarchy and two factories.

**Message model** (`src/PAMI/Message/`):
- `Message` (abstract base) — holds the `keys` map, defines `EOL`/`EOM`
  (`\r\n` / `\r\n\r\n`), and `sanitizeInput()` which auto-types incoming values:
  numeric → int/float, `yes/on/true`→`true`, `no/off/false`→`false`, values with
  a leading `0`/`+` stay strings, empty → `null`.
- `OutgoingMessage` → all `Message/Action/*Action` classes. `serialize()` turns
  them into the wire format; each carries an ActionID and may declare a custom
  response handler via `setResponseHandler()`.
- `IncomingMessage` → `Message/Event/*Event` and `Message/Response/*`. Its
  constructor parses the raw AMI text line-by-line and extracts channel/status
  variables (`ChanVariable:` / `Variable:` lines) into separate maps.

**Factories decide concrete classes by convention, so there is no registry to
update when adding messages:**
- `EventFactoryImpl` maps `Event: some_name` → `SomeNameEvent` (underscores →
  CamelCase); an unrecognized name becomes `UnknownEvent`.
- `ResponseFactoryImpl` returns `GenericResponse` unless the requesting Action
  declared a handler (e.g. `CommandAction` → `CommandResponse`,
  which only completes once its `Message` contains "command output follows").

**Client** (`src/PAMI/Client/Impl/ClientImpl.php`) is the only stateful piece:
`open()` connects + logs in, `send()` writes an Action and blocks until the
correlated Response arrives, and `process()` reads the socket, splits on `EOM`,
then either **correlates a Response by ActionID** (via the `incomingQueue`) or
**dispatches an Event** to registered listeners. Listeners can be an
`IEventListener`, a Closure, or `[object, method]`, each with an optional
predicate filter.

To add a new Action or Event, just create `PAMI\Message\Action\XAction` /
`PAMI\Message\Event\XEvent`; the factories resolve it by name — no wiring needed.

## Conventions that bite

- **`getKey($name)` returns `null` for absent keys.** Any string function on a key
  value (`stristr`, `strlen`, `implode`, `preg_match`, …) must cast with `(string)`
  or `?? ''` — PHP 8.1 deprecates passing `null` to these. The message classes
  already do this; keep it that way.
- **PHP 8.x compatibility is test-enforced.** The `Compat` suite (`test/compat/`)
  is the safety net: `Test_Php8Compat` loads every class under `E_ALL`, parses a
  battery of events/responses, and statically greps `src/` for forbidden constructs
  (`FILTER_SANITIZE_STRING`, `implode(array, glue)`, `each()`, `create_function()`,
  curly-brace offset access). `Test_AllActions` / `Test_AllEvents` are data-driven:
  they instantiate **every** Action/Event, serialize/call every zero-arg getter, and
  assert no deprecation is emitted — so a new message class is auto-covered and
  auto-guarded (this is how the `urldecode(null)` bugs were caught). Adding a new
  Action/Event needs no test wiring; do not reintroduce the forbidden constructs, and
  extend the guard list when you find a new one.
- **The client tests mock PHP's stream functions by redefining them** in
  `namespace PAMI\Client\Impl` / `PAMI\Message\Action` (`stream_socket_client`,
  `fwrite`, `fread`, `microtime`, …). These live in `test/Helpers/StreamMock.php`,
  loaded from `test/bootstrap.php`, so every suite runs standalone. Drive them via
  the `PAMI\Test\StreamMock` facade: `StreamMock::reset()` in `setUp()`, then
  `enable()` (fake socket), `mockTime()` (freeze microtime → `1432.123` for
  deterministic ActionIDs), and `queue($reads, $writes)` (asterisk reads / expected
  writes). State is static — always `reset()` in `setUp()` so it can't leak.
- **`Response.php` is the live abstract base** (extended by `GenericResponse`,
  `ComplexResponse`, `CommandResponse`); `ResponseMessage.php` is a leftover
  duplicate from the PSR-4 rename and is not what the factory returns.
- **`send()` takes exactly one argument** (`send(OutgoingMessage $message)`). Any
  extra callback passed to it is silently dropped by PHP. `open()` must therefore
  check the login response's `isSuccess()` on the return value of `send()` — do not
  pass a success/error closure to `send()` expecting it to run.
- **`send()` blocks until the response for its ActionID is `isComplete()`**, else it
  throws `ClientException('Read timeout')`. List/complex responses only complete
  once their terminating child event arrives (`EventList: Complete`), and some
  handlers self-complete from a key (e.g. `CommandResponse` needs its `Message` to
  contain "command output follows"). When a new response type hangs `send()`, the
  cause is almost always its completion condition never being met.
- `AsyncAgi/AsyncClientImpl` extends the optional `marcelog/pagi` dependency and is
  excluded from the compat class-loading test and from coverage (not installed).

## Releasing

The package is published on Packagist as `cristianovalenca/pami`, auto-updated by a
GitHub webhook on every push. **Publishing changes to consumers requires a version
tag** — pushing to `master` (`dev-master`) alone does NOT reach projects that pin a
stable constraint like `^2.0`. To release: tag `vX.Y.Z` (patch for fixes) and push
the tag; Packagist picks it up automatically. Latest is `v2.0.3`.
