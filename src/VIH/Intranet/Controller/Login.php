<?php
class VIH_Intranet_Controller_Login extends k_Component
{
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $form = new HTML_QuickForm('login', 'POST', $this->url());
        $form->addElement('text', 'handle', 'Brugernavn');
        $form->addElement('password', 'passwrd', 'Adgangskode');
        //$form->addElement('checkbox', 'remember', '', 'Husk mig');
        $form->addElement('submit', null, 'Login');
        return ($this->form = $form);
    }

    function renderHtml()
    {
        $usr = $this->registry->get('liveuser');

        if ($remember = $usr->readRememberCookie()) {
            $this->getForm()->setDefaults(array(
                'handle' => $remember['handle'],
                'passwrd' => $remember['passwd'],
                'remember' => 1
            ));
        }

        if ($usr->isLoggedIn()) {
            throw new k_SeeOther($this->url('../'));
        }

        $this->document->setTitle('Login');
        throw new k_http_Response(200, $this->render('VIH/Intranet/view/login-tpl.php', array('content_main' => $this->getForm()->toHTML())));
    }

    function postForm()
    {
        $usr = $this->registry->get('liveuser');

        if ($this->getForm()->validate()) {

            $session = & $this->SESSION->get('vih');
            $session['logged_in'] = true;
            $usr->login($this->POST['handle'], $this->POST['passwrd']);

            if ($usr->isLoggedIn()) {
                throw new k_SeeOther($this->context->url());
            }
        }
        throw new k_SeeOther($this->url(''));
    }
}
