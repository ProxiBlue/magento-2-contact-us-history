<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Command\Resource;

use Magento\Framework\App\ResourceConnection;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;
use VitaliyBoyko\ContactUsHistory\Model\ResourceModel\NoteResource;

/**
 * Implementation of Notes delete multiple operation for specific db layer
 */
class DeleteNotes
{
    public function __construct(private readonly ResourceConnection $resourceConnection)
    {
    }

    /**
     * Multiple delete notes
     *
     * @param NoteDataInterface[] $notes
     */
    public function execute(array $notes): void
    {
        if (!count($notes)) {
            return;
        }
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName(NoteResource::TABLE_NAME_NOTES);

        $whereSql = $this->buildWhereSqlPart($notes);
        $connection->delete($tableName, $whereSql);
    }

    /**
     * @param NoteDataInterface[] $notes
     */
    private function buildWhereSqlPart(array $notes): string
    {
        $connection = $this->resourceConnection->getConnection();

        $condition = [];
        foreach ($notes as $note) {
            $condition[] = $connection->quoteInto(
                NoteDataInterface::NOTE_ID . ' = ?',
                $note->getNoteId()
            );
        }
        return implode(' OR ', $condition);
    }
}
