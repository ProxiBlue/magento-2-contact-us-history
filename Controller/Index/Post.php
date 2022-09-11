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

    /**
     * @param Context $context
     * @param ConfigInterface $contactsConfig
     * @param MailInterface $mail
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     * @param ProcessNoteService $noteProcessor
     */
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
        try {
            $params = $this->_request->getParams();
            if(!isset($params['telephone_preferred'])) {
                $params['telephone_preferred'] = 'No';
            }
            $params['form_data'] = $params;
            $this->sendEmail($params);
            $this->noteProcessor->execute();
            $this->messageManager->addSuccessMessage(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
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
        return $this->resultRedirectFactory->create()->setPath('contact/index');
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
