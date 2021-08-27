<?php

namespace Citfact\Tools\UserField;


class UserFieldEnumRepository
{
    /**
     * @param  string $userFieldObject
     * @return array
     */
    public function getUserFieldEnumByObjects($userFieldObject)
    {
        $userFieldEnum = new UserFieldEnumTable();
        $result = array();
        $filter = array(
            'USER_FIELD.ENTITY_ID' => $userFieldObject,
        );

        $queryBuilder = $userFieldEnum->query();
        $res = $queryBuilder->setSelect(array('ID', 'VALUE', 'NAME' => 'USER_FIELD.FIELD_NAME', 'XML_ID'))
            ->setFilter($filter)
            ->setOrder(array('SORT' => 'ASC'))
            ->exec();

        while ($item = $res->fetch()) {
            $result[$item['NAME']][$item['ID']] = $item;
        }

        return $result;
    }

    /**
     * @param  string $userFieldName
     * @param  string $userFieldObject
     * @param  string $key
     * @return array
     */
    public function getUserFieldEnumByName($userFieldName, $userFieldObject, $key = 'ID')
    {
        $userFieldEnum = new UserFieldEnumTable();

        $filter = array(
            'USER_FIELD.ENTITY_ID' => $userFieldObject,
            'USER_FIELD.FIELD_NAME' => $userFieldName,
        );

        $queryBuilder = $userFieldEnum->query();
        $res = $queryBuilder->setSelect(array('ID', 'VALUE', 'XML_ID'))
            ->setOrder(array('SORT' => 'ASC'))
            ->setFilter($filter)
            ->exec();

        $result = array();
        while ($item = $res->fetch()) {
            $item['NAME']=$item['VALUE'];
            $result[$item[$key]] = $item;
        }

        return $result;
    }

    /**
     * @param  string $userFieldXmlId
     * @param  string $userFieldName
     * @param  string $userFieldObject
     * @return int
     */
    public function getEnumValue($userFieldXmlId, $userFieldName, $userFieldObject)
    {
        $userFieldEnum = new UserFieldEnumTable();

        $filter = array(
            'XML_ID' => $userFieldXmlId,
            'USER_FIELD.ENTITY_ID' => $userFieldObject,
            'USER_FIELD.FIELD_NAME' => $userFieldName,
        );

        $queryBuilder = $userFieldEnum->query();
        $res = $queryBuilder->setSelect(array('ID', 'VALUE', 'XML_ID'))
            ->setFilter($filter)
            ->exec();

        if ($item = $res->fetch()) {
            return $item['ID'];
        }

        return 0;
    }

    /**
     * @param  int $userFieldValue
     * @param  string $userFieldName
     * @param  string $userFieldObject
     * @return int
     */
    public function getEnumXmlId($userFieldValue, $userFieldName, $userFieldObject)
    {
        $userFieldEnum = new UserFieldEnumTable();

        $filter = array(
            'ID' => $userFieldValue,
            'USER_FIELD.ENTITY_ID' => $userFieldObject,
            'USER_FIELD.FIELD_NAME' => $userFieldName,
        );

        $queryBuilder = $userFieldEnum->query();
        $res = $queryBuilder->setSelect(array('ID', 'VALUE', 'XML_ID'))
            ->setFilter($filter)
            ->exec();

        if ($item = $res->fetch()) {
            return $item['XML_ID'];
        }

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return new UserFieldEnumTable();
    }
}
