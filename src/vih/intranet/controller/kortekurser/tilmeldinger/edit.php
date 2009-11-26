<?php
class VIH_Intranet_Controller_KorteKurser_Tilmeldinger_Edit extends VIH_Controller_KortKursus_Tilmelding_Kontakt
{
    function getTilmelding()
    {
        return new VIH_Model_KortKursus_Tilmelding($this->context->name);
    }

    function getRedirectUrl()
    {
        return $this->context->url();
    }
    /*
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->context->name);
        $deltagere = $tilmelding->getDeltagere();

        $form = new HTML_QuickForm(null, 'post', $this->url(), '', null, true);
        $form->addElement('header', null, 'Kontaktperson');
        $form->addElement('text', 'kontaktnavn', 'Navn');
        $form->addElement('text', 'adresse', 'Adresse');
        $form->addElement('text', 'postnr', 'Postnummer');
        $form->addElement('text', 'postby', 'By');
        $form->addElement('text', 'telefonnummer', 'Telefonnummer');
        $form->addElement('text', 'arbejdstelefon', 'Arbejdstelefon', 'Telefonnummer hvor du kan træffes mellem 8 og 16');
        $form->addElement('text', 'mobil', 'Mobil');
        $form->addElement('text', 'email', 'E-mail'); // 'Bekræftelse sendes til denne e-mail-adresse. Hvis den udelades bruger vi Post Danmark.'
        $form->addElement('header', null, 'Vil du tegne afbestillingsforsikring');
        $form->addElement('radio', 'afbestillingsforsikring', 'Afbestillingsforsikring', 'Ja', 'Ja');
        $form->addElement('radio', 'afbestillingsforsikring', '', 'Nej', 'Nej');
        $form->addElement('text', 'rabat', 'Rabat');
        //$form->addRule('kontaktnavn', 'Skriv venligst dit navn', 'required');
        //$form->addRule('adresse', 'Skriv venligst din adresse', 'required');
        //$form->addRule('postnr', 'Skriv venligst din postnummer', 'required');
        //$form->addRule('postby', 'Skriv venligst din postby', 'required');
        //$form->addRule('telefon', 'Skriv venligst din telefonnummer', 'required');
        //$form->addRule('arbejdstelefon', 'Skriv venligst din arbejdstelefon', 'required');
        //$form->addRule('email', 'Den e-mail du har indtastet er ikke gyldig', 'e-mail');
        //$form->addRule('afbestillingsforsikring', 'Du skal vælge, om du vil have en afbestillingsforsikring', 'required');

        $form->setDefaults(array(
            'kontaktnavn' => $tilmelding->get('navn'),
            'adresse' => $tilmelding->get('adresse'),
            'postnr' => $tilmelding->get('postnr'),
            'postby' => $tilmelding->get('postby'),
            'telefonnummer' => $tilmelding->get('telefonnummer'),
            'arbejdstelefon' => $tilmelding->get('arbejdstelefon'),
            'mobil' => $tilmelding->get('mobil'),
            'email' => $tilmelding->get('email'),
            'afbestillingsforsikring' => $tilmelding->get('afbestillingsforsikring'),
            'besked' => $tilmelding->get('besked'),
            'rabat' => $tilmelding->get('rabat')
        ));

        $deltager_nummer = 1;
        $i = 0;
        foreach ($deltagere AS $deltager) {
            $form->addElement('header', null, 'Deltager ' .  $deltager_nummer);
            $form->addElement('hidden', 'deltager_id['.$i.']');
            $form->addElement('text', 'navn['.$i.']', 'Navn');
            $form->addElement('text', 'cpr['.$i.']', 'CPR-nummer', '(ddmmåå-xxxx)', null);

            //$form->addRule('navn['.$i.']', 'Du skal skrive et navn', 'required');
            //$form->addRule('cpr['.$i.']', 'Du skal skrive et cpr-nummer', 'required');
            //$form->addRule('cpr['.$i.']', 'Du skal skrive et gyldigt cpr-nummer', 'callback', 'Validate_DK::ssn');

            $form->setDefaults(array(
                'deltager_id['.$i.']' => $deltager->get('id'),
                'navn['.$i.']' => $deltager->get('navn'),
                'cpr['.$i.']' => $deltager->get('cpr'),
            ));

            $radio = array();
            if ($tilmelding->kursus->get('indkvartering') == 'kursuscenteret') {
                $form->addElement('radio', 'enevaerelse['.$i.']', 'Eneværelse', 'Ja', 'ja');
                $form->addElement('radio', 'enevaerelse['.$i.']', '', 'Nej', 'nej');
                $form->addElement('text', 'sambo['.$i.']', 'Jeg ønsker at dele toilet og bad med?');
                //$form->addRule('enevaerelse['.$i.']', 'Du skal vælge om du vil have eneværelse', 'required');
                // $form->addRule('sambo['.$i.']', 'Hvem vil du dele toilet og bad med?', 'required');

                $form->setDefaults(array(
                    'enevaerelse['.$i.']' => $deltager->get('enevaerelse'),
                    'sambo['.$i.']' => $deltager->get('sambo')
                ));
            }

            switch ($tilmelding->kursus->get('gruppe_id')) {

                case 1: // golf
                        $form->addElement('text', 'handicap['.$i.']', 'Golfhandicap', '(begynder &rarr; skriv 99)');
                        $form->addElement('text', 'klub['.$i.']', 'Klub');
                        $form->addElement('text', 'dgu['.$i.']', 'DGU-medlem', null, null, 'ja');
                        //$form->addRule('handicap['.$i.']', 'Du skal vælge dit handicap', 'required');
                        //$form->addRule('klub['.$i.']', 'Du skal skrive en klub', 'required');

                        $form->setDefaults(array(
                            'handicap['.$i.']' => $deltager->get('handicap'),
                            'klub['.$i.']' => $deltager->get('klub'),
                            'dgu['.$i.']' => $deltager->get('dgu')
                        ));
                    break;

                case 3: // bridge
                        $niveau = array('Begynder' => 'Begynder', 'Let øvet' => 'Let øvet', 'Øvet' => 'Øvet', 'Meget øvet' => 'Meget øvet');
                        $form->addElement('select', 'niveau['.$i.']', 'Bridgeniveau', $niveau);
                        //$form->addRule('niveau['.$i.']', 'Hvilket bridgeniveau har du?', 'required');
                        $form->setDefaults(array(
                            'niveau['.$i.']' => $deltager->get('niveau')
                        ));
                    break;
                case 4: // golf og bridge
                        $form->addElement('text', 'handicap['.$i.']', 'Golfhandicap', '(ingen spillere med handicap større end 50)');
                        $form->addElement('text', 'klub['.$i.']', 'Klub');
                        $form->addElement('text', 'dgu['.$i.']', 'DGU-medlem', 'Du skal være dgu-medlem for at deltage på kurset', null, 'ja');
                        $niveau = array('Let øvet' => 'Let øvet', 'Øvet' => 'Øvet', 'Meget øvet' => 'Meget øvet');
                        $form->addElement('select', 'niveau['.$i.']', 'Bridgeniveau', $niveau);
                        //$form->addRule('handicap['.$i.']', 'Du skal vælge dit handicap', 'required');
                        //$form->addRule('klub['.$i.']', 'Hvem vil skrive en klub', 'required');
                        //$form->addRule('niveau['.$i.']', 'Hvilket bridgeniveau har du?', 'required');
                        $form->setDefaults(array(
                            'handicap['.$i.']' => $deltager->get('handicap'),
                            'klub['.$i.']' => $deltager->get('klub'),
                            'dgu['.$i.']' => $deltager->get('dgu'),
                            'niveau['.$i.']' => $deltager->get('niveau')
                        ));
                    break;
                default:
                    break;
            } // switch

            $deltager_nummer++;
            $i++;
        } // foreach

        $form->addElement('header', null, 'Øvrige oplysninger');
        $form->addElement('textarea', 'besked', 'Besked');
        $form->addElement('submit', null, 'Videre >>');

        $form->applyFilter('__ALL__', 'trim');

        return ($this->form = $form);

    }

    function GET()
    {
        $this->document->title = 'Rediger tilmelding';

        return $this->getForm()->toHTML();

    }

    function POST()
    {
        if ($this->getForm()->validate()) {
            $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->context->name);
            $deltagere = $tilmelding->getDeltagere();

            if ($id = $tilmelding->save($this->POST->getArrayCopy())) {
                $i = 0;
                foreach ($deltagere AS $deltager) {
                    $var['id'] = $this->POST['deltager_id'][$i];
                    $var['navn'] = $this->POST['navn'][$i];
                    $var['cpr'] = $this->POST['cpr'][$i];

                    if ($tilmelding->kursus->get('indkvartering') == 'kursuscenteret') {
                        $var['enevaerelse'] = $this->POST['enevaerelse'][$i];
                        $var['sambo'] = $this->POST['sambo'][$i];
                    }

                    switch ($tilmelding->kursus->get('gruppe_id')) {

                        case 1: // golf
                            $var['handicap'] = $this->POST['handicap'][$i];
                            $var['klub'] = $this->POST['klub'][$i];
                            $var['dgu'] = $this->POST['dgu'][$i];
                        break;
                        case 3: // bridge
                            $var['niveau'] = $this->POST['niveau'][$i];
                        break;
                        case 4: // golf og bridge
                            $var['handicap'] = $this->POST['handicap'][$i];
                            $var['klub'] = $this->POST['klub'][$i];
                            $var['dgu'] = $this->POST['dgu'][$i];
                            $var['niveau'] = $this->POST['niveau'][$i];
                        break;
                        default:
                        break;
                    } // switch

                    $deltager_object = new VIH_Model_KortKursus_Tilmelding_Deltager($tilmelding, $this->POST['deltager_id'][$i]);

                    if (!$deltager_object->save($var)) {
                        // det gik ikke ret godt. Skal der ske noget?
                    }
                    $i++;
                } // foreach

                throw new k_http_Redirect($this->context->url());
            }
        } else {
            $this->document->title = 'Rediger tilmelding';
            return $this->getForm()->toHTML();
        }

    }
	*/
}
