<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Api\Query;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface GetNotesListInterface
{
    /**
     * Find Notes by SearchCriteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function execute(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;
}
