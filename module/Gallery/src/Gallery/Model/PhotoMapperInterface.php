<?php

namespace Gallery\Model;

use Gallery\Model\PhotoInterface;

interface PhotoMapperInterface
{
    public function persist(PhotoInterface $photo);

    public function findById($id);
}