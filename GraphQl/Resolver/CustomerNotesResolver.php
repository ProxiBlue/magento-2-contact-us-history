<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\GraphQl\Resolver;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;
use VitaliyBoyko\ContactUsHistory\Api\Query\GetNotesListInterface;

/**
 * @inheritdoc
 */
class CustomerNotesResolver implements ResolverInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var GetNotesListInterface
     */
    private $getNotesList;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param GetNotesListInterface $getNotesList
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        GetNotesListInterface $getNotesList,
        FilterBuilder $filterBuilder
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->getNotesList = $getNotesList;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {


        $this->filterBuilder->setField(NoteDataInterface::CUSTOMER_ID);
        $this->filterBuilder->setConditionType('eq');
        $this->filterBuilder->setValue($context->getUserId());
        $customerFilter = $this->filterBuilder->create();
        $this->searchCriteriaBuilder->addFilter($customerFilter);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchCriteria->setCurrentPage($args['currentPage']);
        $searchCriteria->setPageSize($args['pageSize']);
        $customerNotesResult = $this->getNotesList->execute($searchCriteria);

        $result = [];
        /** @var NoteDataInterface $note */
        foreach ($customerNotesResult->getItems() as $note) {
            $result[] = [
                'contact_name' => $note->getContactName(),
                'email' => $note,
                'message' => $note,
                'phone' => $note,
                'created_date' => $note,
                'form_id' => $note,
            ];
        }

        return $result;
    }
}
