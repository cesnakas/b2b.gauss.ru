<?php

namespace Citfact\Tools\Repository;

interface RepositoryInterface
{
    /**
     * Retrieve all data of repository
     *
     * @param  array $columns
     * @return mixed
     */
    public function all($columns = array('*'));

    /**
     * Find data by id
     *
     * @param $id
     * @param  array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'));

    /**
     * Find data by field and value
     *
     * @param $field
     * @param $value
     * @param  array $columns
     * @return mixed
     */
    public function findByField($field, $value, $columns = array('*'));

    /**
     * Find data by multiple fields
     *
     * @param  array $where
     * @param  array $columns
     * @return mixed
     */
    //public function findWhere( array $where , $columns = array('*'));

    /**
     * Find data by multiple values in one field
     *
     * @param $field
     * @param  array $values
     * @param  array $columns
     * @return mixed
     */
    public function findWhereIn( $field, array $values, $columns = array('*'));

    /**
     * Find data by excluding multiple values in one field
     *
     * @param $field
     * @param  array $values
     * @param  array $columns
     * @return mixed
     */
    public function findWhereNotIn( $field, array $values, $columns = array('*'));
}
