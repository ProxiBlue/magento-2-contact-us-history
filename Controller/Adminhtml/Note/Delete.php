<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Controller\Adminhtml\Note;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use VitaliyBoyko\ContactUsHistory\Api\Command\DeleteNotesInterface;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;
use VitaliyBoyko\ContactUsHistory\Api\Query\GetNoteByIdInterface;

/**
 * @inheritdoc
 */
class Delete extends Action
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'VitaliyBoyko_ContactUsHistory::note';

    /**
     * @var GetNoteByIdInterface
     */
    private $getNotesList;

    /**
     * @var DeleteNotesInterface
     */
    private $notesDelete;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param GetNoteByIdInterface $getNotesList
     * @param DeleteNotesInterface $notesDelete
     */
    public function __construct(
        Action\Context $context,
        GetNoteByIdInterface $getNotesList,
        DeleteNotesInterface $notesDelete
    ) {
        parent::__construct($context);
        $this->getNotesList = $getNotesList;
        $this->notesDelete = $notesDelete;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $noteId = (int)$this->getRequest()->getParam(NoteDataInterface::NOTE_ID);
        if ($noteId === null) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));
            return $resultRedirect->setPath('*/*');
        }

        try {
            $note = $this->getNotesList->execute($noteId);
            $this->notesDelete->execute([$note]);
            $this->messageManager->addSuccessMessage(__('The Note has been deleted.'));
            $resultRedirect->setPath('contactus/index/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect->setPath('contactus/note/view', [
                NoteDataInterface::NOTE_ID => $noteId,
                '_current' => true,
            ]);
        }

        return $resultRedirect;
    }
}
