<?php
class VIH_Intranet_Controller_Root extends k_Component
{
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
                return 'VIH_Intranet_Controller_Login';
            case 'logout':
                return 'VIH_Intranet_Controller_Logout';
        }
    }

    function document()
    {
        return $this->document;
    }

    function execute()
    {
        return $this->wrap(parent::execute());
    }

    function wrapHtml($content)
    {
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

    function renderHtml()
    {
        return sprintf(
      "<p>Vejle Idrætshøjskoles intranet er blevet opdateret. Klik på <a href='%s'>restricted</a> for at logge ind. Brugernavnet er det samme som du plejer at bruge som password.</p>",
        htmlspecialchars($this->url('restricted')));
    }
}