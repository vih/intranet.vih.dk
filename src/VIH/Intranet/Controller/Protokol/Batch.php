<?php
class VIH_Intranet_Controller_Protokol_Batch extends k_Component
{
    protected $form;
    protected $template;
    protected $db;

    function __construct(k_TemplateFactory $template, DB_common $db)
    {
        $this->template = $template;
        $this->db = $db;
    }

    function renderHtml()
    {
        $this->document->setTitle('Batch indtastning');

        $tpl = $this->template->create('protokol/batch');

        $this->getForm()->setDefaults(array('date' => $this->getDate()));

        return $this->getForm()->toHTML() . $tpl->render($this, array('elever' => $this->context->getTilmeldinger()));
    }

    function process($elev_id, $type)
    {
            $fields = array('date_created', 'date_updated', 'date_start', 'date_end', 'tilmelding_id', 'text', 'type_key');
            $values = array('NOW()',
                            'NOW()',
                            $this->body('date'),
                            $this->body('date'),
                            $elev_id,
                            $this->body('text'),
                            $type);

            $sth = $this->db->autoPrepare('langtkursus_tilmelding_protokol_item', $fields, DB_AUTOQUERY_INSERT);

            $res = $this->db->execute($sth, $values);

            if (PEAR::isError($res)) {
                throw new Exception($res->getMessage());
            }
            return true;
    }

    function postForm()
    {
        $elever = $this->body('elev');

        foreach ((array)$elever as $key => $value) {
            $this->process($key, $value);
        }

        return new k_SeeOther($this->url('../'));
    }

    function getDate()
    {
        if ($this->query('date')) {
            $get = $this->query('date');
            $date = $get['Y'] . '-' . $get['M'] . '-' .$get['d'];
        } else {
            $date = date('Y-m-d');
        }

        return $date;
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $form = new HTML_QuickForm('holdliste', 'GET', $this->url());
        $form->addElement('date', 'date', 'date');
        $form->addElement('submit', null, 'Hent');

        return ($this->form = $form);
    }

    function getTypeKeys()
    {
        return $this->context->getTypeKeys();
    }
}
