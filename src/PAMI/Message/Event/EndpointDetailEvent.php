<?php
/**
 * Event from PJSIPShowEndpoint
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Event
 * @author     Niklas Larsson <niklas@tese.se>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @version    SVN: $Id$
 * @link       http://marcelog.github.com/PAMI/
 *
 * Copyright 2015 Niklas Larsson <niklas@tese.se>, Marcelo Gornstein <marcelog@gmail.com>
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
 * Event from PJSIPShowEndpoint
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Event
 * @author     Niklas Larsson <niklas@tese.se>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @link       http://marcelog.github.com/PAMI/
 */
class EndpointDetailEvent extends EventMessage
{
    /**
     * Returns key: 'ObjectType'. The object's type. This will always be 'endpoint'.
     *
     * @return string
     */
    public function getObjectType()
    {
        return $this->getKey('ObjectType');
    }

    /**
     * Returns key: 'ObjectName'. The name of this object.
     *
     * @return string
     */
    public function getObjectName()
    {
        return $this->getKey('ObjectName');
    }

    /**
     * Returns key: 'Context'. Dialplan context for inbound sessions
     *
     * @return string
     */
    public function getContext()
    {
        return $this->getKey('Context');
    }

    /**
     * Returns key: 'Disallow'. Media Codec(s) to disallow
     *
     * @return string
     */
    public function getDisallow()
    {
        return $this->getKey('Disallow');
    }

    /**
     * Returns key: 'Allow'. Media Codec(s) to allow
     *
     * @return string
     */
    public function getAllow()
    {
        return $this->getKey('Allow');
    }

    /**
     * Returns key: 'DtmfMode'. DTMF mode
     *
     * @return string
     */
    public function getDtmfMode()
    {
        return $this->getKey('DtmfMode');
    }

    /**
     * Returns key: 'RtpIpv6'. Allow use of IPv6 for RTP traffic
     *
     * @return string
     */
    public function getRtpIpv6()
    {
        return $this->getKey('RtpIpv6');
    }

    /**
     * Returns key: 'RtpSymmetric'. Enforce that RTP must be symmetric
     *
     * @return string
     */
    public function getRtpSymmetric()
    {
        return $this->getKey('RtpSymmetric');
    }

    /**
     * Returns key: 'IceSupport'. Enable the ICE mechanism to help traverse NAT
     *
     * @return string
     */
    public function getIceSupport()
    {
        return $this->getKey('IceSupport');
    }

    /**
     * Returns key: 'UsePtime'. Use Endpoint's requested packetisation interval
     *
     * @return string
     */
    public function getUsePtime()
    {
        return $this->getKey('UsePtime');
    }

    /**
     * Returns key: 'ForceRport'. Force use of return port
     *
     * @return string
     */
    public function getForceRport()
    {
        return $this->getKey('ForceRport');
    }

    /**
     * Returns key: 'RewriteContact'. Allow Contact header to be rewritten with the source IP address-port
     *
     * @return string
     */
    public function getRewriteContact()
    {
        return $this->getKey('RewriteContact');
    }

    /**
     * Returns key: 'Transport'. Desired transport configuration
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->getKey('Transport');
    }

    /**
     * Returns key: 'OutboundProxy'. Proxy through which to send requests, a full SIP URI must be provided
     *
     * @return string
     */
    public function getOutboundProxy()
    {
        return $this->getKey('OutboundProxy');
    }

    /**
     * Returns key: 'MohSuggest'. Default Music On Hold class
     *
     * @return string
     */
    public function getMohSuggest()
    {
        return $this->getKey('MohSuggest');
    }

    /**
     * Returns key: '100rel'. Allow support for RFC3262 provisional ACK tags
     *
     * @return string
     */
    public function get100rel()
    {
        return $this->getKey('100rel');
    }

    /**
     * Returns key: 'Timers'. Session timers for SIP packets
     *
     * @return string
     */
    public function getTimers()
    {
        return $this->getKey('Timers');
    }

    /**
     * Returns key: 'TimersMinSe'. Minimum session timers expiration period
     *
     * @return string
     */
    public function getTimersMinSe()
    {
        return $this->getKey('TimersMinSe');
    }

    /**
     * Returns key: 'TimersSessExpires'. Maximum session timer expiration period
     *
     * @return string
     */
    public function getTimersSessExpires()
    {
        return $this->getKey('TimersSessExpires');
    }

    /**
     * Returns key: 'Auth'. Authentication Object(s) associated with the endpoint
     *
     * @return string
     */
    public function getAuth()
    {
        return $this->getKey('Auth');
    }

    /**
     * Returns key: 'OutboundAuth'. Authentication object used for outbound requests
     *
     * @return string
     */
    public function getOutboundAuth()
    {
        return $this->getKey('OutboundAuth');
    }

    /**
     * Returns key: 'Aors'. AoR(s) to be used with the endpoint
     *
     * @return string
     */
    public function getAors()
    {
        return $this->getKey('Aors');
    }

    /**
     * Returns key: 'MediaAddress'. IP address used in SDP for media handling
     *
     * @return string
     */
    public function getMediaAddress()
    {
        return $this->getKey('MediaAddress');
    }

    /**
     * Returns key: 'IdentifyBy'. Way(s) for Endpoint to be identified
     *
     * @return string
     */
    public function getIdentifyBy()
    {
        return $this->getKey('IdentifyBy');
    }

    /**
     * Returns key: 'DirectMedia'. Determines whether media may flow directly between endpoints.
     *
     * @return string
     */
    public function getDirectMedia()
    {
        return $this->getKey('DirectMedia');
    }

    /**
     * Returns key: 'DirectMediaMethod'. Direct Media method type
     *
     * @return string
     */
    public function getDirectMediaMethod()
    {
        return $this->getKey('DirectMediaMethod');
    }

    /**
     * Returns key: 'ConnectedLineMethod'. Connected line method type
     *
     * @return string
     */
    public function getConnectedLineMethod()
    {
        return $this->getKey('ConnectedLineMethod');
    }

    /**
     * Returns key: 'DirectMediaGlareMitigation'. Mitigation of direct media (re)INVITE glare
     *
     * @return string
     */
    public function getDirectMediaGlareMitigation()
    {
        return $this->getKey('DirectMediaGlareMitigation');
    }

    /**
     * Returns key: 'DisableDirectMediaOnNat'. Disable direct media session refreshes when NAT obstructs the media session
     *
     * @return string
     */
    public function getDisableDirectMediaOnNat()
    {
        return $this->getKey('DisableDirectMediaOnNat');
    }

    /**
     * Returns key: 'Callerid'. CallerID information for the endpoint
     *
     * @return string
     */
    public function getCallerid()
    {
        return $this->getKey('Callerid');
    }

    /**
     * Returns key: 'CalleridPrivacy'. Default privacy level
     *
     * @return string
     */
    public function getCalleridPrivacy()
    {
        return $this->getKey('CalleridPrivacy');
    }

    /**
     * Returns key: 'CalleridTag'. Internal id_tag for the endpoint
     *
     * @return string
     */
    public function getCalleridTag()
    {
        return $this->getKey('CalleridTag');
    }

    /**
     * Returns key: 'TrustIdInbound'. Accept identification information received from this endpoint
     *
     * @return string
     */
    public function getTrustIdInbound()
    {
        return $this->getKey('TrustIdInbound');
    }

    /**
     * Returns key: 'TrustIdOutbound'. Send private identification details to the endpoint.
     *
     * @return string
     */
    public function getTrustIdOutbound()
    {
        return $this->getKey('TrustIdOutbound');
    }

    /**
     * Returns key: 'SendPai'. Send the P-Asserted-Identity header
     *
     * @return string
     */
    public function getSendPai()
    {
        return $this->getKey('SendPai');
    }

    /**
     * Returns key: 'SendRpid'. Send the Remote-Party-ID header
     *
     * @return string
     */
    public function getSendRpid()
    {
        return $this->getKey('SendRpid');
    }

    /**
     * Returns key: 'SendDiversion'. Send the Diversion header, conveying the diversion information to the called user agent
     *
     * @return string
     */
    public function getSendDiversion()
    {
        return $this->getKey('SendDiversion');
    }

    /**
     * Returns key: 'Mailboxes'. NOTIFY the endpoint when state changes for any of the specified mailboxes
     *
     * @return string
     */
    public function getMailboxes()
    {
        return $this->getKey('Mailboxes');
    }

    /**
     * Returns key: 'AggregateMwi'. Condense MWI notifications into a single NOTIFY.
     *
     * @return string
     */
    public function getAggregateMwi()
    {
        return $this->getKey('AggregateMwi');
    }

    /**
     * Returns key: 'MediaEncryption'. Determines whether res_pjsip will use and enforce usage of media encryption for this endpoint.
     *
     * @return string
     */
    public function getMediaEncryption()
    {
        return $this->getKey('MediaEncryption');
    }

    /**
     * Returns key: 'MediaEncryptionOptimistic'. Determines whether encryption should be used if possible but does not terminate the session if not achieved.
     *
     * @return string
     */
    public function getMediaEncryptionOptimistic()
    {
        return $this->getKey('MediaEncryptionOptimistic');
    }

    /**
     * Returns key: 'UseAvpf'. Determines whether res_pjsip will use and enforce usage of AVPF for this endpoint.
     *
     * @return string
     */
    public function getUseAvpf()
    {
        return $this->getKey('UseAvpf');
    }

    /**
     * Returns key: 'ForceAvp'. Determines whether res_pjsip will use and enforce usage of AVP, regardless of the RTP profile in use for this endpoint.
     *
     * @return string
     */
    public function getForceAvp()
    {
        return $this->getKey('ForceAvp');
    }

    /**
     * Returns key: 'MediaUseReceivedTransport'. Determines whether res_pjsip will use the media transport received in the offer SDP in the corresponding answer SDP.
     *
     * @return string
     */
    public function getMediaUseReceivedTransport()
    {
        return $this->getKey('MediaUseReceivedTransport');
    }

    /**
     * Returns key: 'OneTouchRecording'. Determines whether one-touch recording is allowed for this endpoint.
     *
     * @return string
     */
    public function getOneTouchRecording()
    {
        return $this->getKey('OneTouchRecording');
    }

    /**
     * Returns key: 'InbandProgress'. Determines whether chan_pjsip will indicate ringing using inband progress.
     *
     * @return string
     */
    public function getInbandProgress()
    {
        return $this->getKey('InbandProgress');
    }

    /**
     * Returns key: 'CallGroup'. The numeric pickup groups for a channel.
     *
     * @return string
     */
    public function getCallGroup()
    {
        return $this->getKey('CallGroup');
    }

    /**
     * Returns key: 'PickupGroup'. The numeric pickup groups that a channel can pickup.
     *
     * @return string
     */
    public function getPickupGroup()
    {
        return $this->getKey('PickupGroup');
    }

    /**
     * Returns key: 'NamedCallGroup'. The named pickup groups for a channel.
     *
     * @return string
     */
    public function getNamedCallGroup()
    {
        return $this->getKey('NamedCallGroup');
    }

    /**
     * Returns key: 'NamedPickupGroup'. The named pickup groups that a channel can pickup.
     *
     * @return string
     */
    public function getNamedPickupGroup()
    {
        return $this->getKey('NamedPickupGroup');
    }

    /**
     * Returns key: 'DeviceStateBusyAt'. The number of in-use channels which will cause busy to be returned as device state
     *
     * @return string
     */
    public function getDeviceStateBusyAt()
    {
        return $this->getKey('DeviceStateBusyAt');
    }

    /**
     * Returns key: 'T38Udptl'. Whether T.38 UDPTL support is enabled or not
     *
     * @return string
     */
    public function getT38Udptl()
    {
        return $this->getKey('T38Udptl');
    }

    /**
     * Returns key: 'T38UdptlEc'. T.38 UDPTL error correction method
     *
     * @return string
     */
    public function getT38UdptlEc()
    {
        return $this->getKey('T38UdptlEc');
    }

    /**
     * Returns key: 'T38UdptlMaxdatagram'. T.38 UDPTL maximum datagram size
     *
     * @return string
     */
    public function getT38UdptlMaxdatagram()
    {
        return $this->getKey('T38UdptlMaxdatagram');
    }

    /**
     * Returns key: 'FaxDetect'. Whether CNG tone detection is enabled
     *
     * @return string
     */
    public function getFaxDetect()
    {
        return $this->getKey('FaxDetect');
    }

    /**
     * Returns key: 'T38UdptlNat'. Whether NAT support is enabled on UDPTL sessions
     *
     * @return string
     */
    public function getT38UdptlNat()
    {
        return $this->getKey('T38UdptlNat');
    }

    /**
     * Returns key: 'T38UdptlIpv6'. Whether IPv6 is used for UDPTL Sessions
     *
     * @return string
     */
    public function getT38UdptlIpv6()
    {
        return $this->getKey('T38UdptlIpv6');
    }

    /**
     * Returns key: 'ToneZone'. Set which country's indications to use for channels created for this endpoint.
     *
     * @return string
     */
    public function getToneZone()
    {
        return $this->getKey('ToneZone');
    }

    /**
     * Returns key: 'Language'. Set the default language to use for channels created for this endpoint.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getKey('Language');
    }

    /**
     * Returns key: 'RecordOnFeature'. The feature to enact when one-touch recording is turned on.
     *
     * @return string
     */
    public function getRecordOnFeature()
    {
        return $this->getKey('RecordOnFeature');
    }

    /**
     * Returns key: 'RecordOffFeature'. The feature to enact when one-touch recording is turned off.
     *
     * @return string
     */
    public function getRecordOffFeature()
    {
        return $this->getKey('RecordOffFeature');
    }

    /**
     * Returns key: 'AllowTransfer'. Determines whether SIP REFER transfers are allowed for this endpoint
     *
     * @return string
     */
    public function getAllowTransfer()
    {
        return $this->getKey('AllowTransfer');
    }

    /**
     * Returns key: 'UserEqPhone'. Determines whether a user=phone parameter is placed into the request URI if the user is determined to be a phone number
     *
     * @return string
     */
    public function getUserEqPhone()
    {
        return $this->getKey('UserEqPhone');
    }

    /**
     * Returns key: 'SdpOwner'. String placed as the username portion of an SDP origin (o=) line.
     *
     * @return string
     */
    public function getSdpOwner()
    {
        return $this->getKey('SdpOwner');
    }

    /**
     * Returns key: 'SdpSession'. String used for the SDP session (s=) line.
     *
     * @return string
     */
    public function getSdpSession()
    {
        return $this->getKey('SdpSession');
    }

    /**
     * Returns key: 'TosAudio'. DSCP TOS bits for audio streams
     *
     * @return string
     */
    public function getTosAudio()
    {
        return $this->getKey('TosAudio');
    }

    /**
     * Returns key: 'TosVideo'. DSCP TOS bits for video streams
     *
     * @return string
     */
    public function getTosVideo()
    {
        return $this->getKey('TosVideo');
    }

    /**
     * Returns key: 'CosAudio'. Priority for audio streams
     *
     * @return string
     */
    public function getCosAudio()
    {
        return $this->getKey('CosAudio');
    }

    /**
     * Returns key: 'CosVideo'. Priority for video streams
     *
     * @return string
     */
    public function getCosVideo()
    {
        return $this->getKey('CosVideo');
    }

    /**
     * Returns key: 'AllowSubscribe'. Determines if endpoint is allowed to initiate subscriptions with Asterisk.
     *
     * @return string
     */
    public function getAllowSubscribe()
    {
        return $this->getKey('AllowSubscribe');
    }

    /**
     * Returns key: 'SubMinExpiry'. The minimum allowed expiry time for subscriptions initiated by the endpoint.
     *
     * @return string
     */
    public function getSubMinExpiry()
    {
        return $this->getKey('SubMinExpiry');
    }

    /**
     * Returns key: 'FromUser'. Username to use in From header for requests to this endpoint.
     *
     * @return string
     */
    public function getFromUser()
    {
        return $this->getKey('FromUser');
    }

    /**
     * Returns key: 'FromDomain'. Domain to user in From header for requests to this endpoint.
     *
     * @return string
     */
    public function getFromDomain()
    {
        return $this->getKey('FromDomain');
    }

    /**
     * Returns key: 'MwiFromUser'. Username to use in From header for unsolicited MWI NOTIFYs to this endpoint.
     *
     * @return string
     */
    public function getMwiFromUser()
    {
        return $this->getKey('MwiFromUser');
    }

    /**
     * Returns key: 'RtpEngine'. Name of the RTP engine to use for channels created for this endpoint
     *
     * @return string
     */
    public function getRtpEngine()
    {
        return $this->getKey('RtpEngine');
    }

    /**
     * Returns key: 'DtlsVerify'. Verify that the provided peer certificate is valid
     *
     * @return string
     */
    public function getDtlsVerify()
    {
        return $this->getKey('DtlsVerify');
    }

    /**
     * Returns key: 'DtlsRekey'. Interval at which to renegotiate the TLS session and rekey the SRTP session
     *
     * @return string
     */
    public function getDtlsRekey()
    {
        return $this->getKey('DtlsRekey');
    }

    /**
     * Returns key: 'DtlsCertFile'. Path to certificate file to present to peer
     *
     * @return string
     */
    public function getDtlsCertFile()
    {
        return $this->getKey('DtlsCertFile');
    }

    /**
     * Returns key: 'DtlsPrivateKey'. Path to private key for certificate file
     *
     * @return string
     */
    public function getDtlsPrivateKey()
    {
        return $this->getKey('DtlsPrivateKey');
    }

    /**
     * Returns key: 'DtlsCipher'. Cipher to use for DTLS negotiation
     *
     * @return string
     */
    public function getDtlsCipher()
    {
        return $this->getKey('DtlsCipher');
    }

    /**
     * Returns key: 'DtlsCaFile'. Path to certificate authority certificate
     *
     * @return string
     */
    public function getDtlsCaFile()
    {
        return $this->getKey('DtlsCaFile');
    }

    /**
     * Returns key: 'DtlsCaPath'. Path to a directory containing certificate authority certificates
     *
     * @return string
     */
    public function getDtlsCaPath()
    {
        return $this->getKey('DtlsCaPath');
    }

    /**
     * Returns key: 'DtlsSetup'. Whether we are willing to accept connections, connect to the other party, or both.
     *
     * @return string
     */
    public function getDtlsSetup()
    {
        return $this->getKey('DtlsSetup');
    }

    /**
     * Returns key: 'SrtpTag32'. Determines whether 32 byte tags should be used instead of 80 byte tags.
     *
     * @return string
     */
    public function getSrtpTag32()
    {
        return $this->getKey('SrtpTag32');
    }

    /**
     * Returns key: 'RedirectMethod'. How redirects received from an endpoint are handled
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return $this->getKey('RedirectMethod');
    }

    /**
     * Returns key: 'SetVar'. Variable set on a channel involving the endpoint.
     *
     * @return string
     */
    public function getSetVar()
    {
        return $this->getKey('SetVar');
    }

    /**
     * Returns key: 'MessageContext'. Context to route incoming MESSAGE requests to.
     *
     * @return string
     */
    public function getMessageContext()
    {
        return $this->getKey('MessageContext');
    }

    /**
     * Returns key: 'Accountcode'. An accountcode to set automatically on any channels created for this endpoint.
     *
     * @return string
     */
    public function getAccountcode()
    {
        return $this->getKey('Accountcode');
    }

    /**
     * Returns key: 'DeviceState'. The aggregate device state for this endpoint.
     *
     * @return string
     */
    public function getDeviceState()
    {
        return $this->getKey('DeviceState');
    }

    /**
     * Returns key: 'ActiveChannels'. The number of active channels associated with this endpoint.
     *
     * @return string
     */
    public function getActiveChannels()
    {
        return $this->getKey('ActiveChannels');
    }
}
