<?php

namespace Citfact\Tools\Repository\Criteria;


use Citfact\Tools\Repository\RepositoryInterface;

interface CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param  array $parameters
     * @param  RepositoryInterface $repository
     * @return array
     */
    public function apply(array $parameters, RepositoryInterface $repository);

    /**
     * @param array $requestData
     * @return mixed
     */
    public function getFilterByRequestData(array $requestData);
}
