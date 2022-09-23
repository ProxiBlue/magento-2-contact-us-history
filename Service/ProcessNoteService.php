<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Service;

use VitaliyBoyko\ContactUsHistory\Api\Command\SaveNotesInterface;
use VitaliyBoyko\ContactUsHistory\Mapper\NoteDataPostMapper;

/**
 * Note processor
 */
class ProcessNoteService
{
    public function __construct(private readonly SaveNotesInterface $notesSave, private readonly NoteDataPostMapper $noteDataPostMapper)
    {
    }

    /**
     * Save Note Post
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Validation\ValidationException
     */
    public function execute(): void
    {
        $note = $this->noteDataPostMapper->map();
        $this->notesSave->execute([$note]);
    }
}
