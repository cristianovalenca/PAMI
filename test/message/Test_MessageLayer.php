<?php
/**
 * Targeted coverage for the message base classes: typed key access, message
 * variables, status/channel variables, command output and the (standalone)
 * ResponseMessage class.
 *
 * @license http://marcelog.github.com/PAMI/ Apache License 2.0
 */
namespace PAMI\Message;

use PHPUnit\Framework\TestCase;
use PAMI\Message\Action\LoginAction;
use PAMI\Message\Event\Factory\Impl\EventFactoryImpl;
use PAMI\Message\Response\CommandResponse;
use PAMI\Message\Response\ResponseMessage;

class Test_MessageLayer extends TestCase
{
    private function event($raw)
    {
        return (new EventFactoryImpl())->createFromRaw(str_replace("\n", "\r\n", $raw));
    }

    /**
     * @test
     */
    public function get_bool_key_casts_and_defaults_to_null()
    {
        $e = $this->event("Event: Test\nPaused: 1\nOff: 0");
        $this->assertTrue($e->getBoolKey('Paused'));
        $this->assertFalse($e->getBoolKey('Off'));
        $this->assertNull($e->getBoolKey('Missing'));
    }

    /**
     * @test
     */
    public function message_variables_can_be_set_and_read()
    {
        $action = new LoginAction('user', 'secret');
        $action->setVariable('foo', 'bar');
        $this->assertSame('bar', $action->getVariable('foo'));
        $this->assertNull($action->getVariable('missing'));
        $this->assertSame(array('foo' => 'bar'), $action->getVariables());
    }

    /**
     * @test
     */
    public function created_date_is_a_timestamp()
    {
        $action = new LoginAction('user', 'secret');
        $this->assertIsInt($action->getCreatedDate());
        $this->assertGreaterThan(0, $action->getCreatedDate());
    }

    /**
     * @test
     */
    public function outgoing_message_serializes_a_single_variable()
    {
        $action = new LoginAction('user', 'secret');
        $action->setActionId('1');
        $action->setVariable('CHANNEL(foo)', 'bar');
        $serialized = $action->serialize();
        $this->assertStringContainsString('Variable: CHANNEL(foo)=bar', $serialized);
        $this->assertStringEndsWith("\r\n\r\n", $serialized);
    }

    /**
     * @test
     */
    public function response_handler_can_be_customised()
    {
        $action = new LoginAction('user', 'secret');
        $this->assertNull($action->getResponseHandler());
        $action->setResponseHandler('Complex');
        $this->assertStringContainsString('ComplexResponse', $action->getResponseHandler());
    }

    /**
     * @test
     */
    public function status_variables_are_exposed_per_channel()
    {
        $e = $this->event("Event: VarSet\nVariable: DIALSTATUS=ANSWER");
        $this->assertSame(array('dialstatus' => 'ANSWER'), $e->getStatusVariables());
        $this->assertSame(
            array('default' => array('dialstatus' => 'ANSWER')),
            $e->getAllStatusVariables()
        );

        $withChannel = $this->event("Event: VarSet\nChannel: SIP/1\nVariable: FOO=bar");
        $this->assertSame(array('foo' => 'bar'), $withChannel->getStatusVariables('SIP/1'));
        $this->assertNull($withChannel->getStatusVariables('SIP/does-not-exist'));
    }

    /**
     * @test
     */
    public function command_response_collects_output_lines()
    {
        $r = new CommandResponse(
            "Response: Follows\r\nActionID: 1\r\nMessage: Command output follows\r\n"
            . "Output: line1\r\nOutput: line2"
        );
        $this->assertSame(array('line1', 'line2'), $r->getCommandOutputArray());
        $this->assertSame("line1\r\nline2", $r->getCommandOutput());
        $this->assertTrue($r->isCommandFinished());
    }

    /**
     * @test
     * ResponseMessage is a standalone response class (not produced by the
     * factory, which returns Response subclasses). Exercise its public surface.
     */
    public function response_message_reports_success_and_list_state()
    {
        $ok = new ResponseMessage("Response: Success\r\nActionID: 7\r\nMessage: done");
        $this->assertTrue($ok->isSuccess());
        $this->assertFalse($ok->isList());
        $this->assertSame('done', $ok->getMessage());
        $this->assertSame(array(), $ok->getEvents());

        $err = new ResponseMessage("Response: Error\r\nMessage: nope");
        $this->assertFalse($err->isSuccess());
    }

    /**
     * @test
     */
    public function response_message_accumulates_and_completes_on_events()
    {
        $r = new ResponseMessage("Response: Success\r\nActionID: 9\r\nEventList: start");
        $r->addEvent($this->event("Event: PeerEntry\nObjectName: 1000"));
        $this->assertCount(1, $r->getEvents());

        $r->addEvent($this->event("Event: PeerlistComplete\nEventList: Complete"));
        $this->assertCount(2, $r->getEvents());

        // __sleep controls serialization; must include the extra fields.
        $sleep = $r->__sleep();
        $this->assertContains('events', $sleep);
        $this->assertContains('completed', $sleep);
    }
}
