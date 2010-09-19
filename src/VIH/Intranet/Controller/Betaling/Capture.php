<?php
/**
 * Form�let med denne side er at h�ve (capture) betalinger lavet med Dankort.
 *
 * @author Lars Olesen <lars@legestue.net>
 */
class VIH_Intranet_Controller_Betaling_Capture extends k_Component
{
    function renderHtml()
    {
        $betaling = new VIH_Model_Betaling($this->context->name());

        $onlinebetaling = new VIH_Onlinebetaling('capture');
        $eval = $onlinebetaling->capture($betaling->get('transactionnumber'), (int)$betaling->get('amount')*100);

        if ($eval) {
            if (!empty($eval['qpstat']) AND $eval['qpstat'] === '000') {
                if ($betaling->setStatus('approved')) {
                    $historik = new VIH_Model_Historik($betaling->get('belong_to'), $betaling->get('belong_to_id'));
                    $historik->save(array('type' => 'dankort', 'comment' => 'Capture transaktion #' . $betaling->get('transactionnumber')));
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
                $historik->save(array('type' => 'dankort', 'comment' => 'Fejl ved capture af transaktion #' . $betaling->get('transactionnumber')));

                throw new Exception('Betalingen kunne ikke hæves, formentlig fordi den er ugyldig');
            }
        } else {
            throw new Exception('Der var en kommunikationsfejl med Onlinebetalingen');
        }

        return 'error';

    }

}
