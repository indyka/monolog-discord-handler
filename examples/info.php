<?php
require '../vendor/autoload.php';

$log = new Monolog\Logger('name');
// $log->pushHandler(new DiscordHandler\DiscordHandler(['https://discordapp.com/api/webhooks/xxx/yyy'], 'DEBUG'));
$log->pushHandler(new DiscordHandler\DiscordHandler(['https://discordapp.com/api/webhooks/383319402801528842/iLK5KXFLAIHMbEvlwYjTnbVMzSBkeg61auJNyUS55OFX0eCuislaFWozpTUFylItCwJX'], 'DEBUG'));

$log->debug('test');
sleep(1);

$log->info('test');
sleep(1);

$log->notice('test');
sleep(1);

$log->warning('test');
sleep(1);

$log->error('test');
sleep(1);

$log->critical('test');
sleep(1);

$log->alert('test');
sleep(1);

$log->emergency('test');
