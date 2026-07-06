<?php
/**
 * PHP 8.x compatibility guard suite.
 *
 * These tests fail if any deprecated/removed-in-PHP-8 construct creeps back
 * into the library: they load every class, exercise the parsers under a strict
 * error handler, and statically scan the source for forbidden patterns.
 *
 * @license http://marcelog.github.com/PAMI/ Apache License 2.0
 */
namespace PAMI\Compat;

use PHPUnit\Framework\TestCase;
use PAMI\Message\Event\Factory\Impl\EventFactoryImpl;
use PAMI\Message\Response\Factory\Impl\ResponseFactoryImpl;

class Test_Php8Compat extends TestCase
{
    private static function srcDir()
    {
        return dirname(__DIR__, 2) . '/src';
    }

    /**
     * Recursively collect every *.php file under src/.
     *
     * @return string[]
     */
    private static function sourceFiles()
    {
        $files = array();
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(self::srcDir(), \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($it as $file) {
            if ($file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
        sort($files);
        return $files;
    }

    /**
     * @test
     * Every class must load (be declared) without emitting a deprecation.
     * AsyncAgi is skipped because it extends an optional PAGI dependency.
     */
    public function all_classes_load_without_deprecations()
    {
        $deprecations = array();
        set_error_handler(function ($no, $str, $file, $line) use (&$deprecations) {
            $deprecations[] = sprintf('%s (%s:%d)', $str, basename($file), $line);
            return true;
        }, E_DEPRECATED | E_USER_DEPRECATED);

        $loaded = 0;
        try {
            foreach (self::sourceFiles() as $file) {
                $rel = substr($file, strlen(self::srcDir() . '/PAMI/'), -4);
                if (strpos($rel, 'AsyncAgi/') === 0) {
                    continue; // requires marcelog/pagi
                }
                $class = 'PAMI\\' . str_replace('/', '\\', $rel);
                if (class_exists($class) || interface_exists($class) || trait_exists($class)) {
                    $loaded++;
                }
            }
        } finally {
            restore_error_handler();
        }

        $this->assertGreaterThan(200, $loaded);
        $this->assertSame(array(), $deprecations, "Deprecations:\n" . implode("\n", $deprecations));
    }

    /**
     * @test
     * Parsing a broad battery of real AMI events must not emit any deprecation.
     */
    public function parsing_events_emits_no_deprecation()
    {
        $eol = "\r\n";
        $samples = array(
            "Event: Dial{$eol}Privilege: call,all{$eol}SubEvent: Begin{$eol}Channel: SIP/1{$eol}",
            "Event: PeerStatus{$eol}Peer: SIP/1234{$eol}PeerStatus: Reachable{$eol}",
            "Event: VarSet{$eol}Variable: DIALSTATUS{$eol}Value: ANSWER{$eol}",
            "Event: VarSet{$eol}Variable: EMPTYVAR{$eol}Value: {$eol}",
            "Event: Hangup{$eol}Channel: SIP/x{$eol}Cause: 16{$eol}Cause-txt: Normal{$eol}",
            "Event: Newexten{$eol}Extension: 100{$eol}Priority: 1{$eol}",
            "Event: QueueMemberStatus{$eol}Queue: q1{$eol}Status: 1{$eol}Paused: 0{$eol}",
            "Event: Bridge{$eol}Bridgestate: Link{$eol}",
            "Event: CoreShowChannelsComplete{$eol}EventList: Complete{$eol}ListItems: 0{$eol}",
            "Event: TotallyUnknownEvent{$eol}Foo: bar{$eol}",
        );

        $seen = array();
        set_error_handler(function ($no, $str, $file, $line) use (&$seen) {
            $seen[] = sprintf('%s (%s:%d)', $str, basename($file), $line);
            return true;
        }, E_DEPRECATED | E_USER_DEPRECATED);

        $factory = new EventFactoryImpl();
        try {
            foreach ($samples as $raw) {
                $e = $factory->createFromRaw($raw);
                $e->getName();
                $e->getKeys();
                $e->getActionId();
                $e->getEventList();
            }
        } finally {
            restore_error_handler();
        }

        $this->assertSame(array(), $seen, "Deprecations:\n" . implode("\n", $seen));
    }

    /**
     * @test
     * Parsing responses (including ones missing optional keys) must not emit
     * any deprecation.
     */
    public function parsing_responses_emits_no_deprecation()
    {
        $eol = "\r\n";
        $samples = array(
            "Response: Success{$eol}ActionID: 1{$eol}Message: OK{$eol}",
            "Response: Error{$eol}Message: bad{$eol}",
            "Response: Success{$eol}ActionID: 2{$eol}",              // no Message / EventList
            "Response: Success{$eol}EventList: start{$eol}Message: Result will follow{$eol}",
        );

        $seen = array();
        set_error_handler(function ($no, $str, $file, $line) use (&$seen) {
            $seen[] = sprintf('%s (%s:%d)', $str, basename($file), $line);
            return true;
        }, E_DEPRECATED | E_USER_DEPRECATED);

        $factory = new ResponseFactoryImpl();
        try {
            foreach ($samples as $raw) {
                $r = $factory->createFromRaw($raw, null);
                $r->isSuccess();
                $r->isList();
                $r->isComplete();
                $r->getMessage();
                $r->getKeys();
            }
        } finally {
            restore_error_handler();
        }

        $this->assertSame(array(), $seen, "Deprecations:\n" . implode("\n", $seen));
    }

    /**
     * @test
     * Static guard: the source must not reintroduce removed/deprecated PHP 8
     * constructs.
     *
     * @dataProvider forbiddenPatternProvider
     */
    public function source_is_free_of_forbidden_patterns($pattern, $label)
    {
        $offenders = array();
        foreach (self::sourceFiles() as $file) {
            $lineNo = 0;
            foreach (file($file) as $line) {
                $lineNo++;
                // Ignore comment lines so explanatory notes don't trip the guard.
                $trimmed = ltrim($line);
                if ($trimmed !== '' && ($trimmed[0] === '*' || strpos($trimmed, '//') === 0)) {
                    continue;
                }
                if (preg_match($pattern, $line)) {
                    $offenders[] = basename($file) . ':' . $lineNo . ' ' . trim($line);
                }
            }
        }
        $this->assertSame(array(), $offenders, $label . ":\n" . implode("\n", $offenders));
    }

    public static function forbiddenPatternProvider()
    {
        return array(
            'implode(array, glue) order'   => array('/\bimplode\s*\(\s*\$[A-Za-z_]\w*\s*,\s*[\'"]/', 'implode() with array as first arg'),
            'FILTER_SANITIZE_STRING'       => array('/\bFILTER_SANITIZE_STRING\b/', 'removed FILTER_SANITIZE_STRING constant'),
            'each() language construct'    => array('/(^|[^>\w])each\s*\(/', 'removed each() function'),
            'create_function()'            => array('/\bcreate_function\s*\(/', 'removed create_function()'),
            'curly-brace string access'    => array('/\$[A-Za-z_]\w*\{[\'"0-9$]/', 'removed curly-brace offset access'),
        );
    }
}
