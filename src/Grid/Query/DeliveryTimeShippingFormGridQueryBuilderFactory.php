<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Grid\Query;

use Context;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

class DeliveryTimeShippingFormGridQueryBuilderFactory extends AbstractDoctrineQueryBuilder
{
    /**
     * @var DoctrineSearchCriteriaApplicatorInterface
     */
    private $criteriaApplicator;

    /**
     * @param Connection $connection
     * @param string $dbPrefix
     * @param DoctrineSearchCriteriaApplicatorInterface $criteriaApplicator
     */
    public function __construct(
        Connection $connection,
        string $dbPrefix,
        DoctrineSearchCriteriaApplicatorInterface $criteriaApplicator
    )
    {
        parent::__construct($connection, $dbPrefix);
        $this->criteriaApplicator = $criteriaApplicator;
    }

    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getBaseQuery();

        $qb->orderBy(
            $searchCriteria->getOrderBy(),
            $searchCriteria->getOrderWay()
        );

        $this->criteriaApplicator->applyPagination(
            $searchCriteria,
            $qb
        );

        return $qb;
    }

    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getBaseQuery();
        $qb->select('COUNT(DISTINCT wds.id)');

        return $qb;
    }

    private function getBaseQuery(): QueryBuilder
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('wds.*, ca.name as carrier_name')
            ->from($this->dbPrefix.'webo_deliverytime_shipping', 'wds')
            ->leftJoin('wds', $this->dbPrefix .'carrier', 'ca', 'ca.id_carrier = wds.id_carrier AND ca.deleted = 0');
    }
}