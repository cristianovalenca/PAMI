<?php
/**
 * QueueMemberPause Event
 *
 *
 * Note: Auto Generated using xsltproc
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Event
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @author     Diederik de Groot <ddegroot@gmail.com>
 * @license    http://github.com/chan-sccp/PAMI/LICENSE
 * @version    SVN: $Id$
 * @link       http://marcelog.github.com/PAMI/
 *
 * Copyright 2011 Marcelo Gornstein <marcelog@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
namespace PAMI\Message\Event;

use PAMI\Message\Event\EventMessage;

/**
 * QueueMemberPause Event
 *
 *
 * Note: Auto Generated using xsltproc
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Event
 * @author     Diederik de Groot <ddegroot@gmail.com>
 * @license    http://chan-sccp.github.com/PAMI/ Apache License 2.0
 * @link       http://github.com/chan-sccp/PAMI/
 */
class QueueMemberPauseEvent extends EventMessage
{
    /**
     * get Queue
     *
     * Returns:The name of the queue.
     *
     * @return string
     */
    public function getQueue()
    {
        return $this->getKey('Queue');
    }

    /**
     * get MemberName
     *
     * Returns:The name of the queue member.
     *
     * @return string
     */
    public function getMemberName()
    {
        return $this->getKey('MemberName');
    }

    /**
     * get Interface
     *
     * Returns:The queue member's channel technology or location.
     *
     * @return string
     */
    public function getInterface()
    {
        return $this->getKey('Interface');
    }

    /**
     * get StateInterface
     *
     * Returns:Channel technology or location from which to read device state changes.
     *
     * @return string
     */
    public function getStateInterface()
    {
        return $this->getKey('StateInterface');
    }

    /**
     * get Membership
     *
     * Returns key: 'Membership'
     *
     * @return string
     */
    public function getMembership()
    {
        return $this->getKey('Membership');
    }

    /**
     * get Penalty
     *
     * Returns:The penalty associated with the queue member.
     *
     * @return string
     */
    public function getPenalty()
    {
        return $this->getKey('Penalty');
    }

    /**
     * get CallsTaken
     *
     * Returns:The number of calls this queue member has serviced.
     *
     * @return string
     */
    public function getCallsTaken()
    {
        return $this->getKey('CallsTaken');
    }

    /**
     * get LastCall
     *
     * Returns:The time this member last took a call, expressed in seconds since 00:00, Jan 1, 1970 UTC.
     *
     * @return string
     */
    public function getLastCall()
    {
        return $this->getKey('LastCall');
    }

    /**
     * get LastPause
     *
     * Returns:The time when started last paused the queue member.
     *
     * @return string
     */
    public function getLastPause()
    {
        return $this->getKey('LastPause');
    }

    /**
     * get InCall
     *
     * Returns:Set to 1 if member is in call. Set to 0 after LastCall time is updated.
     *
     * @return string
     */
    public function getInCall()
    {
        return $this->getKey('InCall');
    }

    /**
     * get Status
     *
     * Returns:The numeric device state status of the queue member.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getKey('Status');
    }

    /**
     * get Paused
     *
     * Returns key: 'Paused'
     *
     * @return string
     */
    public function getPaused()
    {
        return $this->getKey('Paused');
    }

    /**
     * get PausedReason
     *
     * Returns:If set when paused, the reason the queue member was paused.
     *
     * @return string
     */
    public function getPausedReason()
    {
        return $this->getKey('PausedReason');
    }

    /**
     * get Ringinuse
     *
     * Returns key: 'Ringinuse'
     *
     * @return string
     */
    public function getRinginuse()
    {
        return $this->getKey('Ringinuse');
    }

    /**
     * get Wrapuptime
     *
     * Returns:The Wrapup Time of the queue member. If this value is set will override the wrapup time of queue.
     *
     * @return string
     */
    public function getWrapuptime()
    {
        return $this->getKey('Wrapuptime');
    }

    /**
     * get Reason
     *
     * Returns:The reason a member was paused.
     *
     * @return string
     */
    public function getReason()
    {
        return $this->getKey('Reason');
    }
}
