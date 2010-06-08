<?php
class VIH_Intranet_Controller_Langekurser_Fag_Index extends k_Component
{
    private $pdo;
    protected $template;

    function __construct(pdoext_Connection $pdo, k_TemplateFactory $template)
    {
        $this->pdo = $pdo;
        $this->template = $template;
    }

    function renderHtml()
    {
        $langtkursus = new VIH_Model_LangtKursus($this->context->name());
        $fag = VIH_Model_Fag::getList();
        $selected = $langtkursus->getFag($this->pdo, 'all');

        $data = array('fag' => $fag,
                      'selected' => $selected,
                      'periods' => VIH_Model_LangtKursus_Periode::getFromKursusId($this->pdo, $this->context->name()));

        $tpl = $this->template->create('VIH/Intranet/view/langekurser/fag');
        return $this->render($this, $data);
    }

    function postForm()
    {
        $langtkursus = new VIH_Model_LangtKursus($this->context->name());
        $langtkursus->flushFag();
        $post = $this->body();
        foreach ($this->body('fag') as $key => $value) {
            $fag = new VIH_Model_Fag($value);
            if (empty($post['period'][$key])) {
                continue;
            }

            foreach ($post['period'][$key] as $key => $value) {
                $periode = VIH_Model_LangtKursus_Periode::getFromId($this->pdo, $value);
                $fagperiode = new VIH_Model_LangtKursus_FagPeriode($fag, $periode);
                $langtkursus->addFag($fagperiode);
            }
        }
        return new k_SeeOther($this->url());
    }
}