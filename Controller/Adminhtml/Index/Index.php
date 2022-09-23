<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'VitaliyBoyko_ContactUsHistory::note';

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('VitaliyBoyko_ContactUsHistory::note')
            ->addBreadcrumb(__('Notes'), __('List'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Contact Form Entries'));

        return $resultPage;
    }
}
