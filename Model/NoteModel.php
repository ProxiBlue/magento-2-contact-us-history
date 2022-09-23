<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Model;

use Magento\Framework\Model\AbstractModel;
use VitaliyBoyko\ContactUsHistory\Model\ResourceModel\NoteResource;

/**
 * Note Model
 */
class NoteModel extends AbstractModel
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(NoteResource::class);
    }
}
