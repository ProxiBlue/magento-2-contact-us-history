<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Model\ResourceModel\Note;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;
use VitaliyBoyko\ContactUsHistory\Model\NoteModel;
use VitaliyBoyko\ContactUsHistory\Model\ResourceModel\NoteResource;

/**
 * @inheritdoc
 */
class NoteCollection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(NoteModel::class, NoteResource::class);
        $this->_setIdFieldName(NoteDataInterface::NOTE_ID);
    }
}
