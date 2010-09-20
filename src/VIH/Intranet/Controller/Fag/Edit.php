<?php
class VIH_Intranet_Controller_Fag_Edit extends k_Component
{
    function renderHtml()
    {
        $fag = VIH_Model_Fag::getList();
        foreach($fag AS $f) {
            $faglist[$f->get('id')] = $f->get('navn');
        }

        $fag = new VIH_Model_Fag($this->context->name());

        $underviser_selected = array();

        if ($fag->get('id') > 0) {
            $undervisere = $fag->getUndervisere();
            foreach ($undervisere AS $underviser) {
                $underviser_selected[$underviser->get('id')] = true;
            }
            $defaults = array('id' => $fag->get('id'),
                              'navn' => $fag->get('navn'),
                              'identifier' => $fag->get('identifier'),
                              'title' => $fag->get('title'),
                              'description' => $fag->get('description'),
                              'keywords' => $fag->get('keywords'),
                              'beskrivelse' => $fag->get('beskrivelse'),
                              'kort_beskrivelse' => $fag->get('kort_beskrivelse'),
                              'udvidet_beskrivelse' => $fag->get('udvidet_beskrivelse'),
                              'published' => $fag->get('published'),
                              'faggruppe_id' => $fag->get('faggruppe_id'),
                              'underviser' => $underviser_selected);

            $this->context->context->getForm()->setDefaults($defaults);

        }
        $this->document->setTitle('Rediger fag');

        return $this->context->context->getForm()->toHTML();
    }

    function postForm()
    {
        if ($this->context->context->getForm()->validate()) {
            $fag = new VIH_Model_Fag($this->context->context->name());
            $input = $this->body();
            $input['navn'] = vih_handle_microsoft($input['navn']);
            $input['beskrivelse'] = vih_handle_microsoft($input['beskrivelse']);
            $input['kort_beskrivelse'] = vih_handle_microsoft($input['kort_beskrivelse']);
            $input['udvidet_beskrivelse'] = vih_handle_microsoft($input['udvidet_beskrivelse']);
            if (!isset($input['published'])) {
                $input['published'] = 0;
            }

            if ($id = $fag->save($input)) {
                if ($this->body('underviser')) {
                    $fag->addUnderviser($this->body('underviser'));
                }
                return new k_SeeOther($this->url('/fag/' . $fag->get('id')));
            }
        }
        return $this->render();
    }
}
