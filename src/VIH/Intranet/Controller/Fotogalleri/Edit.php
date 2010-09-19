<?php

class VIH_Intranet_Controller_Fotogalleri_Edit extends k_Component
{
    private $form;

    private $db;
    private $mdb2;
    protected $template;

    function __construct(DB_common $db, MDB2_Driver_Common $mdb2, k_TemplateFactory $template)
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
            $result = $this->db->query('SELECT id, description, DATE_FORMAT(date_created, "%d-%m-%Y") AS dk_date_created, active FROM fotogalleri WHERE id = '.intval($this->context->name()));
            if (PEAR::isError($result)) {
                throw new Exception($result->getMessage());
            }
            $row = $result->fetchRow();

            $this->getForm()->setDefaults(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'active' => $row['active']));
        }


        $this->document->setTitle('Rediger højdepunkt');
        $this->document->addOption('Tilbage', $this->context->url());
        return $this->getForm()->toHTML();

    }

    function postForm()
    {
        if ($this->getForm()->validate()) {

            $values = $this->body();
            if ($values['active'] == NULL) $values['active'] = 0;

            $sql = 'description = '.$this->mdb2->quote($values['description'], 'text').', ' .
                    'active = '.$this->mdb2->quote($values['active'], 'integer');

            $id = $this->context->name();

            if($id != 0) {
                $result = $this->mdb2->exec('UPDATE fotogalleri SET '.$sql.' WHERE id = '.$this->mdb2->quote($id, 'integer'));
                if (PEAR::isError($result)) {
                    throw new Exception($result->getUserInfo());
                }
                return new k_SeeOther($this->url('../'));
            } else {
                $result = $this->mdb2->exec('INSERT INTO fotogalleri SET '.$sql.', date_created = NOW()');
                if (PEAR::isError($result)) {
                    throw new Exception($result->getUserInfo());
                }

                $id = $this->mdb2->lastInsertId('fotogalleri', 'id');
                if (PEAR::isError($id)) {
                    throw new Exception($id->getUserInfo());
                }
                return new k_SeeOther($this->context->url($id));

            }
        }
        return $this->render();
    }
}
