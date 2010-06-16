<?php
class VIH_Intranet_Controller_Nyheder_Show extends k_Component
{
    public $map = array('edit'   => 'VIH_Intranet_Controller_Nyheder_Edit',
                        'delete' => 'VIH_Intranet_Controller_Nyheder_Delete');

    private $form;
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        return $this->map[$name];
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $this->form = new HTML_QuickForm('nyhed', 'post', $this->url(null));
        $this->form->addElement('hidden', 'id');
        $this->form->addElement('file', 'userfile', 'Fil');
        $this->form->addElement('submit', null, 'Upload');

        return $this->form;
    }

    function renderHtml()
    {
        $nyhed = new VIH_News($this->name());

        if (is_numeric($this->query('sletbillede'))) {
            $nyhed->deletePicture($this->query('sletbillede'));
        }

        $pictures = $nyhed->getPictures();
        $pic_html = '';
        foreach ($pictures AS $pic) {
            $file = new VIH_FileHandler($pic['file_id']);
            if ($file->get('id')) {
                $file->loadInstance('small');
            }
            $pic_html .= '<div>' . $file->getImageHtml() . '<br /><a href="'. $this->url('./') . '?sletbillede='.$pic['file_id'] . '">Slet</a></div>';
        }

        $this->document->setTitle('Nyhed: ' . $nyhed->get('overskrift'));
        $this->document->addOption('Ret', $this->url('edit'));
        // $tpl->set('title', 'Nyhed');
        return '<div>'.vih_autoop($nyhed->get('tekst')).'</div> ' . $this->getForm()->toHTML() . $pic_html . $nyhed->get('date_updated');
    }

    function renderHtmlDelete()
    {
        $nyhed= new VIH_News($this->name());
        if ($nyhed->delete()) {
            return new k_SeeOther($this->context->url('../'));
        }
    }

    function postMultipart()
    {
        $nyhed = new VIH_News($this->name());
        if ($this->getForm()->validate()) {
            $file = new VIH_FileHandler;
            if ($file->upload('userfile')) {
                $nyhed->addPicture($file->get('id'));
            }
        }
        return $this->render();
    }
}
