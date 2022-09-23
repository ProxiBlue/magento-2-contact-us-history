<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Command;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Psr\Log\LoggerInterface;
use VitaliyBoyko\ContactUsHistory\Api\Command\SaveNotesInterface;
use VitaliyBoyko\ContactUsHistory\Command\Resource\SaveNotes;

/**
 * @inheritdoc
 */
class SaveNotesCommand implements SaveNotesInterface
{
    public function __construct(private readonly SaveNotes $saveMultiple, private readonly LoggerInterface $logger)
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
            $this->saveMultiple->execute($notes);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotSaveException(__('Could not save notes'), $e);
        }
    }
}
