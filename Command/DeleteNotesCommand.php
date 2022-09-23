<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Command;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\InputException;
use VitaliyBoyko\ContactUsHistory\Api\Command\DeleteNotesInterface;
use Psr\Log\LoggerInterface;
use VitaliyBoyko\ContactUsHistory\Command\Resource\DeleteNotes;

/**
 * @inheritdoc
 */
class DeleteNotesCommand implements DeleteNotesInterface
{
    public function __construct(private readonly LoggerInterface $logger, private readonly DeleteNotes $deleteNotes)
    {
    }

    /**
     * @inheritdoc
     */
    public function execute(array $notes): void
    {
        if (empty($notes)) {
            throw new InputException(__('Input data is empty'));
        }
        try {
            $this->deleteNotes->execute($notes);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotDeleteException(__('Could not delete notes'), $e);
        }
    }
}
