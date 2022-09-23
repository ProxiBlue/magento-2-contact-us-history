<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Controller\Adminhtml\Note;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use VitaliyBoyko\ContactUsHistory\Api\Command\DeleteNotesInterface;
use VitaliyBoyko\ContactUsHistory\Mapper\NotesDataMapper;
use VitaliyBoyko\ContactUsHistory\Model\ResourceModel\Note\NoteCollectionFactory;

/**
 * @inheritdoc
 */
class MassDelete extends Action
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'VitaliyBoyko_ContactUsHistory::note';

    /**
     * @var Filter
     */
    private $massActionFilter;

    /**
     * @var DeleteNotesInterface
     */
    private $notesDelete;

    /**
     * @var NotesDataMapper
     */
    private $notesDataMapper;

    /**
     * @var NoteCollectionFactory
     */
    private $noteCollectionFactory;

    /**
     * @param Context $context
     * @param DeleteNotesInterface $notesDelete
     * @param Filter $massActionFilter
     * @param NotesDataMapper $notesDataMapper
     * @param NoteCollectionFactory $noteCollectionFactory
     */
    public function __construct(
        Context $context,
        DeleteNotesInterface $notesDelete,
        Filter $massActionFilter,
        NotesDataMapper $notesDataMapper,
        NoteCollectionFactory $noteCollectionFactory
    ) {
        parent::__construct($context);
        $this->massActionFilter = $massActionFilter;
        $this->notesDelete = $notesDelete;
        $this->notesDataMapper = $notesDataMapper;
        $this->noteCollectionFactory = $noteCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        if ($this->getRequest()->isPost() !== true) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));

            return $this->resultRedirectFactory->create()->setPath('contactus/index/index');
        }

        $collection = $this->massActionFilter->getCollection($this->noteCollectionFactory->create());
        $notes = $this->notesDataMapper->map($collection);
        $this->notesDelete->execute($notes);
        $this->messageManager->addSuccessMessage(__('You deleted %1 Note(s).', count($notes)));

        return $this->resultRedirectFactory->create()->setPath('contactus/index/index');
    }
}
