<?php
class VIH_Intranet_Controller_Elevforeningen_Index extends k_Component
{
    protected $template;
    protected $db;
    protected $contact_client;

    function __construct(k_TemplateFactory $template, DB_Sql $db, IntrafacePublic_Contact_XMLRPC_Client $client)
    {
        $this->template = $template;
        $this->db = $db;
        $this->contact_client = $client;
    }
    function renderHtml()
    {
        $this->db->query("SELECT aargange FROM elevforeningen_jubilar ORDER BY id DESC");
        if ($this->db->nextRecord()){
            $selected = unserialize($this->db->f('aargange'));
        }

        foreach ($this->contact_client->getKeywords() AS $key=>$value) {
            $input .= '<label><input type="checkbox" name="jubilar[]" value="'.$value['id'].'" ';
            if (in_array($value['id'], $selected)) {
                $input .= ' checked="checked"';
            }
            $input .= '/> '.$value['keyword'].' </label><br />';
        }

        return '
            <h1>Elevforeningen</h1>
            <form method="post" action="'.$this->url().'">
                <fieldset>
                    '.$input.'
                </fieldset>
                <input type="submit" value="Gem" />
            </form>
            ';
    }

    function postForm()
    {
        $input = '';
        $selected = array();

        if (!empty($_POST)) {
            $this->db->query("INSERT INTO elevforeningen_jubilar SET date_created = NOW(), aargange = '".serialize($_POST['jubilar'])."'");
        }

        return new k_SeeOther($this->url());
    }
}