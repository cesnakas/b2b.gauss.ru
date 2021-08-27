<?php

namespace Plan;

class Sort
{
    protected $field;
    protected $dir;

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return mixed
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param mixed $dir
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
    }

    public function __construct($field, $dir)
    {
        $this->field = $field;
        $this->dir = $dir;
    }
}