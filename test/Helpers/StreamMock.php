<?php
/**
 * Centralized socket/stream mocking for the client test suites.
 *
 * The AMI client (\PAMI\Client\Impl\ClientImpl) talks to the network through
 * unqualified calls to stream_socket_client(), fwrite(), fread(), etc. Because
 * PHP resolves unqualified function calls to the current namespace first, the
 * overrides defined below in `namespace PAMI\Client\Impl` (and the microtime()
 * override in `namespace PAMI\Message\Action`) transparently replace the real
 * functions during tests.
 *
 * This file is loaded once from test/bootstrap.php so EVERY suite has the mocks
 * available — the suites no longer depend on Test_Client.php being loaded first.
 *
 * Drive it from tests via the \PAMI\Test\StreamMock facade:
 *
 *     StreamMock::reset();                 // in setUp()
 *     StreamMock::enable();                // fake the socket + blocking calls
 *     StreamMock::mockTime();              // freeze microtime() -> 1432.123
 *     StreamMock::queue($reads, $writes);  // queue asterisk reads / expected writes
 *
 * @license http://marcelog.github.com/PAMI/ Apache License 2.0
 */

namespace PAMI\Test {

    class StreamMock
    {
        /** Freeze microtime() to a deterministic value. */
        public static $mockTime = false;

        /** Fake stream_socket_client()/stream_socket_shutdown()/stream_set_timeout(). */
        public static $socketMocked = false;

        /** Fake stream_set_blocking(). */
        public static $blockingMocked = false;

        /** Fake stream_get_line()/fread()/feof(). */
        public static $readsMocked = false;

        /** Fake fwrite(). */
        public static $writesMocked = false;

        /** @var array Queue of values asterisk "sends" back. */
        public static $reads = array();
        public static $readIndex = 0;

        /** @var array Queue of expected serialized writes (or 'fwrite error'). */
        public static $writes = array();
        public static $writeIndex = 0;

        /**
         * Restore a pristine, un-mocked state. Call from every setUp() so state
         * never leaks between tests (there is no backupGlobals for static state).
         */
        public static function reset()
        {
            self::$mockTime = false;
            self::$socketMocked = false;
            self::$blockingMocked = false;
            self::$readsMocked = false;
            self::$writesMocked = false;
            self::$reads = array();
            self::$readIndex = 0;
            self::$writes = array();
            self::$writeIndex = 0;
        }

        /** Fake the socket lifecycle (connect + blocking + timeout). */
        public static function enable()
        {
            self::$socketMocked = true;
            self::$blockingMocked = true;
        }

        /** Freeze microtime() so generated ActionIDs are deterministic. */
        public static function mockTime($on = true)
        {
            self::$mockTime = $on;
        }

        /**
         * Queue the reads asterisk will "send" and the writes we expect the
         * client to make. Also enables read/write mocking and rewinds cursors.
         *
         * @param array $reads  Lines/values returned by fread()/stream_get_line().
         *                      An int sleeps that many seconds (read-timeout tests);
         *                      false simulates a read error.
         * @param array $writes Expected serialized messages, in order. 'fwrite error'
         *                      makes the matching write return 0 (short write).
         */
        public static function queue(array $reads, array $writes = array())
        {
            self::$readsMocked = true;
            self::$writesMocked = true;
            self::$reads = $reads;
            self::$readIndex = 0;
            self::$writes = $writes;
            self::$writeIndex = 0;
        }

        /** Canonical successful login handshake. */
        public static function standardStart()
        {
            return array(
                'Asterisk Call Manager/1.1',
                'Response: Success',
                'ActionID: 1432.123',
                'Message: Authentication accepted',
                '',
                'Response: Goodbye',
                'ActionID: 1432.123',
                'Message: Thanks for all the fish.',
                '',
            );
        }

        /** Login handshake that asterisk rejects. */
        public static function standardBadLogin()
        {
            return array(
                'Asterisk Call Manager/1.1',
                'Response: Error',
                'Message: Authentication accepted',
                '',
            );
        }

        /**
         * fwrite() handler: verify the write matches the queued expectation and
         * return the number of bytes "written".
         */
        public static function handleWrite($data)
        {
            $expected = isset(self::$writes[self::$writeIndex]) ? self::$writes[self::$writeIndex] : null;
            if ($expected !== null && $expected !== false) {
                if ($expected === 'fwrite error') {
                    self::$writeIndex++;
                    return 0;
                }
                $str = $expected . "\r\n";
                if ($str !== $data) {
                    throw new \Exception(
                        'Mocked: ' . PHP_EOL . PHP_EOL . print_r($expected, true) . PHP_EOL . PHP_EOL
                        . ' is different from: ' . PHP_EOL . PHP_EOL . print_r($data, true)
                    );
                }
            }
            self::$writeIndex++;
            return strlen($data);
        }

        /** stream_get_line() handler. */
        public static function nextLine()
        {
            $result = self::$reads[self::$readIndex];
            self::$readIndex++;
            return is_string($result) ? $result . "\r\n" : $result;
        }

        /** fread() handler (an int sleeps to simulate a read timeout). */
        public static function nextRead()
        {
            $result = self::$reads[self::$readIndex];
            self::$readIndex++;
            if (is_integer($result)) {
                sleep($result);
                return '';
            }
            return is_string($result) ? $result . "\r\n" : $result;
        }
    }
}

namespace PAMI\Message\Action {

    use PAMI\Test\StreamMock;

    function microtime()
    {
        if (StreamMock::$mockTime) {
            return 1432.123;
        }
        return call_user_func_array('\microtime', func_get_args());
    }
}

namespace PAMI\Client\Impl {

    use PAMI\Test\StreamMock;

    function microtime()
    {
        if (StreamMock::$mockTime) {
            return 1432.123;
        }
        return call_user_func_array('\microtime', func_get_args());
    }

    function stream_socket_client($remote, &$errno = null, &$errstr = null, $timeout = null, $flags = null, $context = null)
    {
        if (StreamMock::$socketMocked) {
            // A real in-memory resource so the unmocked stream_set_timeout()/
            // stream_get_meta_data() calls still receive a valid resource.
            return \fopen('php://memory', 'r+');
        }
        return \stream_socket_client($remote, $errno, $errstr, $timeout, $flags, $context);
    }

    function stream_socket_shutdown($stream, $mode = STREAM_SHUT_RDWR)
    {
        if (StreamMock::$socketMocked) {
            return true;
        }
        return \stream_socket_shutdown($stream, $mode);
    }

    function stream_set_blocking($stream, $enable = true)
    {
        if (StreamMock::$blockingMocked) {
            return true;
        }
        return \stream_set_blocking($stream, $enable);
    }

    function stream_set_timeout($stream, $seconds, $microseconds = 0)
    {
        // php://memory does not support stream_set_timeout(); pretend it works.
        if (StreamMock::$socketMocked) {
            return true;
        }
        return \stream_set_timeout($stream, $seconds, $microseconds);
    }

    function fwrite($stream, $data, $length = null)
    {
        if (StreamMock::$writesMocked) {
            return StreamMock::handleWrite($data);
        }
        return $length === null ? \fwrite($stream, $data) : \fwrite($stream, $data, $length);
    }

    function stream_get_line($stream, $length, $ending = "")
    {
        if (StreamMock::$readsMocked) {
            return StreamMock::nextLine();
        }
        return \stream_get_line($stream, $length, $ending);
    }

    function feof($stream)
    {
        if (StreamMock::$readsMocked) {
            return false;
        }
        return \feof($stream);
    }

    function fread($stream, $length)
    {
        if (StreamMock::$readsMocked) {
            return StreamMock::nextRead();
        }
        return \fread($stream, $length);
    }

    /**
     * Shared event listener used by the client/event suites to capture the last
     * dispatched event.
     */
    class SomeListenerClass implements \PAMI\Listener\IEventListener
    {
        public static $event;

        public function handle(\PAMI\Message\Event\EventMessage $event)
        {
            self::$event = $event;
        }
    }
}
