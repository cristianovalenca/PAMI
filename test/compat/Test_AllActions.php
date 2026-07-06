<?php
/**
 * Data-driven smoke + coverage test for every Action class.
 *
 * Instantiates each concrete PAMI\Message\Action\*Action (supplying dummy
 * constructor arguments by reflected type), serializes it, and calls every
 * public zero-argument getter. Exercises constructors, serialize() and getters
 * across the whole action set while asserting no PHP 8 deprecation is emitted.
 *
 * @license http://marcelog.github.com/PAMI/ Apache License 2.0
 */
namespace PAMI\Compat;

use PHPUnit\Framework\TestCase;

class Test_AllActions extends TestCase
{
    /**
     * @return array<string,array>
     */
    public static function actionProvider()
    {
        $dir = dirname(__DIR__, 2) . '/src/PAMI/Message/Action';
        $cases = array();
        foreach (glob($dir . '/*.php') as $file) {
            $name = basename($file, '.php');
            if ($name === 'ActionMessage') {
                continue;
            }
            $cases[$name] = array('PAMI\\Message\\Action\\' . $name);
        }
        return $cases;
    }

    /** Build a dummy value matching a reflected parameter type. */
    private function dummyFor(\ReflectionParameter $p)
    {
        $type = $p->getType();
        $name = $type instanceof \ReflectionNamedType ? $type->getName() : 'string';
        switch ($name) {
            case 'int':
                return 1;
            case 'float':
                return 1.0;
            case 'bool':
                return true;
            case 'array':
                return array();
            default:
                return 'x';
        }
    }

    /**
     * @test
     * @dataProvider actionProvider
     */
    public function every_action_serializes_and_getters_run_cleanly($class)
    {
        $ref = new \ReflectionClass($class);
        if ($ref->isAbstract()) {
            $this->markTestSkipped($class . ' is abstract');
        }

        // Build required constructor args.
        $args = array();
        $ctor = $ref->getConstructor();
        if ($ctor) {
            foreach ($ctor->getParameters() as $p) {
                if ($p->isOptional()) {
                    break;
                }
                $args[] = $this->dummyFor($p);
            }
        }

        $deprecations = array();
        set_error_handler(function ($no, $str, $file, $line) use (&$deprecations) {
            $deprecations[] = sprintf('%s (%s:%d)', $str, basename($file), $line);
            return true;
        }, E_DEPRECATED | E_USER_DEPRECATED);

        $errors = array();
        $serialized = null;
        try {
            $action = $ref->newInstanceArgs($args);
            $serialized = $action->serialize();
            $action->setActionId('unit-test');
            $action->serialize();

            foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->isStatic() || $method->isConstructor()
                    || $method->getNumberOfRequiredParameters() > 0
                    || $method->getName() === 'serialize'
                ) {
                    continue;
                }
                try {
                    $method->invoke($action);
                } catch (\Throwable $e) {
                    $errors[] = $method->getName() . '(): ' . $e->getMessage();
                }
            }
        } catch (\Throwable $e) {
            $errors[] = 'instantiation: ' . $e->getMessage();
        } finally {
            restore_error_handler();
        }

        $this->assertIsString($serialized, $class . ' did not serialize to a string');
        $this->assertStringContainsStringIgnoringCase('actionid:', $serialized);
        $this->assertSame(array(), $errors, $class . " errors:\n" . implode("\n", $errors));
        $this->assertSame(array(), $deprecations, $class . " deprecations:\n" . implode("\n", $deprecations));
    }
}
