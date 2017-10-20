<?php


$user = array();

$user['id'] = 1;
$user['username'] = 'charkey';
$user['first_name'] = 'Chris';
$user['last_name'] = 'Harkey';
$user['email'] = 'charkey@example.com';
$user['password'] = 'fakepassword';

$album1 = array();
$album1['id'] = 1;
$album1['menu_name'] = 'Disneyland 2017';
$album1['visible'] = '1';

$album2 = array();
$album2['id'] = 2;
$album2['menu_name'] = 'Camping, Summer 2016';
$album2['visible'] = '1';

$users = [$user];
$albums = ['1' => $album1, '2' => $album2];
