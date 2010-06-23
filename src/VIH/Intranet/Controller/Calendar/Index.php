<?php
class VIH_Intranet_Controller_Calendar_Index extends k_Component
{
    protected $form;
    protected $template;

    function renderHtml()
    {
        $this->document->setTitle('Kalender');
        $this->document->addOption('Google kalenderen', 'http://www.google.com/calendar/embed?src=scv5aba9r3r5qcs1m6uddskjic%40group.calendar.google.com');

        return $this->getForm()->toHtml();
    }

    function getUserData()
    {
        require_once '/home/lsolesen/workspace/structures-ical/src/Structures/Ical.php';
        $gateway = new Structures_IcalGateway;
        $ical = $gateway->getFromUri('http://www.google.com/calendar/ical/scv5aba9r3r5qcs1m6uddskjic%40group.calendar.google.com/public/basic.ics');

        $return = '';

        foreach ($ical->getSortedEvents() as $event) {
            $start = new Date($event['DTSTART']);
            $end = new Date($event['DTEND']);

            if ($start->format('%Y') < date('Y')) {
                continue;
            }

            if ($start->format('%Y-%m-%d') != $end->format('%Y-%m-%d')) {
                $return .= 'fra ' . $start->format('%d.%m.%Y') . ' til ' .  $end->format('%d.%m.%Y') . ': ' . ' ' . $event['SUMMARY'] . "\n";
            } elseif ($start->format('%R') == '00:00') {
                $return .= $start->format('%d.%m.%Y') . ': ' . $event['SUMMARY'] . "\n";
            } elseif ($start->format('%R') == $end->format('%R')) {
                $return .= $start->format('%d.%m.%Y') . ': ' . $start->format('%R') . ' ' . $event['SUMMARY'] . "\n";

            } else {
                $return .= $start->format('%d.%m.%Y') . ': ' . $start->format('%R') . '-' . $end->format('%R') . ' ' . $event['SUMMARY'] . "\n";
            }

        }
        return $return;
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm('calendar', 'post', 'http://kalendersiden.dk/generate.php');
        $form->addElement('text', 'month', 'Første måned');
        $form->addElement('text', 'months', 'Antal måneder');
        $form->addElement('text', 'year', 'Årstal');
        $form->addElement('hidden', 'pages');
        $form->addElement('hidden', 'format');
        $form->addElement('hidden', 'head');
        $form->addElement('hidden', 'color');
        $form->addElement('hidden', 'weeks');
        $form->addElement('hidden', 'userdata');
        $form->setDefaults(array(
            'month' => date('m'),
            'months' => 6,
            'year' => date('Y'),
            'pages' => 2,
            'format' => 'portrait',
            'head' => 'Vejle Idrætshøjskole',
            'color' => '90 90 100:100 100 100:',
            'weeks' => 't',
            'userdata' => $this->getUserData()
        ));
        $form->addElement('submit', null, 'Hent kalender');

        return ($this->form = $form);
    }
}