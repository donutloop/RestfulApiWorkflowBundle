<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Donutloop\RestfulApiWorkflowBundle\Library;

use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class TraitWorkflowUtility
 * @package Donutloop\RestfulApiWorkflowBundle\Library
 */
trait TraitWorkflowUtility
{
    /**
     * @param $qb
     * @param string $route
     * @param array $sort
     * @param array $filter
     * @param int $page
     * @param int $limit
     * @param array $extraParams
     * @return PaginatedRepresentation
     */
    public function generatePaginateCollection($qb, string $route, array $sort = [], array $filter = [], int $page = 1, int $limit = 25, array $extraParams = []): PaginatedRepresentation
    {
        $pagerAdapter = new DoctrineORMAdapter($qb);

        $pager = new Pagerfanta($pagerAdapter);

        $pager->setMaxPerPage($limit);

        $pager->setCurrentPage($page);
        
        $pagerFactory = new PagerfantaFactory();

        $routeParam = [
            'limit' => $limit,
            'page' => $pager,
            'sort' => $sort,
            'filter' => $filter
        ];

        if (0 !== count($extraParams)) {
            $routeParam = array_merge($routeParam, $extraParams);
        }

        return $pagerFactory->createRepresentation($pager, new Route($route, $routeParam));
    }
}