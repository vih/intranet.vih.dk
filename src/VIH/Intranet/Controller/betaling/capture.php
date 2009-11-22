<?php
/**
 * Formålet med denne side er at hæve (capture) betalinger lavet med Dankort.
 *
 * @author Lars Olesen <lars@legestue.net>
 */
class VIH_Intranet_Controller_Betaling_Capture extends k_Controller
{
    function GET()
    {
        $betaling = new VIH_Model_Betaling($this->context->name);

        $onlinebetaling = new VIH_Onlinebetaling('capture');
        $eval = $onlinebetaling->capture($betaling->get('transactionnumber'), (int)$betaling->get('amount')*100);

        if ($eval) {
            if (!empty($eval['qpstat']) AND $eval['qpstat'] === '000') {
                if ($betaling->setStatus('approved')) {
                    $historik = new VIH_Model_Historik($betaling->get('belong_to'), $betaling->get('belong_to_id'));
                    $historik->save(array('type' => 'dankort', 'comment' => 'Capture transaktion #' . $betaling->get('transactionnumber')));
                }
                throw new k_http_Redirect($this->context->url('../'));

            } else {
                // An error occured with the capture
                // Dumping return data for debugging
                /*
                echo "<pre>";
                var_dump($eval);
                echo "</pre>";
                */
                $historik = new VIH_Model_Historik($betaling->get('belong_to'), $betaling->get('belong_to_id'));
                $historik->save(array('type' => 'dankort', 'comment' => 'Fejl ved capture af transaktion #' . $betaling->get('transactionnumber')));

                trigger_error('Betalingen kunne ikke hæves, formentlig fordi den er ugyldig', E_USER_ERROR);
            }
        } else {
            trigger_error('Der var en kommunikationsfejl med Onlinebetalingen', E_USER_ERROR);
        }

        return 'error';

    }

}
