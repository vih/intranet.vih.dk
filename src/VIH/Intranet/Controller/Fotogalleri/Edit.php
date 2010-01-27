<?php

class VIH_Intranet_Controller_Fotogalleri_Edit extends k_Component
{
    private $form;

    private $db;
    private $mdb2;
    protected $template;

    function __construct(DB $db, MDB2_Driver_Common $mdb2, k_TemplateFactory $template)
    {
        $this->db = $db;
        $this->mdb2 = $mdb2;
        $this->template = $template;
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm('fotogalleri', 'POST', $this->url());
        $form->addElement('hidden', 'id');
        $form->addElement('text', 'description', 'Beskrivelse');
        $form->addElement('checkbox', 'active', '', 'Aktiv');
        $form->addElement('submit', null, 'Gem og tilføj billeder');
        return ($this->form = $form);
    }

    function renderHtml()
    {
        if(isset($this->context->name())) {
            $db = $this->db;

            $result = $db->query('SELECT id, description, DATE_FORMAT(date_created, "%d-%m-%Y") AS dk_date_created, active FROM fotogalleri WHERE id = '.intval($this->context->name()));
            if (PEAR::isError($result)) {
                die($result->getMessage());
            }
            $row = $result->fetchRow();

            $this->getForm()->setDefaults(array(
            'id' => $row['id'],
            'description' => $row['description'],
            'active' => $row['active']));
        }


        $this->document->setTitle('Rediger højdepunkt');
        $this->document->options = array($this->context->url() => 'Tilbage');
        return $this->getForm()->toHTML();

    }

    function postForm()
    {
        if ($this->getForm()->validate()) {

            $db = $this->mdb2;
            $values = $this->body();
            if($values['active'] == NULL) $values['active'] = 0;

            $sql = 'description = '.$db->quote($values['description'], 'text').', ' .
                    'active = '.$db->quote($values['active'], 'integer');

            $id = $this->context->name();

            if($id != 0) {
                $result = $db->exec('UPDATE fotogalleri SET '.$sql.' WHERE id = '.$db->quote($id, 'integer'));
                if(PEAR::isError($result)) {
                    trigger_error($result->getUserInfo(), E_USER_ERROR);
                    exit;
                }
                return new k_SeeOther($this->url('../'));
            } else {
                $result = $db->exec('INSERT INTO fotogalleri SET '.$sql.', date_created = NOW()');
                if(PEAR::isError($result)) {
                    trigger_error($result->getUserInfo(), E_USER_ERROR);
                    exit;
                }

                $id = $db->lastInsertId('fotogalleri', 'id');
                if(PEAR::isError($id)) {
                    trigger_error($id->getUserInfo(), E_USER_ERROR);
                    exit;
                }
                return new k_SeeOther($this->context->url($id));

            }
        }
        return $this->render();

    }
}
