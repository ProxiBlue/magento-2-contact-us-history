<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Controller\Index;

use Magento\Contact\Controller\Index;
use Magento\Contact\Model\ConfigInterface;
use Magento\Contact\Model\MailInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Psr\Log\LoggerInterface;
use VitaliyBoyko\ContactUsHistory\Service\ProcessNoteService;

/**
 * Class Post is custom implementation of Magento\Contact\Controller\Post
 */
class Post extends Index
{
    private readonly \Magento\Framework\App\Action\Context $context;

    public function __construct(
        Context $context,
        ConfigInterface $contactsConfig,
        private readonly MailInterface $mail,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly LoggerInterface $logger,
        private readonly ProcessNoteService $noteProcessor
    ) {
        parent::__construct($context, $contactsConfig);
        $this->context = $context;
    }

    /**
     * Post user question
     *
     * @return Redirect
     */
    public function execute()
    {
        if (!$this->isPostRequest()) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
        $params = $this->_request->getParams();
        if(!isset($params['telephone_preferred'])) {
            $params['telephone_preferred'] = 'No';
        }
        try {
            // trap email send issue seperate so as to not block saving of request
            $this->sendEmail($params);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
        try {
            $this->noteProcessor->execute();
            $this->dataPersistor->clear('contact_us');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('contact_us', $this->getRequest()->getParams());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(
                __('An error occurred while processing your form. Please try again later.')
            );
            $this->dataPersistor->set('contact_us', $this->getRequest()->getParams());
        }
        if(isset($params['redirect_back_to'])) {
            $this->messageManager->addSuccessMessage(
                __('Thanks for the feedback. We\'ll respond to you very soon, if required.')
            );
            return $this->resultRedirectFactory->create()->setUrl(base64_decode($params['redirect_back_to']));
        } elseif(isset($params['form_id']) && $params['form_id'] != 'contact'){
            $this->messageManager->addSuccessMessage(
                __('Your submission has been received. We\'ll respond to you very soon.')
            );
            return $this->resultRedirectFactory->create()->setPath('contact?form=' . $params['form_id']);
        } else {
            $this->messageManager->addSuccessMessage(
                __('Thanks for contacting us. We\'ll respond to you very soon.')
            );
            return $this->resultRedirectFactory->create()->setPath('contact/index');
        }
    }

    /**
     * @param array $post Post data from contact form
     * @return void
     */
    private function sendEmail($post)
    {
        $this->mail->send($post['email'], ['data' => new \Magento\Framework\DataObject($post)]);
    }

    /**
     * @return bool
     */
    private function isPostRequest()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        return !empty($request->getPostValue());
    }
}
