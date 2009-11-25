<?php
class VIH_Intranet_Controller_Langekurser_Show extends k_Component
{
    public $map = array('edit' => 'VIH_Intranet_Controller_Langekurser_Edit',
                        'delete' => 'VIH_Intranet_Controller_Langekurser_Delete');

    public $form;

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
        $kursus = new VIH_Model_LangtKursus((int)$this->name());

        $form = new HTML_QuickForm('show', 'POST', $this->url());
        $form->addElement('hidden', 'id', $kursus->get('id'));
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');
        return ($this->form = $form);
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_LangtKursus((int)$this->name());

        if (!empty($this->GET['sletbillede']) AND is_numeric($this->GET['sletbillede'])) {
            $kursus->deletePicture($this->GET['sletbillede']);
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
        $this->document->options = array(
                $this->url('../') => 'Kurser',
                $this->url('edit') => 'Ret',
                $this->url('copy') => 'Lav en kopi',
                $this->url('delete') => 'Slet',
                $this->url('rater') => 'Rater',
                // $this->url('fag') => 'Fag',
                $this->url('periode') => 'Perioder',
                $this->url('ministeriumliste') => 'Ministerium',
                $this->url('elevuger') => 'Elevuger',
                $this->url('tilmeldinger') => 'Tilmeldinger',
                $this->url('/holdliste') => 'Holdlister'
        );


        $data = array('kursus' => $kursus, 'subjects' => $this->getSubjects());

        return $this->render('VIH/Intranet/view/langekurser/show.tpl.php', $data) . $this->getForm()->toHTML() . $pic_html;
    }

    function getSubjects()
    {
        $conn = $this->registry->get('doctrine');

        $kursus = new VIH_Model_LangtKursus((int)$this->name());

        $data = array('kursus' => $kursus);


        return $this->render('VIH/Intranet/view/langekurser/tilmelding/fagcount.tpl.php', $data);
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

    function postForm()
    {
        $kursus = new VIH_Model_LangtKursus((int)$this->name());
        if ($this->getForm()->validate()) {
            $file = new Ilib_FileHandler;
            if($file->upload('userfile')) {
                $kursus->addPicture($file->get('id'));
                throw new k_SeeOther($this->url());
            }
        }
    }

    function map($name)
    {
        if ($name == 'edit') {
            return 'VIH_Intranet_Controller_Langekurser_Edit';
        } elseif ($name == 'delete') {
            return 'VIH_Intranet_Controller_Langekurser_Delete';
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
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Elevugerliste'';
        }
    }
}
