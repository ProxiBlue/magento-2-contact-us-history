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
    final const ADMIN_RESOURCE = 'VitaliyBoyko_ContactUsHistory::note';

    public function __construct(
        Context $context,
        private readonly DeleteNotesInterface $notesDelete,
        private readonly Filter $massActionFilter,
        private readonly NotesDataMapper $notesDataMapper,
        private readonly NoteCollectionFactory $noteCollectionFactory
    ) {
        parent::__construct($context);
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
