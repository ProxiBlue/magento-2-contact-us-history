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
     * @var GetNotesListInterface
     */
    private $getNotesList;

    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;

    /**
     * NoteDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param GetNotesListInterface $getNotesList
     * @param SearchResultFactory $searchResultFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        GetNotesListInterface $getNotesList,
        SearchResultFactory $searchResultFactory,
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
        $this->searchResultFactory = $searchResultFactory;
        $this->getNotesList = $getNotesList;
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
            $formDataValue = json_decode($formData->getValue());
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
