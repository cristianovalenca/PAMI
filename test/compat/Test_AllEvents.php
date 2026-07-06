<?php
/**
 * Data-driven smoke + coverage test for every Event class.
 *
 * Instantiates each concrete PAMI\Message\Event\*Event, then calls every public
 * zero-argument getter. This exercises the getter bodies (broad line coverage)
 * and, under a strict error handler, guarantees no getter emits a PHP 8
 * deprecation (e.g. passing a null key to a string function).
 *
 * @license http://marcelog.github.com/PAMI/ Apache License 2.0
 */
namespace PAMI\Compat;

use PHPUnit\Framework\TestCase;

class Test_AllEvents extends TestCase
{
    /** Common AMI keys so getters that read/transform values have data. */
    private function commonKeys()
    {
        return implode("\r\n", array(
            'Privilege: system,all',
            'Channel: SIP/1000-00000001',
            'Uniqueid: 1600000000.1',
            'Linkedid: 1600000000.1',
            'CallerIDNum: 1000',
            'CallerIDName: Tester',
            'ConnectedLineNum: 2000',
            'Exten: 100',
            'Context: default',
            'Priority: 1',
            'Cause: 16',
            'Cause-txt: Normal Clearing',
            'Queue: support',
            'Interface: SIP/1000',
            'MemberName: Agent/1000',
            'Membership: dynamic',
            'Paused: 1',
            'Penalty: 0',
            'Status: 1',
            'ObjectName: 1000',
            'ObjectType: endpoint',
            'EndpointName: 1000',
            'DeviceState: NOT_INUSE',
            'Variable: FOO',
            'Value: bar',
            'Env: %28agi_request%29',
            'Result: %32%30%30',
            'TableName: peers',
            'ActionID: 1600000000.1',
        ));
    }

    /**
     * @return array<string,string> class name => file
     */
    public static function eventProvider()
    {
        $dir = dirname(__DIR__, 2) . '/src/PAMI/Message/Event';
        $cases = array();
        foreach (glob($dir . '/*.php') as $file) {
            $name = basename($file, '.php');
            if ($name === 'EventMessage') {
                continue;
            }
            $cases[$name] = array('PAMI\\Message\\Event\\' . $name);
        }
        return $cases;
    }

    /**
     * @test
     * @dataProvider eventProvider
     */
    public function every_event_getter_runs_cleanly($class)
    {
        $ref = new \ReflectionClass($class);
        if ($ref->isAbstract()) {
            $this->markTestSkipped($class . ' is abstract');
        }

        $deprecations = array();
        set_error_handler(function ($no, $str, $file, $line) use (&$deprecations) {
            $deprecations[] = sprintf('%s (%s:%d)', $str, basename($file), $line);
            return true;
        }, E_DEPRECATED | E_USER_DEPRECATED);

        $errors = array();
        try {
            $event = $ref->newInstance("Event: " . $ref->getShortName() . "\r\n" . $this->commonKeys());
            foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->isStatic() || $method->isConstructor()
                    || $method->getNumberOfRequiredParameters() > 0
                ) {
                    continue;
                }
                try {
                    $method->invoke($event);
                } catch (\Throwable $e) {
                    $errors[] = $method->getName() . '(): ' . $e->getMessage();
                }
            }
        } finally {
            restore_error_handler();
        }

        $this->assertSame(array(), $errors, $class . " getter errors:\n" . implode("\n", $errors));
        $this->assertSame(array(), $deprecations, $class . " deprecations:\n" . implode("\n", $deprecations));
    }
}
