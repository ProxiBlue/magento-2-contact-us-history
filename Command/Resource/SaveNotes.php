<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Command\Resource;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;
use VitaliyBoyko\ContactUsHistory\Model\ResourceModel\NoteResource;
use VitaliyBoyko\ContactUsHistory\Service\Validation\ValidateNote;

/**
 * Implementation of Notes save multiple operation for specific db layer
 */
class SaveNotes
{
    public function __construct(private readonly ResourceConnection $resourceConnection, private readonly ValidateNote $validateNote)
    {
    }

    /**
     * Multiple save notes
     *
     * @param NoteDataInterface[] $notes
     * @throws LocalizedException
     */
    public function execute(array $notes): void
    {
        if (!count($notes)) {
            return;
        }
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName(NoteResource::TABLE_NAME_NOTES);
        $columnsSql = $this->buildColumnsSqlPart([
            NoteDataInterface::NOTE_ID,
            NoteDataInterface::PHONE,
            NoteDataInterface::CONTACT_NAME,
            NoteDataInterface::MESSAGE,
            NoteDataInterface::EMAIL,
            NoteDataInterface::CUSTOMER_ID,
            NoteDataInterface::FORM_ID,
            NoteDataInterface::FORM_DATA

        ]);
        $valuesSql = $this->buildValuesSqlPart($notes);
        $onDuplicateSql = $this->buildOnDuplicateSqlPart([
            NoteDataInterface::PHONE,
            NoteDataInterface::CONTACT_NAME,
            NoteDataInterface::MESSAGE,
            NoteDataInterface::EMAIL,
            NoteDataInterface::CUSTOMER_ID,
            NoteDataInterface::FORM_ID,
            NoteDataInterface::FORM_DATA
        ]);
        $bind = $this->getSqlBindData($notes);
        $insertSql = sprintf(
            'INSERT INTO %s (%s) VALUES %s %s',
            $tableName,
            $columnsSql,
            $valuesSql,
            $onDuplicateSql
        );
        $connection->query($insertSql, $bind);
    }

    private function buildColumnsSqlPart(array $columns): string
    {
        $connection = $this->resourceConnection->getConnection();
        $processedColumns = array_map($connection->quoteIdentifier(...), $columns);
        $sql = implode(', ', $processedColumns);
        return $sql;
    }

    /**
     * @param NoteDataInterface[] $notes
     */
    private function buildValuesSqlPart(array $notes): string
    {
        $sql = rtrim(str_repeat('(?, ?, ?, ?, ?, ?, ?, ?), ', count($notes)), ', ');
        return $sql;
    }

    /**
     * @param NoteDataInterface[] $notes
     * @throws LocalizedException
     */
    private function getSqlBindData(array $notes): array
    {
        $bind = [];
        foreach ($notes as $note) {
            $this->validateNote->execute($note);
            $bind = array_merge($bind, [
                $note->getNoteId(),
                $note->getPhone(),
                $note->getContactName(),
                $note->getMessage(),
                $note->getEmail(),
                $note->getCustomerId(),
                $note->getFormId(),
                json_encode($note->getData(), JSON_THROW_ON_ERROR)
            ]);
        }
        return $bind;
    }

    private function buildOnDuplicateSqlPart(array $fields): string
    {
        $connection = $this->resourceConnection->getConnection();
        $processedFields = [];
        foreach ($fields as $field) {
            $processedFields[] = sprintf('%1$s = VALUES(%1$s)', $connection->quoteIdentifier($field));
        }
        $sql = 'ON DUPLICATE KEY UPDATE ' . implode(', ', $processedFields);
        return $sql;
    }
}
