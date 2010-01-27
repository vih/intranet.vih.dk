<?php
/**
 * Formålet med denne side er at hæve (capture) betalinger lavet med Dankort.
 *
 * @author Lars Olesen <lars@legestue.net>
 */
class VIH_Intranet_Controller_Betaling_Reverse extends k_Component
{
    function renderHtml()
    {

        $betaling = new VIH_Model_Betaling($this->context->name());

        $onlinebetaling = new VIH_Onlinebetaling('reversal');
        $eval = $onlinebetaling->reverse($betaling->get('transactionnumber'));

        if ($eval) {
            if (!empty($eval['qpstat']) AND $eval['qpstat'] === '000') {
                if ($betaling->setStatus('cancelled')) {
                    $historik = new VIH_Model_Historik($betaling->get('belong_to'), $betaling->get('belong_to_id'));
                    $historik->save(array('type' => 'dankort', 'comment' => 'Reversal transaktion #' . $betaling->get('transactionnumber')));
                }
                return new k_SeeOther($this->context->url('../'));
            } else {
                // An error occured with the capture
                // Dumping return data for debugging
                /*
                echo "<pre>";
                var_dump($eval);
                echo "</pre>";
                */

                $historik = new VIH_Model_Historik($betaling->get('belong_to'), $betaling->get('belong_to_id'));
                $historik->save(array('type' => 'dankort', 'comment' => 'Fejl ved reverse af transaktion #' . $betaling->get('transactionnumber')));

                $betaling->setStatus('invalid');

                trigger_error('Betalingen kunne ikke annulleres, formentlig fordi den er ugyldig', E_USER_ERROR);
            }
        } else {
            trigger_error('Der var en kommunikationsfejl med Onlinebetalingen', E_ERROR);
        }

    }

}
