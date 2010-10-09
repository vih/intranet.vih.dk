<?php
require_once 'config.local.php';
require_once 'Ilib/ClassLoader.php';
require_once 'VIH/functions.php';
require_once 'VIH/configuration.php';
require_once 'konstrukt/konstrukt.inc.php';
require_once 'bucket.inc.php';

require_once('Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));

$GLOBALS['konstrukt_content_types']['application/ms-excel'] = 'xls';
$GLOBALS['konstrukt_content_types']['text/x-vcard'] = 'vcf';
$GLOBALS['konstrukt_content_types']['text/plain'] = 'txt';
$GLOBALS['konstrukt_content_types']['xml/oioxml'] = 'oioxml';

class k_PdfResponse extends k_ComplexResponse
{
    function contentType()
    {
        return 'application/pdf';
    }

    protected function marshal()
    {
        return $this->content;
    }
}

class k_XlsResponse extends k_ComplexResponse
{
    function contentType()
    {
        return 'application/excel';
    }

    protected function marshal()
    {
        return $this->content;
    }
}

class k_TxtResponse extends k_ComplexResponse
{
    function contentType()
    {
        return 'text/plain';
    }

    protected function marshal()
    {
        return $this->content;
    }
}


class k_VcfResponse extends k_ComplexResponse
{
    function contentType()
    {
        return 'text/x-vcard';
    }

    protected function marshal()
    {
        return $this->content;
    }
}

class k_OioxmlResponse extends k_ComplexResponse
{
    function contentType()
    {
        return 'xml/oioxml';
    }

    protected function marshal()
    {
        return $this->content;
    }
}

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