<?php
require_once 'config.local.php';
require_once 'Ilib/ClassLoader.php';
require_once 'VIH/functions.php';
require_once 'VIH/configuration.php';
require_once 'konstrukt/konstrukt.inc.php';
require_once 'bucket.inc.php';
require_once 'Intraface/shared/keyword/Keyword.php';

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

class EnglishLanguage implements k_Language {
    function name() {
        return 'English';
    }
    function isoCode() {
        return 'en';
    }
}

class SwedishLanguage implements k_Language {
    function name() {
        return 'Swedish';
    }
    function isoCode() {
        return 'sv';
    }
}

class MyLanguageLoader implements k_LanguageLoader {
    function load(k_Context $context) {
        if($context->query('lang') == 'sv') {
            return new SwedishLanguage();
        } else if($context->query('lang') == 'en') {
            return new EnglishLanguage();
        }
        return new EnglishLanguage();
    }
}

class SimpleTranslator implements k_Translator {
    protected $phrases;
    function __construct($phrases = array()) {
        $this->phrases = $phrases;
    }
    function translate($phrase, k_Language $language = null) {
        return isset($this->phrases[$phrase]) ? $this->phrases[$phrase] : $phrase;
    }
}

class SimpleTranslatorLoader implements k_TranslatorLoader {
    function load(k_Context $context) {
        // Default to English
        $phrases = array(
      'Hello' => 'Hello',
      'Meatballs' => 'Meatballs',
        );
        if($context->language()->isoCode() == 'sv') {
            $phrases = array(
        'Hello' => 'Bork, bork, bork!',
        'Meatballs' => 'Swedish meatballs',
            );
        }
        return new SimpleTranslator($phrases);
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
    ->setLanguageLoader(new MyLanguageLoader())
    ->setTranslatorLoader(new SimpleTranslatorLoader())
    ->setComponentCreator($components)
    ->setIdentityLoader($identity_loader)
    ->run('VIH_Intranet_Controller_Root')
    ->out();
}