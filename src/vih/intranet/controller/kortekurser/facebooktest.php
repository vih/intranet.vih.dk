<?php
set_include_path('/home/vih/pear/php/');

include_once 'facebook.php';
$api_key = 'c71ea516b1bd47632d87509054d0d27f';
$secret = 'dee8d9c85826710aae593ec0358a84e7';

$facebook = new Facebook($api_key, $secret);
$user = $facebook->require_login();
$fapi = $facebook->api_client;

//Setup array with paramets you would like to pass into events.create
$event_info = array();
$event_info['name'] = 'Party';
$event_info['category'] = 1;
$event_info['subcategory'] = 1;
$event_info['host'] = 'You';
$event_info['location'] = 'Your House';
$event_info['city'] = 'Toronto'; //Must be a valid city name
$event_info['start_time'] = gmmktime(22,0,0,9,3,2008); //Converts time to UTC
$event_info['end_time'] = gmmktime(5,0,0,9,5,2008); //COnverts time to UTC

//Call events_create
//Display events id on event creation
//Display error message with error code on error
try
{
 $event_id = $fapi->events_create($event_info);
 echo 'Event Created! Event Id is: '.$event_id;
}
catch(Exception $e)
{
 echo 'Error message: '.$e->getMessage().' Error code:'.$e->getCode();
}