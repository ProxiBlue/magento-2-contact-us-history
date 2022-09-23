<?php

namespace VitaliyBoyko\ContactUsHistory\Model;


use Magento\Contact\Model\ConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Email\Model\BackendTemplate;
use Magento\Framework\App\Area;

class Mail extends \Magento\Contact\Model\Mail
{

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Initialize dependencies.
     *
     * @param StoreManagerInterface|null $storeManager
     */
    public function __construct(
        private readonly ConfigInterface $contactsConfig,
        private readonly TransportBuilder $transportBuilder,
        private readonly StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager = null,
        protected BackendTemplate $emailTemplate
    ) {
        $this->storeManager = $storeManager ?: ObjectManager::getInstance()->get(StoreManagerInterface::class);
    }


    /**
     * Send email from contact form
     *
     * @param string $replyTo
     * @return void
     */
    public function send($replyTo, array $variables)
    {
        /** @see \Magento\Contact\Controller\Index\Post::validatedParams() */
        $replyToName = !empty($variables['data']['name']) ? $variables['data']['name'] : null;

        $formId = $variables['data']->getData('form_id');
        $emailTemplate = $this->emailTemplate->load($formId, 'template_code');
        if(is_object($emailTemplate) && $emailTemplate->getId()){
            $emailTemplate = $emailTemplate->getId();
        } else {
            $emailTemplate = $this->contactsConfig->emailTemplate();
        }
        $this->inlineTranslation->suspend();
        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($emailTemplate)
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId()
                    ]
                )
                ->setTemplateVars($variables)
                ->setFrom($this->contactsConfig->emailSender())
                ->addTo($this->contactsConfig->emailRecipient())
                ->setReplyTo($replyTo, $replyToName)
                ->getTransport();

            $transport->sendMessage();
        } finally {
            $this->inlineTranslation->resume();
        }
    }
}
