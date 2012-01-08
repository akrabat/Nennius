<?php

namespace Gallery\Model;


use EdpCommon\Mapper\DbMapperAbstract,
    Gallery\Module;

class PhotoMapper extends DbMapperAbstract implements PhotoMapperInterface
{
    protected $tableName = 'photo';

    public function persist(PhotoInterface $photo)
    {
        $data = $photo->toArray();
        foreach ($data as $key => $value) {
            if ($value instanceof \DateTime) {
                $value->setTimeZone(new \DateTimeZone('UTC'));
                $data[$key] = $value->format('Y-m-d H:i:s');
            }
        }

        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('data' => $data, 'photo' => $photo));
        $db = $this->getWriteAdapter();

        if ($photo->getId() > 0) {
            $db->update($this->getTableName(), (array) $data, $db->quoteInto('id = ?', $photo->getId()));
        } else {
            $db->insert($this->getTableName(), (array) $data);
            $id = $db->lastInsertId();
            $photo->setId($id);
        }

        return $photo;
    }

    public function fetchLatest($count=10)
    {
        $db = $this->getReadAdapter();
        $select = $db->select()
            ->from($this->getTableName())
            ->order('date_created DESC');
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('query' => $select));
        $results = $db->fetchAll($select);

        $rows = array();
        $modelClass = Module::getOption('photo_model_class');
        foreach ($results as $result) {
            $rows[] = $modelClass::fromArray($result);
        }

        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('rows' => $rows));
        return $rows;
    }

    public function findById($id)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('id = ?', (int)$id);
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('query' => $sql));
        $row = $db->fetchRow($sql);
        $userModelClass = Module::getOption('photo_model_class');
        $photo = $userModelClass::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('photo' => $photo, 'row' => $row));
        return $user;
    }
}
