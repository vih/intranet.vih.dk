<?php
class VIH_Intranet_Controller_LangeKurser_Show extends k_Controller
{
    public $map = array('edit' => 'VIH_Intranet_Controller_LangeKurser_Edit',
                        'delete' => 'VIH_Intranet_Controller_LangeKurser_Delete');

    public $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $kursus = new VIH_Model_LangtKursus((int)$this->name);

        $form = new HTML_QuickForm('show', 'POST', $this->url());
        $form->addElement('hidden', 'id', $kursus->get('id'));
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');
        return ($this->form = $form);
    }

    function GET()
    {
        $kursus = new VIH_Model_LangtKursus((int)$this->name);

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

        $this->document->title = $kursus->getKursusNavn();
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

        return $this->render('vih/intranet/view/langekurser/show.tpl.php', $data) . $this->getForm()->toHTML() . $pic_html;
    }

    function getSubjects()
    {
        $conn = $this->registry->get('doctrine');

        $kursus = new VIH_Model_LangtKursus((int)$this->name);

        $data = array('kursus' => $kursus);


        return $this->render('vih/intranet/view/langekurser/tilmelding/fagcount.tpl.php', $data);
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

    function POST()
    {
        $kursus = new VIH_Model_LangtKursus((int)$this->name);
        if ($this->getForm()->validate()) {
            $file = new Ilib_FileHandler;
            if($file->upload('userfile')) {
                $kursus->addPicture($file->get('id'));
                throw new k_http_Redirect($this->url());
            }
        }
    }

    function forward($name)
    {
        if ($name == 'edit') {
            $next = new VIH_Intranet_Controller_LangeKurser_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'delete') {
            $next = new VIH_Intranet_Controller_LangeKurser_Delete($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'copy') {
            $next = new VIH_Intranet_Controller_LangeKurser_Copy($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'periode') {
            $next = new VIH_Intranet_Controller_LangeKurser_Periode_Index($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'tilmeldinger') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Tilmeldinger($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'rater') {
            $next = new VIH_Intranet_Controller_LangeKurser_Rater($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'periode') {
            $next = new VIH_Intranet_Controller_LangeKurser_Periode_Index($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'fag') {
            $next = new VIH_Intranet_Controller_LangeKurser_Fag_Index($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'ministeriumliste') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Ministeriumliste($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'elevuger') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Elevugerliste($this, $name);
            return $next->handleRequest();
        } else {
            return self::GET();
        }
    }
}
