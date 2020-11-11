<?php
/**
 * Pickup Event
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
 * Pickup Event
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
class PickupEvent extends EventMessage
{
    /**
     * get Channel
     *
     * Returns key: 'Channel'
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->getKey('Channel');
    }

    /**
     * get ChannelState
     *
     * Returns:A numeric code for the channel's current state, related to ChannelStateDesc
     *
     * @return string
     */
    public function getChannelState()
    {
        return $this->getKey('ChannelState');
    }

    /**
     * get ChannelStateDesc
     *
     * Returns key: 'ChannelStateDesc'
     *
     * @return string
     */
    public function getChannelStateDesc()
    {
        return $this->getKey('ChannelStateDesc');
    }

    /**
     * get CallerIDNum
     *
     * Returns key: 'CallerIDNum'
     *
     * @return string
     */
    public function getCallerIDNum()
    {
        return $this->getKey('CallerIDNum');
    }

    /**
     * get CallerIDName
     *
     * Returns key: 'CallerIDName'
     *
     * @return string
     */
    public function getCallerIDName()
    {
        return $this->getKey('CallerIDName');
    }

    /**
     * get ConnectedLineNum
     *
     * Returns key: 'ConnectedLineNum'
     *
     * @return string
     */
    public function getConnectedLineNum()
    {
        return $this->getKey('ConnectedLineNum');
    }

    /**
     * get ConnectedLineName
     *
     * Returns key: 'ConnectedLineName'
     *
     * @return string
     */
    public function getConnectedLineName()
    {
        return $this->getKey('ConnectedLineName');
    }

    /**
     * get Language
     *
     * Returns key: 'Language'
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getKey('Language');
    }

    /**
     * get AccountCode
     *
     * Returns key: 'AccountCode'
     *
     * @return string
     */
    public function getAccountCode()
    {
        return $this->getKey('AccountCode');
    }

    /**
     * get Context
     *
     * Returns key: 'Context'
     *
     * @return string
     */
    public function getContext()
    {
        return $this->getKey('Context');
    }

    /**
     * get Exten
     *
     * Returns key: 'Exten'
     *
     * @return string
     */
    public function getExten()
    {
        return $this->getKey('Exten');
    }

    /**
     * get Priority
     *
     * Returns key: 'Priority'
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->getKey('Priority');
    }

    /**
     * get Uniqueid
     *
     * Returns key: 'Uniqueid'
     *
     * @return string
     */
    public function getUniqueid()
    {
        return $this->getKey('Uniqueid');
    }

    /**
     * get Linkedid
     *
     * Returns:Uniqueid of the oldest channel associated with this channel.
     *
     * @return string
     */
    public function getLinkedid()
    {
        return $this->getKey('Linkedid');
    }

    /**
     * get TargetChannel
     *
     * Returns key: 'TargetChannel'
     *
     * @return string
     */
    public function getTargetChannel()
    {
        return $this->getKey('TargetChannel');
    }

    /**
     * get TargetChannelState
     *
     * Returns:A numeric code for the channel's current state, related to TargetChannelStateDesc
     *
     * @return string
     */
    public function getTargetChannelState()
    {
        return $this->getKey('TargetChannelState');
    }

    /**
     * get TargetChannelStateDesc
     *
     * Returns key: 'TargetChannelStateDesc'
     *
     * @return string
     */
    public function getTargetChannelStateDesc()
    {
        return $this->getKey('TargetChannelStateDesc');
    }

    /**
     * get TargetCallerIDNum
     *
     * Returns key: 'TargetCallerIDNum'
     *
     * @return string
     */
    public function getTargetCallerIDNum()
    {
        return $this->getKey('TargetCallerIDNum');
    }

    /**
     * get TargetCallerIDName
     *
     * Returns key: 'TargetCallerIDName'
     *
     * @return string
     */
    public function getTargetCallerIDName()
    {
        return $this->getKey('TargetCallerIDName');
    }

    /**
     * get TargetConnectedLineNum
     *
     * Returns key: 'TargetConnectedLineNum'
     *
     * @return string
     */
    public function getTargetConnectedLineNum()
    {
        return $this->getKey('TargetConnectedLineNum');
    }

    /**
     * get TargetConnectedLineName
     *
     * Returns key: 'TargetConnectedLineName'
     *
     * @return string
     */
    public function getTargetConnectedLineName()
    {
        return $this->getKey('TargetConnectedLineName');
    }

    /**
     * get TargetLanguage
     *
     * Returns key: 'TargetLanguage'
     *
     * @return string
     */
    public function getTargetLanguage()
    {
        return $this->getKey('TargetLanguage');
    }

    /**
     * get TargetAccountCode
     *
     * Returns key: 'TargetAccountCode'
     *
     * @return string
     */
    public function getTargetAccountCode()
    {
        return $this->getKey('TargetAccountCode');
    }

    /**
     * get TargetContext
     *
     * Returns key: 'TargetContext'
     *
     * @return string
     */
    public function getTargetContext()
    {
        return $this->getKey('TargetContext');
    }

    /**
     * get TargetExten
     *
     * Returns key: 'TargetExten'
     *
     * @return string
     */
    public function getTargetExten()
    {
        return $this->getKey('TargetExten');
    }

    /**
     * get TargetPriority
     *
     * Returns key: 'TargetPriority'
     *
     * @return string
     */
    public function getTargetPriority()
    {
        return $this->getKey('TargetPriority');
    }

    /**
     * get TargetUniqueid
     *
     * Returns key: 'TargetUniqueid'
     *
     * @return string
     */
    public function getTargetUniqueid()
    {
        return $this->getKey('TargetUniqueid');
    }

    /**
     * get TargetLinkedid
     *
     * Returns:Uniqueid of the oldest channel associated with this channel.
     *
     * @return string
     */
    public function getTargetLinkedid()
    {
        return $this->getKey('TargetLinkedid');
    }
}
