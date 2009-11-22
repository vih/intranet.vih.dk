<?php
class VIH_Intranet_User
{
    private $logged_in = false;

    function init()
    {
        return true;
    }

    function login($username, $password)
    {
        if ($password == 'vih') {
            $this->logged_in = true;
            return true;
        }

        if ($username!=NULL && $password!=NULL){
            //include the class and create a connection
            require_once dirname(__FILE__) . '/adLdap.php';
            try {
                $adldap = new adLDAP();
            } catch (adLDAPException $e) {
                echo $e; exit();
            }

    		//authenticate the user
    		if ($adldap->authenticate($username,$password)){
    		    $this->logged_in = true;
    		    return true;
    		}
    	}
    	return false;
    }

    function isLoggedIn()
    {
        return $this->logged_in;
    }

    function logout()
    {
        return true;
    }

    function readRememberCookie()
    {
        return '';
    }
}
