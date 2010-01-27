<?php
class VIH_Intranet_Controller_Logout extends k_Component
{

    function execute() {
        $this->url_state->init("continue", $this->url('/'));
        return parent::execute();
    }

    function renderHtml()
    {
        return $this->postForm();
    }

    function postForm()
    {
        $this->session()->set('identity', null);
        return new k_SeeOther($this->query('continue'));
    }
}