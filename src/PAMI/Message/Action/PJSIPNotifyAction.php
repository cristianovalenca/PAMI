<?php
/**
 * PJSIPNotify Action
 *
 *
 *
 * Note: Auto Generated using xsltproc
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Action
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
namespace PAMI\Message\Action;

/**
 * PJSIPNotify Action
 *
 * Note: Auto Generated using xsltproc
 */
class PJSIPNotifyAction extends ActionMessage
{
    /**
     * Constructor.
     *
     * @param string $variable
     *        Appends variables as headers/content to the NOTIFY. If the variable is named Content, then the value will compose the body of the message if another variable sets Content-Type. name=value
     *
     * @return void
     */
    public function __construct($variable)
    {
        parent::__construct('PJSIPNotify');
        $this->setKey('Variable', $variable);
    }

    /**
     * set Endpoint
     *
     * @param string $endpoint
     *        The endpoint to which to send the NOTIFY.
     *
     * @return void
     */
    public function setEndpoint($endpoint)
    {
        $this->setKey('Endpoint', $endpoint);
    }
    /**
     * set URI
     *
     * @param string $uri
     *        Abritrary URI to which to send the NOTIFY.
     *
     * @return void
     */
    public function setURI($uri)
    {
        $this->setKey('URI', $uri);
    }
    /**
     * set channel
     *
     * @param string $channel
     *        Channel name to send the NOTIFY. Must be a PJSIP channel.
     *
     * @return void
     */
    public function setchannel($channel)
    {
        $this->setKey('channel', $channel);
    }
}
