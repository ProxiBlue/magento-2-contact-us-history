<?php

namespace VitaliyBoyko\ContactUsHistory\Block;


class ContactForm extends \Magento\Contact\Block\ContactForm
{

    /**
     * Override title, which is set in plugin
     * @see VitaliyBoyko\ContactUsHistory\Plugin\Block\ContactForm
     * @return ContactForm
     */
    protected function _prepareLayout() {
        $title = $this->getData('title_override');
        if(!empty($title)) {
            $this->pageConfig->getTitle()->set(__($title));
        }
        return parent::_prepareLayout();
    }
}
