<?php
class VIH_Intranet_Controller_Langekurser_Show extends k_Component
{
    public $map = array('edit' => 'VIH_Intranet_Controller_Langekurser_Edit',
                        'delete' => 'VIH_Intranet_Controller_Langekurser_Delete');

    public $form;
    protected $template;
    protected $doctrine;

    function __construct(k_TemplateFactory $template, Doctrine_Connection_Common $doctrine)
    {
        $this->template = $template;
        $þhis->doctrine = $doctrine;
    }

    function map($name)
    {
        if ($name == 'edit') {
            return 'VIH_Intranet_Controller_Langekurser_Edit';
        } elseif ($name == 'copy') {
            return 'VIH_Intranet_Controller_Langekurser_Copy';
        } elseif ($name == 'periode') {
            return 'VIH_Intranet_Controller_Langekurser_Periode_Index';
        } elseif ($name == 'tilmeldinger') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Tilmeldinger';
        } elseif ($name == 'rater') {
            return 'VIH_Intranet_Controller_Langekurser_Rater';
        } elseif ($name == 'fag') {
            return 'VIH_Intranet_Controller_Langekurser_Fag_Index';
        } elseif ($name == 'ministeriumliste') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Ministeriumliste';
        } elseif ($name == 'elevuger') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Elevugerliste';
        }
    }

    function dispatch()
    {
        $kursus = new VIH_Model_LangtKursus($this->name());
        if ($kursus->get("id") == 0) {
            return new k_PageNotFound();
        }
        return parent::dispatch();
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_LangtKursus((int)$this->name());

        if (is_numeric($this->query('sletbillede'))) {
            $kursus->deletePicture($this->query('sletbillede'));
        }

        $pictures = $kursus->getPictures();
        $pic_html = '';

        foreach($pictures as $pic) {
            $file = new VIH_FileHandler($pic['file_id']);
            if ($file->get('id')) {
                $file->loadInstance('small');
            }
            $pic_html .= '<div>' . $file->getImageHtml() . '<br /><a href="'.$this->url().'?sletbillede='.$pic['file_id'].'&amp;id='.$kursus->get('id').'">Slet</a></div>';
        }

        $this->document->setTitle($kursus->getKursusNavn());
        $this->document->addOption('Kurser', $this->url('../'));
        $this->document->addOption('Ret', $this->url('edit'));
        $this->document->addOption('Lav en kopi', $this->url('copy'));
        $this->document->addOption('Slet', $this->url('delete'));
        $this->document->addOption('Rater', $this->url('rater'));
        $this->document->addOption('Perioder', $this->url('periode'));
        $this->document->addOption('Ministerium', $this->url('ministeriumliste'));
        $this->document->addOption('Elevuger', $this->url('elevuger'));
        $this->document->addOption('Tilmeldinger', $this->url('tilmeldinger'));
        $this->document->addOption('Holdlister', $this->url('../holdliste'));

        $data = array('kursus' => $kursus, 'subjects' => $this->getSubjects());

        $tpl = $this->template->create('langekurser/show');
        return $tpl->render($this, $data) . $this->getForm()->toHTML() . $pic_html;
    }

    function postMultipart()
    {
        $kursus = new VIH_Model_LangtKursus((int)$this->name());
        if ($this->getForm()->validate()) {
            $file = new Ilib_FileHandler;
            if ($file->upload('userfile')) {
                $kursus->addPicture($file->get('id'));
                return new k_SeeOther($this->url());
            }
        }
        return $this->render();
    }

    function renderHtmlDelete()
    {
        $kursus = new VIH_Model_LangtKursus($this->name());
        if ($kursus->delete()) {
            return new k_SeeOther($this->url('../'));
        }
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $kursus = new VIH_Model_LangtKursus((int)$this->name());

        $form = new HTML_QuickForm('show', 'POST', $this->url());
        $form->addElement('hidden', 'id', $kursus->get('id'));
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');
        return ($this->form = $form);
    }

    function getSubjects()
    {
        $kursus = new VIH_Model_LangtKursus((int)$this->name());

        $data = array('kursus' => $kursus);

        $tpl = $this->template->create('langekurser/tilmelding/fagcount');
        return $tpl->render($this, $data);
        /*
        $conn = $this->registry->get('doctrine');
        $registrations = Doctrine::getTable('VIH_Model_Course_Registration')->findByKursusId($kursus->getId());

        $subjects = array();

        foreach ($registrations as $registration) {
            $i = 0;
            foreach ($registration->Subjects as $subject) {
                $subjects[$subject->getId()]['fag'] = $subject->getName();
                if (!isset($subjects[$subject->getId()]['count'])) {
                    $subjects[$subject->getId()]['count'] = 1;
                } else {
                    $subjects[$subject->getId()]['count']++;
                }

            }
        }
        return $subjects;
        */
    }
}
