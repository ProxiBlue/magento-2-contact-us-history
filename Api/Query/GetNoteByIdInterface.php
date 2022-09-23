<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Api\Query;

use Magento\Framework\Exception\NoSuchEntityException;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;

/**
 * Returns Note by entity id
 * @api
 */
interface GetNoteByIdInterface
{
    /**
     * @param int $noteId
     * @return NoteDataInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $noteId): NoteDataInterface;
}
