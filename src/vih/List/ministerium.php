<?php
require_once 'Template/Template.php';

class VIH_List_Ministerium
{
    private $tpl;

    function update($course, $participants)
    {
        $this->tpl = new Template(dirname(__FILE__) . '/templates/');
        $this->tpl->assign('kursus', $course);
        $this->tpl->assign('deltagere', $participants);

    }

    function fetch()
    {
        return $this->tpl->fetch('ministerium.tpl.php');
    }

}