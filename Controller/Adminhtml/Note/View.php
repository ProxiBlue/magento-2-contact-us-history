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
    const ADMIN_RESOURCE = 'VitaliyBoyko_ContactUsHistory::note';

    /**
     * @var GetNoteByIdInterface
     */
    private $getNoteById;

    /**
     * @param Action\Context $context
     * @param GetNoteByIdInterface $getNoteById
     */
    public function __construct(
        Action\Context $context,
        GetNoteByIdInterface $getNoteById
    ) {
        parent::__construct($context);
        $this->getNoteById = $getNoteById;
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
        } catch (NoSuchEntityException $e) {
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
