<?php
/**
 * Only used for ajax calls
 *
 * @category
 * @package
 * @author     lsolesen
 * @copyright
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version
 *
 */
class VIH_Intranet_Controller_Kortekurser_Begyndere extends k_Component
{
    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name());
        if ($kursus->get('gruppe_id') != 1) {
            echo '';
            exit;
        }
        $begyndere = $kursus->getBegyndere();

        throw new k_http_Response(200, $begyndere);
    }
}