<?php

namespace Citfact\Tools\Repository\Criteria;

use Bitrix\Main\Application;
use Citfact\Tools\Repository\RepositoryInterface;

abstract class BaseCriteria implements CriteriaInterface
{

    /**
     * @var \Bitrix\Main\HttpRequest
     */
    protected $request;

    /**
     * @var int
     */
    protected $limit = 10;

    /**
     * @var array
     */
    protected $filter = array();

    /**
     * @var array
     */
    protected $select = array();

    /**
     * @var array
     */
    protected $order = array();

    /**
     * @var string
     */
    protected $paginationCode;

    /**
     * BaseCriteria constructor.
     * @param $paginationCode
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct($paginationCode = '')
    {
        $this->request = Application::getInstance()->getContext()->getRequest();
        $this->paginationCode = ($paginationCode) ?: 'PAGEN_1';
    }

    /**
     * @param  int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = (int)$limit;

        return $this;
    }

    /**
     * @param  array $select
     * @return $this
     */
    public function setSelect($select)
    {
        $this->select = $select;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->request->getQuery($this->paginationCode) ?: 1;
    }

    /**
     * @param  string $definition
     * @param  string $order
     * @return $this
     */
    public function addOrder($definition, $order = 'ASC')
    {
        $this->order[$definition] = $order;

        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed $filter
     * @return $this
     */
    public function setFilterKey($key, $filter)
    {
        $this->filter[$key] = $filter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(array $parameters, RepositoryInterface $repository)
    {
        $requestData = $this->request->getPostList()->toArray();
        $parameters['filter'] = array_merge(
            $this->filter,
            $this->getFilterByRequestData($requestData)
        );
        if ($this->select) {
            $parameters['select'] = $this->select;
        }

        $parameters['offset'] = ($this->getCurrentPage() - 1) * $this->limit;
        $parameters['limit'] = $this->limit;
        $parameters['order'] = $this->order;

        return $parameters;
    }
}
