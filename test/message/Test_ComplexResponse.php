<?php
/**
 * Tests for ComplexResponse: regular event accumulation, completion, and the
 * TableStart/TableEnd grouping flow.
 *
 * @license http://marcelog.github.com/PAMI/ Apache License 2.0
 */
namespace PAMI\Message\Response;

use PHPUnit\Framework\TestCase;
use PAMI\Message\Event\EventMessage;
use PAMI\Exception\PAMIException;

/**
 * Concrete EventMessage test double so we can build arbitrarily-named events
 * (TableStart/TableEnd) without depending on a generated event class — the
 * factory would otherwise resolve unknown names to UnknownEvent, which the
 * table grouping logic deliberately ignores.
 */
class TableEventDouble extends EventMessage
{
}

class Test_ComplexResponse extends TestCase
{
    private function event($raw)
    {
        return new TableEventDouble(str_replace("\n", "\r\n", $raw));
    }

    private function newResponse()
    {
        return new ComplexResponse(
            "Response: Success\r\nActionID: 1\r\nEventList: start\r\nMessage: Result will follow"
        );
    }

    /**
     * @test
     */
    public function a_list_response_starts_incomplete_without_tables()
    {
        $r = $this->newResponse();
        $this->assertFalse($r->isComplete());
        $this->assertFalse($r->hasTable());
        $this->assertSame(array(), $r->getTableNames());
        $this->assertSame(array(), $r->getEvents());
    }

    /**
     * @test
     */
    public function regular_events_are_accumulated()
    {
        $r = $this->newResponse();
        $r->addEvent($this->event("Event: PeerEntry\nObjectName: 1000"));
        $r->addEvent($this->event("Event: PeerEntry\nObjectName: 1001"));

        $events = $r->getEvents();
        $this->assertCount(2, $events);
        $this->assertFalse($r->hasTable());
    }

    /**
     * @test
     */
    public function a_complete_eventlist_marks_the_response_complete()
    {
        $r = $this->newResponse();
        $this->assertFalse($r->isComplete());
        $r->addEvent($this->event("Event: PeerlistComplete\nEventList: Complete"));
        $this->assertTrue($r->isComplete());
    }

    /**
     * @test
     * Full TableStart -> entries -> TableEnd grouping flow.
     */
    public function events_between_table_markers_are_grouped_by_table_name()
    {
        $r = $this->newResponse();
        $r->addEvent($this->event("Event: TableStart\nTableName: peers"));
        $r->addEvent($this->event("Event: PeerEntry\nObjectName: 1000"));
        $r->addEvent($this->event("Event: PeerEntry\nObjectName: 1001"));
        $r->addEvent($this->event("Event: TableEnd\nTableName: peers"));

        $this->assertTrue($r->hasTable());
        $this->assertSame(array('peers'), $r->getTableNames());

        $table = $r->getTable('peers');
        $this->assertSame('peers', $table['Name']);
        $this->assertCount(2, $table['Entries']);
        $this->assertInstanceOf(EventMessage::class, $table['Entries'][0]);

        // Grouped entries must not leak into the flat events list.
        $this->assertSame(array(), $r->getEvents());
    }

    /**
     * @test
     */
    public function multiple_tables_are_kept_separate()
    {
        $r = $this->newResponse();
        $r->addEvent($this->event("Event: TableStart\nTableName: aors"));
        $r->addEvent($this->event("Event: AorEntry\nObjectName: a1"));
        $r->addEvent($this->event("Event: TableEnd\nTableName: aors"));

        $r->addEvent($this->event("Event: TableStart\nTableName: auths"));
        $r->addEvent($this->event("Event: AuthEntry\nObjectName: u1"));
        $r->addEvent($this->event("Event: AuthEntry\nObjectName: u2"));
        $r->addEvent($this->event("Event: TableEnd\nTableName: auths"));

        $this->assertEqualsCanonicalizing(array('aors', 'auths'), $r->getTableNames());
        $this->assertCount(1, $r->getTable('aors')['Entries']);
        $this->assertCount(2, $r->getTable('auths')['Entries']);
    }

    /**
     * @test
     */
    public function get_table_throws_for_unknown_table()
    {
        $r = $this->newResponse();
        $r->addEvent($this->event("Event: TableStart\nTableName: peers"));
        $r->addEvent($this->event("Event: TableEnd\nTableName: peers"));

        $this->expectException(PAMIException::class);
        $r->getTable('does-not-exist');
    }

    /**
     * @test
     * UnknownEvents are treated as regular events, never as table markers.
     */
    public function unknown_events_are_not_grouped_as_tables()
    {
        $factory = new \PAMI\Message\Event\Factory\Impl\EventFactoryImpl();
        $unknown = $factory->createFromRaw("Event: NoSuchEventTableStart\r\nTableName: peers");
        $this->assertInstanceOf(\PAMI\Message\Event\UnknownEvent::class, $unknown);

        $r = $this->newResponse();
        $r->addEvent($unknown);
        $this->assertFalse($r->hasTable());
        $this->assertCount(1, $r->getEvents());
    }
}
