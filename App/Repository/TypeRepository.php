<?php

namespace App\Repository;

use App\Entity\Type;
use App\Db\Mysql;

class TypeRepository extends Repository
{

    public function findOneById(int $id): Type|bool
    {

        $query = $this->pdo->prepare('SELECT * FROM type WHERE id = :id');
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $query->execute();
        $type = $query->fetch($this->pdo::FETCH_ASSOC);
        if ($type) {
            return Type::createAndHydrate($type);
        } else {
            return false;
        }
    }

    public function findAll():array
    {
        $query = $this->pdo->prepare("SELECT * FROM type ORDER BY id ASC");
        $query->execute();
        $types = $query->fetchAll($this->pdo::FETCH_ASSOC);

        $typesArray = [];
        if (!empty($types)) {
            foreach($types as $type) {
                $typesArray[] = Type::createAndHydrate($type);

            }
        }

        return $typesArray;

    }
}