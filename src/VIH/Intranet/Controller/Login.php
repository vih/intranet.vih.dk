<?php
class VIH_Intranet_Controller_Login extends k_Component
{
    private $form;
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $form = new HTML_QuickForm('login', 'POST', $this->url());
        $form->addElement('text', 'username', 'Brugernavn');
        $form->addElement('password', 'password', 'Adgangskode');
        //$form->addElement('checkbox', 'remember', '', 'Husk mig');
        $form->addElement('submit', null, 'Login');
        return ($this->form = $form);
    }

    function execute()
    {
        $this->url_state->init("continue", $this->url('/'));
        return parent::execute();
    }

    function renderHtml()
    {
        $this->document->setTitle('Login');
        $tpl = $this->template->create('login');
        return new k_HttpResponse(200, $tpl->render($this, array('content_main' => $this->getForm()->toHTML())));
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {

            $user = $this->selectUser($this->body('username'), $this->body('password'));
            if ($user) {
                $this->session()->set('identity', $user);
                return new k_SeeOther($this->query('continue'));
            }
        }
        return $this->render();
    }

    protected function selectUser($username, $password)
    {
        $users = array(
      		'vih' => 'vih'
        );
        if (isset($users[$username]) && $users[$username] == $password) {
          return new k_AuthenticatedUser($username);
        }
    }
}
