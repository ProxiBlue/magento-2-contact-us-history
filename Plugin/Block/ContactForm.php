<?php

declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Plugin\Block;

use \Magento\Framework\App\RequestInterface;


class ContactForm
{

    public function __construct(protected RequestInterface $request)
    {
    }

    public function beforeSetTemplate(\Magento\Contact\Block\ContactForm $subject, $param)
    {
        $params = $this->getParams();
        if(isset($params['form'])) {
            return 'Magento_Contact::'.$params['form'].'.phtml';
        }
        return $param;
    }

    public function getParams()
    {
        return $this->request->getParams();
    }
}
