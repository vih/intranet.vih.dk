<?php
//if you use ISO-8601 dates, switch to PearDate engine
define('CALENDAR_ENGINE', 'PearDate');

require_once 'Calendar/Calendar.php';
require_once 'Calendar/Month/Weekdays.php';
require_once 'Calendar/Day.php';
require_once 'Calendar/Decorator.php';


// accepts multiple entries
class DiaryEvent extends Calendar_Decorator
{
    private $entries = array();

    function __construct($calendar) {
        Calendar_Decorator::Calendar_Decorator($calendar);
    }

    function addEntry($entry) {
        $this->entries[] = $entry;
    }

    function getEntry() {
        $entry = each($this->entries);
        if ($entry) {
            return $entry['value'];
        } else {
            reset($this->entries);
            return false;
        }
    }
}

class MonthPayload_Decorator extends Calendar_Decorator
{
    //Calendar engine
    public $cE;
    public $tableHelper;

    public $year;
    public $month;
    public $firstDay = false;

    function build($events=array())
    {
        $this->tableHelper = new Calendar_Table_Helper($this, $this->firstDay);
        $this->cE = & $this->getEngine();
        $this->year  = $this->thisYear();
        $this->month = $this->thisMonth();

        $daysInMonth = $this->cE->getDaysInMonth($this->year, $this->month);
        for ($i=1; $i<=$daysInMonth; $i++) {
            $Day = new Calendar_Day(2000,1,1); // Create Day with dummy values
            $Day->setTimeStamp($this->cE->dateToStamp($this->year, $this->month, $i));
            $this->children[$i] = new DiaryEvent($Day);
        }
        if (count($events) > 0) {
            $this->setSelection($events);
        }
        /*
        Calendar_Month_Weekdays::buildEmptyDaysBefore();
        Calendar_Month_Weekdays::shiftDays();
        Calendar_Month_Weekdays::buildEmptyDaysAfter();
        Calendar_Month_Weekdays::setWeekMarkers();
        */
        return true;
    }

    function setSelection($events)
    {
        $daysInMonth = $this->cE->getDaysInMonth($this->year, $this->month);
        for ($i=1; $i<=$daysInMonth; $i++) {
            $stamp1 = $this->cE->dateToStamp($this->year, $this->month, $i);
            $stamp2 = $this->cE->dateToStamp($this->year, $this->month, $i+1);
            foreach ($events as $event) {
                if (($stamp1 >= $event['start'] && $stamp1 < $event['end']) ||
                    ($stamp2 > $event['start'] && $stamp2 < $event['end']) ||
                    ($stamp1 <= $event['start'] && $stamp2 > $event['end'])
                ) {
                    $this->children[$i]->addEntry($event);
                    $this->children[$i]->setSelected();
                }
            }
        }
    }

    function fetch()
    {
        $child = each($this->children);
        if ($child) {
            return $child['value'];
        } else {
            reset($this->children);
            return false;
        }
    }
}

class Calendar_Render_MonthlyAgenda_HTML
{
    function __construct() {}

    function toHTML($decorator)
    {
        $table = new HTML_Table(array('class' => 'calendar'));
        $table->setCaption($decorator->thisMonth().' / '.$decorator->thisYear());

        $week_test = 0;
        while ($day = $decorator->fetch()) {

            $data = array();
            $datehelper = new Date($day->thisDay('timestamp'));

            if ($week_test != $datehelper->getWeekOfYear()) {
                $data[0] = $datehelper->getWeekOfYear();
            } else {
                $data[0] = '&nbsp;';
            }
            $week_test = $datehelper->getWeekOfYear();

            $data[1] = $day->thisDay();
            $data[2] = $datehelper->getDayName();

            if ($day->isEmpty()) {
                $data[3] = '&nbsp;';
            } else {
                $i = 0;
                while ($entry = $day->getEntry()) {
                    $i++;

                    $start = new Date($entry['start']);
                    $data[3][$i] = '';
                    if ($start->format('%R') != '00:00') {
                        $data[3][$i] = $start->format('%R') . ' ';
                    }
                    $data[3][$i] .= $entry['desc'];
                    // you can print the time range as well
                }

            }
            $table->addRow($data);
        }

        return $table->toHTML();
    }
}

class VIH_Intranet_Controller_Calendar_Index extends k_Controller
{
    private $form;
    
    function getForm()
    {
        if ($this->form) return $this->form;

        $form = new HTML_QuickForm('calendar', 'get', $this->url());
        $form->addElement('date', 'date', '', array('format' => 'M Y'));
        $defaults = array('date' => date('Y-m-d'));
        $form->setDefaults($defaults);
        $form->addElement('submit', null, 'Hent');

        return ($this->form = $form);
    }
    
    function GET()
    {
        // Calendar instance used to get the dates in the preferred format:
        // you can switch Calendar Engine and the example still works
        $cal = new Calendar;
        $ical = new Structures_Ical();
        //$ical->parse('c:/Users/Lars Olesen/Desktop/basic.ics');
        $ical->parseUrl('http://www.google.com/calendar/ical/scv5aba9r3r5qcs1m6uddskjic%40group.calendar.google.com/public/basic.ics');

        $this->document->title = utf8_decode($ical->getCalendarName());
        $this->document->options = array($this->url('http://www.google.com/calendar/embed?src=scv5aba9r3r5qcs1m6uddskjic%40group.calendar.google.com') => 'Google kalenderen'); 

        $events = array();
        foreach ($ical->getSortEventList() as $event) {
            $start = new Date($event['DTSTART']['unixtime']);
            $end = new Date($event['DTEND']['unixtime']);

            $events[] = array(
                'start' => $start->format('%Y-%m-%d %T'),
                'end'   => $end->format('%Y-%m-%d %T'),
                'desc'  => utf8_decode($event['SUMMARY'])
            );
        }

        $date = $this->getForm()->exportValue('date');
        $month = new Calendar_Month_Weekdays($date['Y'], $date['M']);
        $month_decorator = new MonthPayload_Decorator($month);
        $month_decorator->build($events);

        $render = new Calendar_Render_MonthlyAgenda_HTML();
        return $this->getForm()->toHtml() . $render->toHTML($month_decorator);
    }

}