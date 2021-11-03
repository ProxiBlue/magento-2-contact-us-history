<?php

declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Plugin\Block;

use \Magento\Framework\App\RequestInterface;


class ContactForm
{

    protected $request;

    public function __construct(
        RequestInterface $request
    ) {
       $this->request = $request;
    }

    /**
     * Set an alternative template to use in place of the default, based on url param: form
     * The mapping will be best presented with an admin config option
     *
     * @param \Magento\Contact\Block\ContactForm $subject
     * @param $param
     * @return mixed|string
     */
    public function beforeSetTemplate(\Magento\Contact\Block\ContactForm $subject, $param)
    {
        $params = $this->getParams();
        if(isset($params['form']) && $subject->getNameInLayout() != 'footer-form') {
            $params['form'] = str_replace('/','', $params['form']);
            $parts = explode('-', $params['form']);
            $title = implode(' ', $parts);
            $subject->setData('title_override', $title);
            return 'Magento_Contact::'.$params['form'].'.phtml';
        }
        return $param;
    }

    public function getParams()
    {
        return $this->request->getParams();
    }


}
