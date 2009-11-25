<?php
class VIH_Intranet_Controller_Logout extends k_Component
{
    function renderHtml()
    {
        if ($this->registry->identity->init()) {
            if ($this->registry->identity->logout()) {
                $this->SESSION->destroy();
                $this->ENV['PHP_AUTH_USER'] = null;
                $this->ENV['PHP_AUTH_PW'] = null;
                unset($this->ENV['PHP_AUTH_PW']);
                unset($this->ENV['PHP_AUTH_USER']);
                $_COOKIE['PHP_AUTH_USER'] = null;
                $_COOKIE['PHP_AUTH_PW'] = null;
                unset($_COOKIE['PHP_AUTH_PW']);
                unset($_COOKIE['PHP_AUTH_USER']);
                throw new k_SeeOther($this->url('/'));
            }
        } else {
            $this->SESSION->destroy();
            throw Exception('could not log out');
        }
    }
}