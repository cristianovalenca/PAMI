[![Tests](https://github.com/cristianovalenca/PAMI/actions/workflows/tests.yml/badge.svg)](https://github.com/cristianovalenca/PAMI/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/cristianovalenca/pami/v/stable)](https://packagist.org/packages/cristianovalenca/pami)
[![Total Downloads](https://poser.pugx.org/cristianovalenca/pami/downloads)](https://packagist.org/packages/cristianovalenca/pami)
[![PHP Version Require](https://poser.pugx.org/cristianovalenca/pami/require/php)](https://packagist.org/packages/cristianovalenca/pami)
[![License](https://poser.pugx.org/cristianovalenca/pami/license)](https://packagist.org/packages/cristianovalenca/pami)

# PAMI — PHP Asterisk Manager Interface

PAMI is a set of PHP classes that let you issue commands to an Asterisk Manager
Interface (AMI) and/or receive its events, using an observer/listener pattern.
It is useful for building operator consoles, call monitors, dashboards and other
telephony integrations.

> **Fork.** This is a PHP 8.x–maintained fork of the original
> [`marcelog/PAMI`](https://github.com/marcelog/PAMI) by
> [Marcelo Gornstein](https://github.com/marcelog). It keeps the same API while
> fixing PHP 8 compatibility, adding new actions/events and a full test suite.

## Requirements

- PHP **7.1+** (developed and tested against **8.1 – 8.4**)
- [`psr/log`](https://packagist.org/packages/psr/log) `^1 || ^2 || ^3`

## Installing

Install via [Composer](https://getcomposer.org/):

```bash
composer require cristianovalenca/pami
```

Or add it to your `composer.json`:

```json
{
  "require": {
    "cristianovalenca/pami": "^2.0"
  }
}
```

## Quick start

```php
require __DIR__ . '/vendor/autoload.php';

use PAMI\Client\Impl\ClientImpl as PamiClient;

$options = array(
    'host'            => '127.0.0.1',
    'scheme'          => 'tcp://',
    'port'            => 5038,
    'username'        => 'admin',
    'secret'          => 'secret',
    'connect_timeout' => 10,
    'read_timeout'    => 10,
);

$client = new PamiClient($options);
$client->open();

// Register a listener (a Closure, an [object, method] pair, or an IEventListener):
$client->registerEventListener(function ($event) {
    echo get_class($event), PHP_EOL;
});

// Send an action and read the response.
use PAMI\Message\Action\PingAction;
$response = $client->send(new PingAction());

// Keep processing events (e.g. inside your own loop):
$client->process();

$client->close();
```

### Filtering with predicates

`registerEventListener()` accepts an optional second argument: a predicate that is
evaluated before the callback runs. The callback fires only when it returns `true`:

```php
use PAMI\Message\Event\DialEvent;

$client->registerEventListener(
    array($listener, 'handleDialStart'),
    function ($event) {
        return $event instanceof DialEvent && $event->getSubEvent() === 'Begin';
    }
);
```

### Logging

The client accepts any [PSR-3](https://www.php-fig.org/psr/psr-3/) logger and
defaults to a `NullLogger`:

```php
$client->setLogger($logger);
```

## Supported actions

120 actions are available under `PAMI\Message\Action` (append `Action`, e.g.
`OriginateAction`).

```
AbsoluteTimeout, AGI, AgentLogoff, Agents, AttendedTransfer, BlindTransfer, Bridge,
BridgeInfo, Challenge, ChangeMonitor, Command, ConfbridgeList, ConfbridgeMute,
ConfbridgeUnmute, CoreSettings, CoreShowChannel, CoreShowChannels, CoreStatus,
CreateConfig, DAHDIDialOffHook, DAHDIDNDOff, DAHDIDNDOn, DAHDIHangup, DAHDIRestart,
DAHDIShowChannels, DAHDITransfer, DBDel, DBDelTree, DBGet, DBPut, DongleReload,
DongleReset, DongleRestart, DongleSendPDU, DongleSendSMS, DongleSendUSSD,
DongleShowDevices, DongleStart, DongleStop, Events, ExtensionState, GetConfig,
GetConfigJSON, GetVar, Hangup, IAXnetstats, IAXPeerList, IAXpeers, IAXregistry,
JabberSend, ListCategories, ListCommands, LocalOptimizeAway, Login, Logoff,
MailboxCount, MailboxStatus, MeetmeList, MeetmeMute, MeetmeUnmute, MixMonitor,
MixMonitorMute, ModuleCheck, ModuleLoad, ModuleReload, ModuleUnload, Monitor,
Originate, Park, ParkedCalls, PauseMonitor, Ping, PJSIPNotify, PJSIPQualify,
PJSIPRegister, PJSIPShowAors, PJSIPShowAuths, PJSIPShowContacts, PJSIPShowEndpoint,
PJSIPShowEndpoints, PJSIPShowRegistrationInboundContactStatuses,
PJSIPShowRegistrationsInbound, PJSIPShowRegistrationsOutbound, PJSIPShowResourceLists,
PJSIPShowSubscriptionsInbound, PJSIPShowSubscriptionsOutbound, PJSIPUnregister,
PlayDTMF, QueueAdd, QueueLog, QueuePause, QueuePenalty, QueueReload, QueueRemove,
QueueReset, QueueRule, Queues, QueueStatus, QueueSummary, QueueUnpause, Redirect,
Reload, SendText, SetVar, ShowDialPlan, SIPNotify, SIPPeers, SIPQualifyPeer,
SIPShowPeer, SIPShowPeerStatus, SIPShowRegistry, Status, StopMixMonitor, StopMonitor,
UnpauseMonitor, UpdateConfig, UserEvent, VGSMSMSTx, VoicemailUsersList, WaitEvent
```

## Supported events

133 events are available under `PAMI\Message\Event` (append `Event`, e.g.
`HangupEvent`). Any event not implemented is delivered as an `UnknownEvent`, so you
can still catch it — please report those you find.

```
AgentCalled, AgentComplete, AgentConnect, AgentDump, Agentlogin, Agentlogoff,
AgentRingNoAnswer, Agents, AgentsComplete, AGIExec, AGIExecEnd, AGIExecStart,
AsyncAGI, AsyncAGIEnd, AsyncAGIExec, AsyncAGIStart, AttendedTransfer, BlindTransfer,
Bridge, BridgeCreate, BridgeDestroy, BridgeEnter, BridgeInfoChannel,
BridgeInfoComplete, BridgeLeave, CallAnswered, CallForward, Cdr, CEL, ChannelUpdate,
ConfbridgeEnd, ConfbridgeJoin, ConfbridgeLeave, ConfbridgeList, ConfbridgeListComplete,
ConfbridgeMute, ConfbridgeStart, ConfbridgeTalking, ConfbridgeUnmute, CoreShowChannel,
CoreShowChannelsComplete, DAHDIShowChannels, DAHDIShowChannelsComplete, DBGetResponse,
DeviceStateChange, DeviceStateListComplete, DeviceStatus, Dial, DialBegin, DialEnd,
DNDState, DongleDeviceEntry, DongleNewCUSD, DongleNewUSSD, DongleNewUSSDBase64,
DongleShowDevicesComplete, DongleSMSStatus, DongleStatus, DongleUSSDStatus, DTMF,
DTMFBegin, DTMFEnd, EndpointDetail, EndpointDetailComplete, EndpointList,
EndpointListComplete, ExtensionStatus, FullyBooted, Hangup, Hold, JabberEvent, Join,
Leave, Link, ListDialplan, Masquerade, MessageWaiting, MusicOnHold, MusicOnHoldStart,
MusicOnHoldStop, NewAccountCode, NewCallerid, Newchannel, Newexten, Newstate,
OriginateResponse, ParkedCall, ParkedCallGiveUp, ParkedCallsComplete, ParkedCallTimeOut,
PeerEntry, PeerlistComplete, PeerStatus, Pickup, QueueCallerAbandon, QueueCallerJoin,
QueueCallerLeave, QueueEntry, QueueMember, QueueMemberAdded, QueueMemberPause,
QueueMemberPaused, QueueMemberPenalty, QueueMemberRemoved, QueueMemberRinginuse,
QueueMemberStatus, QueueParams, QueueStatusComplete, QueueSummary, QueueSummaryComplete,
RegistrationsComplete, Registry, Rename, RTCPReceived, RTCPReceiverStat, RTCPSent,
RTPReceiverStat, RTPSenderStat, ShowDialPlanComplete, Shutdown, Status, StatusComplete,
Transfer, Unlink, UnParkedCall, UserEvent, VarSet, VgsmMeState, VgsmNetState, VgsmSmsRx,
VoicemailUserEntry, VoicemailUserEntryComplete
```

## Development

```bash
composer install       # install dev dependencies
composer test          # run the PHPUnit suite
composer test-coverage # coverage report (requires pcov/xdebug)
composer cs            # PSR-12 code style check (PHP_CodeSniffer)
```

Tests run on PHP 8.1–8.4 in [CI](.github/workflows/tests.yml) on every push and
pull request, including a data-driven pass that guards against PHP 8 deprecations
across every action and event.

## Contributing

Contributions are welcome — please read [CONTRIBUTING.md](CONTRIBUTING.md). For
security issues, see [SECURITY.md](SECURITY.md). Notable changes are recorded in
[CHANGELOG.md](CHANGELOG.md).

## License

Apache License 2.0. This fork preserves the original license of
[`marcelog/PAMI`](https://github.com/marcelog/PAMI).

Copyright 2016 Marcelo Gornstein &lt;marcelog@gmail.com&gt;. Fork maintained by
Cristiano Valença.

Licensed under the Apache License, Version 2.0 (the "License"); you may not use
this file except in compliance with the License. You may obtain a copy of the
License at http://www.apache.org/licenses/LICENSE-2.0. Unless required by applicable
law or agreed to in writing, software distributed under the License is distributed
on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
implied. See [LICENSE](LICENSE) for the full text.

## Credits

Original library and its contributors by Marcelo Gornstein and the PAMI community —
see the [upstream repository](https://github.com/marcelog/PAMI) for the full list of
acknowledgements.
