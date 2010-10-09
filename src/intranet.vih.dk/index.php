<?php
require_once 'config.local.php';
require_once 'Ilib/ClassLoader.php';
require_once 'VIH/functions.php';
require_once 'VIH/configuration.php';
require_once 'konstrukt/konstrukt.inc.php';
require_once 'bucket.inc.php';

require_once('Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));

class k_SessionIdentityLoader implements k_IdentityLoader
{
    function load(k_Context $context)
    {
        if ($context->session('identity')) {
            return $context->session('identity');
        }
        return new k_Anonymous();
    }
}

class NotAuthorizedComponent extends k_Component
{
    function dispatch()
    {
        return new k_TemporaryRedirect($this->url('/login', array('continue' => $this->requestUri())));
    }
}

$factory = new VIH_Intranet_Factory();
$container = new bucket_Container($factory);
$db = $container->get('db_common');
$db = $container->get('mdb2_driver_common');

if (realpath($_SERVER['SCRIPT_FILENAME']) == __FILE__) {
    $components = new k_InjectorAdapter($container, new VIH_Intranet_Document);
    $components->setImplementation('k_DefaultNotAuthorizedComponent', 'NotAuthorizedComponent');
    $identity_loader = new k_SessionIdentityLoader();
    k()
    ->setComponentCreator($components)
    ->setIdentityLoader($identity_loader)
    ->run('VIH_Intranet_Controller_Root')
    ->out();
}