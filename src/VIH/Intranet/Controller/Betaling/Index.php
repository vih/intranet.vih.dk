<?php
/**
 * Formålet med denne side er at hæve (capture) betalinger lavet med Dankort.
 *
 * @author Lars Olesen <lars@legestue.net>
 */
class VIH_Intranet_Controller_Betaling_Index extends k_Component
{
    private $form;
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        return 'VIH_Intranet_Controller_Betaling_Show';
    }

    function renderHtml()
    {
        $this->document->setTitle('Betalinger');

        if ($this->getForm()->validate()) {
            $betalinger = VIH_Model_Betaling::search($this->getForm()->exportValue('search'));
            $data['caption'] = 'Betalinger';
        } elseif ($this->query('find') == 'alle') {
            $betaling = new VIH_Model_Betaling;
            $betalinger = $betaling->getList();
            $data['caption'] = 'Alle betalinger';
        } elseif ($this->query('find') == 'elevforeningen') {
            $betaling = new VIH_Model_Betaling;
            $betalinger = $betaling->getList('elevforeningen');
            $data['caption'] = 'Elevforeningen';
        } else {
            $betaling = new VIH_Model_Betaling;
            $betalinger = $betaling->getList('not_approved');
            $data['caption'] = 'Afventende betalinger';
        }

        $data['betalinger'] = $betalinger;

        $this->document->options = array($this->url(null, array('find'=>'alle')) => 'Alle',
                                         $this->url(null, array('find'=>'elevforeningen')) => 'Elevforeningen');

      $tpl = $this->template->create('betalinger/betalinger');
      return $tpl->render($this, $data);

    }

    function getForm()
    {
        if ($this->form) return $this->form;
        $form = new HTML_QuickForm('search', 'GET', $this->url());
        $form->addElement('text', 'search');
        $form->addElement('submit', null, 'Søg efter bundtnummer');
        return ($this->form = $form);
    }
}
