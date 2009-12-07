<?php
require_once 'config.local.php';

set_include_path(PATH_INCLUDE);
/*
require_once 'VIH/errorhandler.php';
set_error_handler('vih_error_handler');
*/
require_once 'Ilib/ClassLoader.php';
require_once 'VIH/functions.php';
require_once 'VIH/configuration.php';
require_once('Doctrine/lib/Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));

require_once 'konstrukt/konstrukt.inc.php';
require_once 'lib/bucket.inc.php';

class k_SessionIdentityLoader implements k_IdentityLoader {
  function load(k_Context $context) {
    if ($context->session('identity')) {
      return $context->session('identity');
    }
    return new k_Anonymous();
  }
}

class NotAuthorizedComponent extends k_Component {
  function dispatch() {
    // redirect to login-page
    return new k_TemporaryRedirect($this->url('/login', array('continue' => $this->requestUri())));
  }
}

class Login extends k_Component {
  function execute() {
    $this->url_state->init("continue", $this->url('/'));
    return parent::execute();
  }
  function renderHtml() {
    $response = new k_HtmlResponse(
      "<html><head><title>Authentication required</title></head><body><form method='post' action='" . htmlspecialchars($this->url()) . "'>
  <h1>Authentication required</h1>
  <p>
    <label>
      username:
      <input type='text' name='username' />
    </label>
  </p>
  <p>
    <label>
      password:
      <input type='password' name='password' />
    </label>
  </p>
  <p>
    <input type='submit' value='Login' />
  </p>
</form></body></html>");
    $response->setStatus(401);
    return $response;
  }
  function postForm() {
    $user = $this->selectUser($this->body('username'), $this->body('password'));
    if ($user) {
      $this->session()->set('identity', $user);
      return new k_SeeOther($this->query('continue'));
    }
    return $this->render();
  }
  protected function selectUser($username, $password) {
    $users = array(
      'vih' => 'vih'
    );
    if (isset($users[$username]) && $users[$username] == $password) {
      return new k_AuthenticatedUser($username);
    }
  }
}

class Logout extends k_Component {
  function execute() {
    $this->url_state->init("continue", $this->url('/'));
    return parent::execute();
  }
  function postForm() {
    $this->session()->set('identity', null);
    return new k_SeeOther($this->query('continue'));
  }
}

class Root extends k_Component {
    private $template;
  function __construct(k_TemplateFactory $template)
  {
      $this->template = $template;
  }

  protected function map($name) {
    switch ($name) {
    case 'restricted':
      return 'VIH_Intranet_Controller_Index';
    case 'login':
      return 'Login';
    case 'logout':
      return 'Logout';
    }
  }
    function document()
    {
        return $this->document;
    }

  function execute() {
    return $this->wrap(parent::execute());
  }
  function wrapHtml($content) {
        $this->document->navigation = array(
            $this->url('/restricted/nyheder') => 'Nyheder',
            $this->url('/restricted/langekurser/tilmeldinger')  => 'Lange kurser',
            $this->url('/restricted/kortekurser/tilmeldinger')  => 'Korte kurser',
            $this->url('/restricted/betaling') => 'Betalinger',
            $this->url('/restricted/materialebestilling')  => 'Brochurebestilling',
            $this->url('/restricted/ansatte')  => 'Ansatte',
            $this->url('/restricted/faciliteter')  => 'Faciliteter',
            $this->url('/restricted/filemanager') => 'Dokumenter',
            $this->url('/restricted/fotogalleri')  => 'Højdepunkter',
            $this->url('/restricted/logout')  => 'Logout');

      $tpl = $this->template->create('main');
      return $tpl->render($this, array('content' => $content));
  }
  function renderHtml() {
      return new k_SeeOther($this->url('restricted'));
  }
}

$factory = new VIH_Intranet_Factory();
$container = new bucket_Container($factory);

class VIH_Document extends k_Document
{
    public $options;
    public $navigation;
    public $help;

    function navigation()
    {
        return $this->navigation;
    }

    function options()
    {
        if (empty($this->options)) return array();
        return $this->options;
    }

    function help()
    {
        return $this->help;
    }
}

if (realpath($_SERVER['SCRIPT_FILENAME']) == __FILE__) {
  $components = new k_InjectorAdapter($container, new VIH_Document);
  $components->setImplementation('k_DefaultNotAuthorizedComponent', 'NotAuthorizedComponent');
  $identity_loader = new k_SessionIdentityLoader();
  k()
    ->setComponentCreator($components)
    ->setIdentityLoader($identity_loader)
    ->run('Root')
    ->out();
}

/*
$application = new VIH_Intranet_Controller_Root();

$application->registry->registerConstructor('database', create_function(
  '$className, $args, $registry',
  'return new pdoext_Connection("mysql:dbname=".DB_NAME.";host=" . DB_HOST, DB_USER, DB_PASSWORD);'
));

$application->registry->registerConstructor('database:db_sql', create_function(
  '$className, $args, $registry',
  'return new DB_Sql();'
));

$application->registry->registerConstructor('database:pear', create_function(
  '$className, $args, $registry',
  '$db_options= array("debug"       => 2);
   $db = DB::connect(DB_DSN, $db_options);
   if (PEAR::isError($db)) {
        die($db->getMessage());
   }
   $db->setFetchMode(DB_FETCHMODE_ASSOC);
   $db->query("SET time_zone=\"-01:00\"");
   return $db;
'
));

$application->registry->registerConstructor('database:mdb2', create_function(
  '$className, $args, $registry',
  '$options= array("debug" => 0);
   $db = MDB2::factory(DB_DSN, $options);
   if (PEAR::isError($db)) {
        die($db->getMessage());
   }
   $db->setOption("portability", MDB2_PORTABILITY_NONE);
   $db->setFetchMode(MDB2_FETCHMODE_ASSOC);
   $db->exec("SET time_zone=\"-01:00\"");
   return $db;
'
));

$application->registry->registerConstructor('intraface:kernel', create_function(
  '$className, $args, $registry',
  '$kernel = new VIH_Intraface_Kernel;
   $kernel->setting = new VIH_Intraface_Setting;
   $kernel->intranet = new VIH_Intraface_Intranet;
   $kernel->user = new VIH_Intraface_User;
   return $kernel;'
));

$application->registry->registerConstructor('table:langtkursus_periode', create_function(
  '$className, $args, $registry',
  'return new pdoext_TableGateway("langtkursus_fag_periode", $registry->get("database"));'
));

$application->registry->registerConstructor('liveuser', create_function(
  '$className, $args, $registry',
  'return new VIH_Intranet_User;'
));

$application->registry->registerConstructor('doctrine', create_function(
  '$className, $args, $registry',
  'return Doctrine_Manager::connection(DB_DSN);'
));

$application->registry->registerConstructor('template', create_function(
  '$className, $args, $registry',
  'require_once "Template/Template.php";
   return new Template(PATH_INCLUDE . "/VIH/Intranet/view/");
  '
));

$application->registry->registerConstructor('intraface:filehandler:gateway', create_function(
  '$className, $args, $registry',
  'return new Ilib_Filehandler_Gateway($registry->get("intraface:kernel"));'
));


$application->dispatch();
*/