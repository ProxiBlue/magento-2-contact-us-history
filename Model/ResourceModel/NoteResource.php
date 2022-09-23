<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;

class NoteResource extends AbstractDb
{
    /**
     * Table name
     */
    const TABLE_NAME_NOTES = 'vb_contact_us_history';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME_NOTES, NoteDataInterface::NOTE_ID);
    }
}
