<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Ui\DataProvider;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;
use VitaliyBoyko\ContactUsHistory\Api\Query\GetNotesListInterface;
use Magento\Ui\DataProvider\SearchResultFactory;

class NoteDataProvider extends DataProvider
{
    /**
     * NoteDataProvider constructor.
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        private readonly GetNotesListInterface $getNotesList,
        private readonly SearchResultFactory $searchResultFactory,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchResult()
    {
        $searchCriteria = $this->getSearchCriteria();
        $result = $this->getNotesList->execute($searchCriteria);

        $searchResult = $this->searchResultFactory->create(
            $result->getItems(),
            $result->getTotalCount(),
            $searchCriteria,
            NoteDataInterface::NOTE_ID
        );
        foreach ($searchResult->getItems() as $item) {
            $formData = $item->getCustomAttribute('form_data');
            $formDataValue = json_decode($formData->getValue(), null, 512, JSON_THROW_ON_ERROR);
            if(is_object($formDataValue)) {
                $formDataValue = (array)$formDataValue;
                if(isset($formDataValue['form_data'])) {
                    $formDataValue = $formDataValue['form_data'];
                }
                $textDisplay = '';
                foreach ($formDataValue as $key => $dataValue) {
                    $textDisplay .= $key . ': ' . $dataValue . "\n";
                }
                $formData->setValue($textDisplay);
                $item->setCustomAttribute('form_data', $formData);
            }
        }
        return $searchResult;
    }
}
