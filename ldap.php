<?php
// Inclusion of the Net_LDAP package:
require_once 'Net/LDAP2.php';

// The configuration array:
$config = array (
    'binddn'    => 'cn=admin,ou=users,dc=example,dc=org',
    'bindpw'    => 'password',
    'basedn'    => 'dc=example,dc=org',
    'host'      => 'mail.vih.dk'
);

// Connecting using the configuration:
$ldap = Net_LDAP2::connect($config);

// Testing for connection error
if (PEAR::isError($ldap)) {
    die('Could not connect to LDAP-server: '.$ldap->getMessage());
}

echo 'connected to ldap';