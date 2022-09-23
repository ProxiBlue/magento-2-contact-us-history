<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Mapper;

use Magento\Framework\Api\DataObjectHelper;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterfaceFactory;
use VitaliyBoyko\ContactUsHistory\Model\NoteModel;
use VitaliyBoyko\ContactUsHistory\Model\ResourceModel\Note\NoteCollection;

/**
 * Class NoteDataMapper
 * Transfers data from NoteModel's to NoteData's
 */
class NotesDataMapper
{
    public function __construct(private readonly NoteDataInterfaceFactory $noteDataInterfaceFactory, private readonly DataObjectHelper $dataObjectHelper)
    {
    }

    /**
     * Map data models
     *
     * @return NoteDataInterface[]
     */
    public function map(NoteCollection $noteCollection): array
    {
        $noteModels = $noteCollection->getItems();
        $noteDataObjects = [];
        foreach ($noteModels as $noteModel) {
            /** @var NoteModel $noteDataObject */
            $noteDataObject = $this->noteDataInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $noteDataObject,
                $noteModel->getData(),
                NoteDataInterface::class
            );
            $noteDataObjects[] = $noteDataObject;
        }

        return $noteDataObjects;
    }
}
