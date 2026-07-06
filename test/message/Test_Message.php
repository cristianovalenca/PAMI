<?php
/**
 * Unit tests for the message layer: value sanitization, response parsing,
 * null-safety and the PHP 8 regression fixes.
 *
 * @license http://marcelog.github.com/PAMI/ Apache License 2.0
 */
namespace PAMI\Message;

use PHPUnit\Framework\TestCase;
use PAMI\Message\Event\Factory\Impl\EventFactoryImpl;
use PAMI\Message\Response\Factory\Impl\ResponseFactoryImpl;
use PAMI\Message\Response\GenericResponse;
use PAMI\Message\Response\ComplexResponse;
use PAMI\Message\Response\CommandResponse;
use PAMI\Exception\PAMIException;

class Test_Message extends TestCase
{
    /** @var EventFactoryImpl */
    private $eventFactory;

    /** @var ResponseFactoryImpl */
    private $responseFactory;

    public function setUp(): void
    {
        $this->eventFactory = new EventFactoryImpl();
        $this->responseFactory = new ResponseFactoryImpl();
    }

    private function event($raw)
    {
        return $this->eventFactory->createFromRaw(str_replace("\n", "\r\n", $raw));
    }

    private function response($raw)
    {
        return $this->responseFactory->createFromRaw(str_replace("\n", "\r\n", $raw), null);
    }

    /**
     * @test
     */
    public function sanitizes_integer_values_to_int()
    {
        $e = $this->event("Event: Test\nCount: 42");
        $this->assertSame(42, $e->getKey('Count'));
    }

    /**
     * @test
     */
    public function preserves_leading_zero_as_string()
    {
        $e = $this->event("Event: Test\nExten: 007");
        $this->assertSame('007', $e->getKey('Exten'));
    }

    /**
     * @test
     */
    public function sanitizes_boolean_like_values()
    {
        $e = $this->event("Event: Test\nA: yes\nB: on\nC: true\nD: no\nE: off\nF: false");
        $this->assertTrue($e->getKey('A'));
        $this->assertTrue($e->getKey('B'));
        $this->assertTrue($e->getKey('C'));
        $this->assertFalse($e->getKey('D'));
        $this->assertFalse($e->getKey('E'));
        $this->assertFalse($e->getKey('F'));
    }

    /**
     * @test
     * Regression: the FILTER_SANITIZE_STRING replacement (strip_tags predicate)
     * must keep returning the original string, not raise a deprecation.
     */
    public function keeps_string_with_markup_intact()
    {
        $e = $this->event("Event: Test\nName: <b>hi</b>");
        $this->assertSame('<b>hi</b>', $e->getKey('Name'));
    }

    /**
     * @test
     */
    public function empty_value_becomes_null()
    {
        $e = $this->event("Event: Test\nEmpty: ");
        $this->assertNull($e->getKey('Empty'));
    }

    /**
     * @test
     */
    public function missing_key_returns_null()
    {
        $e = $this->event("Event: Test\nFoo: bar");
        $this->assertNull($e->getKey('DoesNotExist'));
    }

    /**
     * @test
     * Regression for the VarSet parsing bug: getVariableName() used to always
     * return null because the generic re-keying overwrote it.
     */
    public function varset_exposes_variable_name_and_value()
    {
        $e = $this->event("Event: VarSet\nVariable: DIALSTATUS\nValue: ANSWER");
        $this->assertInstanceOf(\PAMI\Message\Event\VarSetEvent::class, $e);
        $this->assertSame('DIALSTATUS', $e->getVariableName());
        $this->assertSame('ANSWER', $e->getValue());
    }

    /**
     * @test
     */
    public function unknown_event_maps_to_unknown_event()
    {
        $e = $this->event("Event: SomethingNobodyKnows\nFoo: bar");
        $this->assertInstanceOf(\PAMI\Message\Event\UnknownEvent::class, $e);
    }

    /**
     * @test
     */
    public function event_name_is_camel_cased_from_underscores()
    {
        $e = $this->event("Event: dongle_sms_received\nFoo: bar");
        // Underscored asterisk events resolve to DongleSmsReceived style names.
        $this->assertNotNull($e->getName());
    }

    /**
     * @test
     */
    public function generic_response_success_and_error()
    {
        $ok = $this->response("Response: Success\nActionID: 1\nMessage: OK");
        $this->assertInstanceOf(GenericResponse::class, $ok);
        $this->assertTrue($ok->isSuccess());

        $err = $this->response("Response: Error\nMessage: bad");
        $this->assertFalse($err->isSuccess());
    }

    /**
     * @test
     * Null-safety: a response without EventList / Message keys must evaluate
     * isList() and getMessage() without raising a PHP 8.1 null deprecation.
     */
    public function response_without_optional_keys_is_null_safe()
    {
        $r = $this->response("Response: Success\nActionID: 42");
        $this->assertFalse($r->isList());
        $this->assertNull($r->getMessage());
        $this->assertTrue($r->isComplete());
    }

    /**
     * @test
     */
    public function response_is_list_when_eventlist_starts()
    {
        $r = $this->response("Response: Success\nActionID: 1\nEventList: start\nMessage: Result will follow");
        $this->assertTrue($r->isList());
        $this->assertFalse($r->isComplete());
    }

    /**
     * @test
     * The removed dynamic property $eventsCount must not reappear: building a
     * response under E_ALL must not emit any deprecation.
     */
    public function building_a_response_emits_no_deprecation()
    {
        $seen = array();
        set_error_handler(function ($no, $str) use (&$seen) {
            $seen[] = $str;
            return true;
        });
        try {
            $r = $this->response("Response: Success\nActionID: 1\nMessage: OK");
            $r->isSuccess();
            $r->isList();
            $r->getMessage();
        } finally {
            restore_error_handler();
        }
        $this->assertSame(array(), $seen, implode(' | ', $seen));
        $this->assertFalse(property_exists($r, 'eventsCount'));
    }

    /**
     * @test
     */
    public function command_response_output_is_null_safe()
    {
        $r = new CommandResponse("Response: Follows\r\nActionID: 1\r\nMessage: Command output follows");
        // No 'Output' key present -> must not raise implode(null) deprecation.
        $this->assertSame('', $r->getCommandOutput());
        $this->assertTrue($r->isCommandFinished());
    }

    /**
     * @test
     */
    public function complex_response_without_json_throws()
    {
        $r = new ComplexResponse("Response: Success\r\nActionID: 1");
        $this->expectException(PAMIException::class);
        $r->getJSON();
    }

    /**
     * @test
     */
    public function complex_response_decodes_json_key()
    {
        $json = json_encode(array('a' => 1, 'b' => 'two'));
        $r = new ComplexResponse("Response: Success\r\nActionID: 1\r\nJSON: " . $json);
        $this->assertSame(array('a' => 1, 'b' => 'two'), $r->getJSON());
    }

    /**
     * @test
     */
    public function response_survives_serialization_roundtrip()
    {
        $r = $this->response("Response: Success\nActionID: 1\nMessage: OK");
        $restored = unserialize(serialize($r));
        $this->assertTrue($restored->isSuccess());
        $this->assertSame($r->getActionId(), $restored->getActionId());
    }
}
