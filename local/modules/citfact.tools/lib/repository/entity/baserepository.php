<?php

namespace Citfact\Tools\Repository\Entity;

use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DeleteResult;
use Bitrix\Main\Entity\UpdateResult;
use Bitrix\Main\Entity\AddResult;
use Citfact\Tools\Repository\Criteria\CriteriaInterface;
use Citfact\Tools\Repository\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface, EntityRepositoryInterface
{
    /**
     * @var CriteriaInterface[]
     */
    protected $criteria;

    /**
     * @var DataManager
     */
    protected $entity;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * @var array
     */
    private $parameters = array();


    /**
     * @var integer
     */
    private $totalCount = 0;

    /**
     * Construct object
     */
    public function __construct()
    {
        $this->makeEntity();
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @throws \Exception
     */
    public function resetEntity()
    {
        $this->makeEntity();
    }

    public function getPrimaryKey()
    {
        return 'ID';
    }

    /**
     * @throws \Exception
     */
    public function makeEntity()
    {
        $entity = $this->getEntity();

        if (!$entity instanceof DataManager) {
            throw new \Exception('Class must be an instance of Bitrix\Main\Entity\DataManager');
        }

        $this->entity = $entity;
        $this->parameters = array();
    }

    /**
     * Retrieve all data of repository
     *
     * @param  array $columns
     * @return mixed
     */
    public function all($columns = array())
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }

        $result = $this->entity
            ->getList($parameters)
            ->fetchAll();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by id
     *
     * @param $id
     * @param  array $columns
     * @throws \Exception
     * @return mixed
     */
    public function find($id, $columns = array())
    {
        $this->applyCriteria();

        $primary = $this->entity->getEntity()->getPrimaryArray();
        if (empty($primary)) {
            throw new \Exception('Entity not found primary field');
        }

        $primary = array_shift($primary);
        $parameters = $this->getParameters();
        $parameters['filter'][sprintf('=%s', $primary)] = $id;

        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }

        $result = $this->entity
            ->getList($parameters)
            ->fetch();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by field and value
     *
     * @param $field
     * @param $value
     * @param  array $columns
     * @return mixed
     */
    public function findByField($field, $value, $columns = array())
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }
        $parameters['filter'][$field] = $value;
        $result = $this->entity
            ->getList($parameters)
            ->fetchAll();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by multiple fields
     *
     * @param  array $where
     * @param  array $columns
     * @param  array $order
     * @param int $offset
     * @param  int $limit
     * @return mixed
     */
    public function findWhere(array $where, $columns = array(), $order = array(), $offset = 0, $limit = 0)
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        $parameters['filter'] = array_merge(
            (array)$parameters['filter'],
            (array)$where
        );

        $primaryKey = $this->getPrimaryKey();
        $parameters['select'] = [];
        if ($primaryKey) {
            $parameters['select'][] = new ExpressionField('DISTINCT_ID', 'DISTINCT %s', [$primaryKey]);
        }
        if (!empty($columns)) {
            $parameters['select'] = array_merge($parameters['select'], $columns);
        } else {
            $parameters['select'][] = '*';
        }
        if (!empty($order)) {
            $parameters['order'] = $order;
        }

        if ($limit > 0) {
            $parameters['limit'] = $limit;
        }

        if ($offset > 0) {
            $parameters['offset'] = $offset;
        }

        $parameters['count_total'] = true;
        $res = $this->entity
            ->getList($parameters);

        try {
            $this->totalCount = $res->getCount();
        } catch (\Exception $exception) {

        }

        $result = $res->fetchAll();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by multiple fields
     *
     * @param  array $where
     * @param  array $columns
     * @param  array $order
     * @return mixed
     */
    public function findOneWhere(array $where, $columns = array(), $order = array())
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        $parameters['filter'] = array_merge(
            (array)$parameters['filter'],
            (array)$where
        );

        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }
        if (!empty($order)) {
            $parameters['order'] = $order;
        }

        $result = $this->entity
            ->getList($parameters)
            ->fetch();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by multiple values in one field
     *
     * @param $field
     * @param  array $values
     * @param  array $columns
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = array('*'))
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }

        $parameters['filter'][$field] = $values;
        $result = $this->entity
            ->getList($parameters)
            ->fetchAll();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by excluding multiple values in one field
     *
     * @param $field
     * @param  array $values
     * @param  array $columns
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = array('*'))
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }

        $parameters['filter'][sprintf('!=%s', $field)] = $values;
        $result = $this->entity
            ->getList($parameters)
            ->fetchAll();

        $this->resetEntity();

        return $result;
    }

    /**
     * Push Criteria for filter the query
     *
     * @param  CriteriaInterface $criteria
     * @return $this
     */
    public function pushCriteria(CriteriaInterface $criteria)
    {
        $this->criteria[] = $criteria;
        return $this;
    }

    /**
     * Resets Criteria
     *
     * @return $this
     */
    public function resetCriteria()
    {
        $this->criteria = array();

        return $this;
    }

    /**
     * Apply criteria in current Query
     * @throws \Exception
     * @return $this
     */
    protected function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            $this->parameters = array();
            return $this;
        }

        $criteriaList = $this->getCriteria();
        if (!sizeof($criteriaList)) {
            return $this;
        }

        foreach ($criteriaList as $criteria) {
            if ($criteria instanceof CriteriaInterface) {
                $this->parameters = $criteria->apply($this->parameters, $this);
            }
        }

        $defaultParams = $this->getDefaultParameters();
        foreach ($this->parameters as $param => $value) {
            if (!array_key_exists($param, $defaultParams)) {
                throw new \Exception(sprintf('Criteria return invalid parameter %s', $param));
            }
        }

        return $this;
    }

    /**
     * @param  array $attributes
     * @param  array|int $primary
     * @return UpdateResult
     */
    public function update($primary, array $attributes)
    {
        return $this->entity->update($primary, $attributes);
    }

    /**
     * @param  array $attributes
     * @return AddResult
     */
    public function add(array $attributes)
    {
        return $this->entity->add($attributes);
    }

    /**
     * @param  array|int $primary
     * @return DeleteResult
     */
    public function delete($primary)
    {
        return $this->entity->delete($primary);
    }

    /**
     * Get Collection of Criteria
     *
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @return array
     */
    private function getDefaultParameters()
    {
        return array(
            'select' => array(),
            'filter' => array(),
            'group' => array(),
            'order' => array(),
            'limit' => null,
            'offset' => null,
            'count_total' => null,
            'runtime' => array(),
            'data_doubling' => false,
        );
    }

    /**
     * @param  string $type
     * @throws \Exception
     * @return mixed
     */
    protected function getParameterByType($type)
    {
        $defaultParams = $this->getDefaultParameters();
        if (!array_key_exists($type, $defaultParams)) {
            throw new \Exception('Unknown parameter used: ' . implode(',', array_keys($defaultParams)));
        }

        return $this->parameters[$type] ?: $defaultParams[$type];
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Skip Criteria
     *
     * @param  bool $status
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * @param  array $filter
     * @return int
     */
    public function getCountFind($filter = array())
    {
        $this->applyCriteria();

        $queryBuilder = $this->entity->query();
        $queryBuilder->enableDataDoubling();

        $primaryKey = $this->getPrimaryKey();
        $builderSelect = ($primaryKey)
            ? [
                new ExpressionField('CNT', 'COUNT(DISTINCT %s)', [$primaryKey]),
            ]
            : [];
        if (empty($filter)) {
            $filter = $this->getParameterByType('filter');
        }

        $maxRecord = $queryBuilder->setSelect($builderSelect)
            ->setFilter($filter)
            ->exec()
            ->fetch();

        $this->resetEntity();

        return $maxRecord['CNT'];
    }

    public function arrangeByKey($data, $key)
    {
        if (!$data || !$key) {
            return $data;
        }
        $result = [];
        foreach ($data as $item) {
            if (!$item[$key]) {
                continue;
            }
            $result[$item[$key]] = $item;
        }
        return $result;
    }
}
