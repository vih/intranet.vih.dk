<?php
class VIH_Intranet_Controller_Login extends k_Component
{
    function execute()
    {
        $this->url_state->init("continue", $this->url('/restricted'));
        return parent::execute();
    }

    function renderHtml()
    {
        $this->document->setTitle('Login');
        $response = new k_HtmlResponse(
      "<form method='post' action='" . htmlspecialchars($this->url()) . "'>
  <p>
    <label>
      Brugernavn
      <input type='text' name='username' />
    </label>
  </p>
  <p>
    <label>
      Adgangskode
      <input type='password' name='password' />
    </label>
  </p>
  <p>
    <input type='submit' value='Login' />
  </p>
</form>
");
        $response->setStatus(401);
        return $response;
    }

    function postForm()
    {
        $user = $this->selectUser($this->body('username'), $this->body('password'));
        if ($user) {
            $this->session()->set('identity', $user);
            return new k_SeeOther($this->query('continue'));
        }
        return $this->render();
    }

    function selectUserFromLdap($username, $password)
    {
        try {
            $adldap = new adLDAP();
            $adldap->set_account_suffix('@vejleidraetsefterskole.local');
            $adldap->set_domain_controllers(array('mail.vih.dk'));
         } catch (adLDAPException $e) {
            echo $e;
            exit();
         }
         $authUser = $adldap->authenticate($username, $password);
         if ($authUser === true) {
             return new k_AuthenticatedUser($username);
         } else {
             throw new Exception('User authentication unsuccessful. ' . $adldap->get_last_error());
        }
    }

    protected function selectUser($username, $password)
    {
        $users = $GLOBALS['users'];

        if (isset($users[$username]) && $users[$username] == $password) {
            return new k_AuthenticatedUser($username);
        }
    }
}

/*
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
*/