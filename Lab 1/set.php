<?php

require 'Predis/Predis/Autoload.php';

use Predis\Client;

$redis = new Client();

$redis->sadd('employee', 'Smith');
$redis->sadd('employee', 'Paul');
$redis->sadd('employee', 'George');
$redis->sadd('employee', 'Timothy');
$redis->sadd('employee', 'Michael');
$redis->sadd('employee', 'Alan');

print_r($redis->smembers('employee'));

$redis->sadd('myset', 'Smith');
$redis->sadd('myset', 'Paul');
$redis->sadd('myset', 'George');
$redis->sadd('myset', 'George');
$redis->sadd('myset', 'George');
$redis->sadd('myset', 'Christopher');

print_r($redis->smembers('myset'));

echo $redis->sismember('myset', 'George')."\n";
echo $redis->sismember('myset', 'Alan')."\n";

echo $redis->srem('myset', 'Paul');
echo $redis->srem('myset', 'Alan');
print_r($redis->smembers('myset'));

print_r($redis->sdiff('myset', 'employee'));
print_r($redis->sinter('myset', 'employee'));
print_r($redis->sunion('myset', 'employee'));

$redis->del('myset');
$redis->del('employee');

?>