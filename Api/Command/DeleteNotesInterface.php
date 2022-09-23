<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Api\Command;

use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;

/**
 * @api
 */
interface DeleteNotesInterface
{
    /**
     * Delete Multiple Note
     *
     * @param NoteDataInterface[] $notes
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(array $notes): void;
}
