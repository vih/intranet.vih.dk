<?php
class VIH_Intranet_Controller_Nyheder_Edit extends k_Component
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

        $nyhed = new VIH_News;

        $date_options = array('format' => 'd m Y H i',
                              'optionIncrement' => array('i' => 15),
                              'minYear' => date('Y') - 15,
                              'maxYear' => date('Y') + 5,
                              'addEmptyOption' => 'true',
                              'emptyOptionValue' => '',
                              'emptyOptionText' => 'Vælg');

        $this->form = new HTML_QuickForm('news', '', $this->url('./'));
        $this->form->addElement('text', 'overskrift', 'Overskrift');
        $this->form->addElement('textarea', 'tekst', 'Tekst', array('cols' => 80, 'rows' => 20));
        $this->form->addElement('checkbox', 'published', 'Udgivet');
        $this->form->addElement('date', 'date_publish', 'Udgivelsesdato', $date_options);
        $this->form->addElement('date', 'date_expire', 'Udløbsdato', $date_options);
        //$this->form->addElement('select', 'kategori_id', 'Kategori', $nyhed->kategori, array('addEmptyOption' => 'true', 'emptyOptionValue' => '', 'emptyOptionText' => 'Vælg'));
        $this->form->addElement('select', 'type_id', 'Type', $nyhed->type, array('addEmptyOption' => 'true', 'emptyOptionValue' => '', 'emptyOptionText' => 'Vælg'));
        //$this->form->addElement('select', 'prioritet_id', 'Prioritet', $nyhed->prioritet, array('addEmptyOption' => 'true', 'emptyOptionValue' => '', 'emptyOptionText' => 'Vælg'));
        $this->form->addElement('textarea', 'keyword', 'Keywords (fx lange-kurser, haandbold, fodbold)', array('cols' => 80, 'rows' => 5));
        $this->form->addElement('header', null, 'Søgemaskineguf');
        $this->form->addElement('text', 'title', 'Titel', array('size' => 80));
        $this->form->addElement('textarea', 'description', 'Description', array('cols' => 80, 'rows' => 3));
        $this->form->addElement('textarea', 'keywords', 'Nøgleord', array('cols' => 80, 'rows' => 2));
        $this->form->addElement('submit', null, 'Gem');

        $this->form->addRule('overskrift', 'Du skal skrive en overskrift', 'required');
        $this->form->addRule('date_publish', 'Du skal skrive en udgivelsesdato', 'required');
        return $this->form;
    }

    function renderHtml()
    {
        if (is_numeric($this->context->name())) {
            $nyhed = new VIH_News((int)$this->context->name());
        } else {
            $nyhed = new VIH_News;
        }

        if ($nyhed->get('id') > 0) {
            $defaults = array('overskrift' => $nyhed->get('overskrift'),
                              //'kategori_id' => $nyhed->get('kategori_id'),
                              'type_id' => $nyhed->get('type_id'),
                              //'prioritet_id' => $nyhed->get('prioritet_id'),
                              'tekst' => $nyhed->get('tekst'),
                              'date_publish' => $nyhed->get('date_publish'),
                              'title' => $nyhed->get('title'),
                              'keywords' => $nyhed->get('keywords'),
                              'description' => $nyhed->get('description'),
                              'published' => $nyhed->get('published'));
            if ($nyhed->get('date_expire') == '0000-00-00 00:00:00') {
                $defaults['date_expire'] = '';
            } else {
                $defaults['date_expire'] = $nyhed->get('date_expire');
            }
        } else {
            $defaults['date_publish'] = date('Y-m-d H:i');
        }

        $keyword = new Ilib_Keyword_Appender($nyhed); // starter keyword objektet

        $defaults['keyword'] = $keyword->getConnectedKeywordsAsString();

        $this->getForm()->setDefaults($defaults);

        $this->document->setTitle('Rediger nyhed');

        return $this->getForm()->toHTML();
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $nyhed = new VIH_News($this->context->name());
            $input = $this->body();
            $input['title'] = vih_handle_microsoft($input['title']);
            $input['tekst'] = vih_handle_microsoft($input['tekst']);
            $input['overskrift'] = vih_handle_microsoft($input['overskrift']);
            $input['date_publish'] = $this->body('date_publish');
            $input['date_publish'] = $input['date_publish']['Y'] . '-' . $input['date_publish']['m'] . '-' . $input['date_publish']['d'];
            $input['date_expire'] = $this->body('date_expire');
            if (!empty($input['date_expire']['Y'])) {
                $input['date_expire'] = $input['date_expire']['Y'] . '-' . $input['date_expire']['m'] . '-' . $input['date_expire']['d'];
            } else {
                $input['date_expire'] = '0000-00-00 00:00:00';
            }

            if ($id = $nyhed->save($input)) {
            }

            // strengen med keywords
            if (!empty($input['keyword'])) {
                $keyword = new Ilib_Keyword_Appender($nyhed); // starter keyword objektet
                $appender = new Ilib_Keyword_StringAppender(new Ilib_Keyword($nyhed), $keyword);
                $appender->addKeywordsByString($input['keyword']);
            }

            return new k_SeeOther($this->url('../' . $id));

        }
        return $this->render();

    }
}
