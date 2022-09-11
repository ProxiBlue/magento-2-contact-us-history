<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Query;

use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use VitaliyBoyko\ContactUsHistory\Api\Query\GetNotesListInterface;
use VitaliyBoyko\ContactUsHistory\Mapper\NotesDataMapper;
use VitaliyBoyko\ContactUsHistory\Model\ResourceModel\Note\NoteCollection;
use VitaliyBoyko\ContactUsHistory\Model\ResourceModel\Note\NoteCollectionFactory;

/**
 * @inheritdoc
 */
class GetNotesList implements GetNotesListInterface
{
    /**
     * @param NoteCollectionFactory $noteCollectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(private readonly CollectionProcessorInterface $collectionProcessor, private readonly NoteCollectionFactory $noteCollectionFactory, private readonly SearchResultsInterfaceFactory $searchResultsFactory, private readonly NotesDataMapper $noteDataMapper)
    {
    }

    /**
     * @inheritdoc
     */
    public function execute(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var NoteCollection $collection */
        $collection = $this->noteCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $noteDataObjects = $this->noteDataMapper->map($collection);

        $searchResult->setItems($noteDataObjects);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
