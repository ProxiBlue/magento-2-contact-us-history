<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Controller\Adminhtml\Note;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\NoSuchEntityException;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;
use VitaliyBoyko\ContactUsHistory\Api\Query\GetNoteByIdInterface;

/**
 * @inheritdoc
 */
class View extends Action
{
    /**
     * @see _isAllowed()
     */
    final const ADMIN_RESOURCE = 'VitaliyBoyko_ContactUsHistory::note';

    public function __construct(
        Action\Context $context,
        private readonly GetNoteByIdInterface $getNoteById
    ) {
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        $noteId = (int)$this->getRequest()->getParam(NoteDataInterface::NOTE_ID);
        try {
            $note = $this->getNoteById->execute($noteId);

            /** @var Page $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $result->setActiveMenu('VitaliyBoyko_ContactUsHistory::note')
                ->addBreadcrumb(__('View Note'), __('View Note'));
            $result->getConfig()
                ->getTitle()
                ->prepend(__('View Note from %name', ['name' => $note->getContactName()]));
        } catch (NoSuchEntityException) {
            /** @var Redirect $result */
            $result = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(
                __('Note with id "%value" does not exist.', ['value' => $noteId])
            );
            $result->setPath('contactus/index/index');
        }

        return $result;
    }
}
