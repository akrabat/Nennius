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
        LDBG($data, 'data');exit;
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('data' => $data, 'photo' => $photo));
        $db = $this->getWriteAdapter();
        LDBG($db);
        if ($photo->getId() > 0) {
            $db->update($this->getTableName(), (array) $data, $db->quoteInto('id = ?', $photo->getId()));
        } else {
            $db->insert($this->getTableName(), (array) $data);
            $id = $db->lastInsertId();
            $photo->setId($id);
        }
LDBGD($photo, 'photo');
        return $photo;
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
