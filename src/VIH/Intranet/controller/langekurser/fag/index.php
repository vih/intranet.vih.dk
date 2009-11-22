<?php
class VIH_Intranet_Controller_LangeKurser_Fag_Index extends k_Controller
{
    function GET()
    {
        $langtkursus = new VIH_Model_LangtKursus($this->context->name);
        $fag = VIH_Model_Fag::getList();
        $selected = $langtkursus->getFag($this->registry->get('database'), 'all');

        $data = array('fag' => $fag,
                      'selected' => $selected,
                      'periods' => VIH_Model_LangtKursus_Periode::getFromKursusId($this->registry->get('database'), $this->context->name));
        return $this->render('vih/intranet/view/langekurser/fag-tpl.php', $data);
    }

    function POST()
    {
        $langtkursus = new VIH_Model_LangtKursus($this->context->name);
        $langtkursus->flushFag();
        foreach ($this->POST['fag'] as $key => $value) {
            $fag = new VIH_Model_Fag($value);
            if (empty($this->POST['period'][$key])) {
                continue;
            }

            foreach ($this->POST['period'][$key] as $key => $value) {
                $periode = VIH_Model_LangtKursus_Periode::getFromId($this->registry->get('database'), $value);
                $fagperiode = new VIH_Model_LangtKursus_FagPeriode($fag, $periode);
                $langtkursus->addFag($fagperiode);
            }
        }
        throw new k_http_Redirect($this->url());
    }
}