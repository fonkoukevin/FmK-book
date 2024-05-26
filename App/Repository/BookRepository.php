<?php

namespace App\Repository;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\TypeRepository;
use PDO;

class BookRepository extends Repository
{




    public function findOneById(int $id): Book|bool
    {

        $query = $this->pdo->prepare('SELECT * FROM book WHERE id = :id');
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $query->execute();
        $book = $query->fetch($this->pdo::FETCH_ASSOC);
        if ($book) {
            $book = Book::createAndHydrate($book);
            // On rajoute auteur
            $authorRepository = new AuthorRepository();
            $author =  $authorRepository->findOneById($book->getAuthorId());
            $book->setAuthor($author);

            // On rajoute type
            $typeRepository = new TypeRepository();
            $type =  $typeRepository->findOneById($book->getTypeId());
            $book->setType($type);

            return $book;
        } else {
            return false;
        }
    }

    public function findAll(int $limit = null, int $page = null, int $typeId = null): array
    {
        $sql = "SELECT * FROM book";
        
        // Ajouter une clause WHERE si un type_id est fourni
        if ($typeId !== null) {
            $sql .= " WHERE type_id = :type_id";
        }
    
        $sql .= " ORDER BY id DESC";
    
        if ($limit !== null) {
            if ($page !== null) {
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            } else {
                $sql .= " LIMIT :limit";
            }
        }
    
        $query = $this->pdo->prepare($sql);
    
        if ($typeId !== null) {
            $query->bindValue(':type_id', $typeId, $this->pdo::PARAM_INT);
        }
    
        if ($limit !== null) {
            $query->bindValue(':limit', $limit, $this->pdo::PARAM_INT);
            if ($page !== null) {
                $query->bindValue(':offset', $offset, $this->pdo::PARAM_INT);
            }
        }
    
        $query->execute();
        $results = $query->fetchAll($this->pdo::FETCH_ASSOC);
        $booksArray = [];
    
        foreach ($results as $result) {
            $book = Book::createAndHydrate($result);
            $booksArray[] = $book;
        }
    
        return $booksArray;
    }
    
    public function count(int $typeId = null): int
    {
        $sql = "SELECT COUNT(*) as total_books FROM book";
    
        if ($typeId !== null) {
            $sql .= " WHERE type_id = :type_id";
        }
    
        $query = $this->pdo->prepare($sql);
    
        if ($typeId !== null) {
            $query->bindValue(':type_id', $typeId, $this->pdo::PARAM_INT);
        }
    
        $query->execute();
        $total = $query->fetch($this->pdo::FETCH_ASSOC);
        
        return $total ? (int)$total['total_books'] : 0;
    }
    
    public function persist(Book $book)
    {

        if ($book->getId() !== null) {
            $query = $this->pdo->prepare(
                'UPDATE book SET title = :title, 
                        description = :description, type_id = :type_id, author_id = :author_id, image = :image WHERE id = :id'
            );
            $query->bindValue(':id', $book->getId(), $this->pdo::PARAM_INT);
        } else {
            $query = $this->pdo->prepare(
                'INSERT INTO book (title, description, type_id, author_id, image) 
                                                    VALUES (:title, :description, :type_id, :author_id, :image)'
            );
        }

        $query->bindValue(':title', $book->getTitle(), $this->pdo::PARAM_STR);
        $query->bindValue(':description', $book->getDescription(), $this->pdo::PARAM_STR);
        $query->bindValue(':type_id', $book->getTypeId(), $this->pdo::PARAM_INT);
        $query->bindValue(':author_id', $book->getAuthorId(), $this->pdo::PARAM_INT);
        $query->bindValue(':image', $book->getImage(), $this->pdo::PARAM_STR);

        $res = $query->execute();

        if ($res) {
            if ($book->getId() == null) {
                $book->setId($this->pdo->lastInsertId());
            }
            return $book;
        } else {
            throw new \Exception("Erreur lors de l'enregistrement");
        }
    }

    public function removeById(int $id)
    {
        $query = $this->pdo->prepare('DELETE FROM book WHERE id = :id');
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function searchByTitleOrDescription(string $searchTerm): array
    {
        $query = $this->pdo->prepare('SELECT * FROM book WHERE title LIKE :searchTerm OR description LIKE :searchTerm');
        $query->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
        $query->execute();
        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $booksArray = [];
        foreach ($searchResults as $result) {
            $book = Book::createAndHydrate($result);
            $booksArray[] = $book;
        }
    
        return $booksArray;
    }

    public function findByCategoryId(int $categoryId): array
    {
        $query = $this->pdo->prepare('SELECT * FROM book WHERE type_id = :categoryId');
        $query->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll($this->pdo::FETCH_ASSOC);
        
        $booksArray = [];
        foreach ($results as $result) {
            $book = Book::createAndHydrate($result);
            $booksArray[] = $book;
        }
        
        return $booksArray;
    }


    
}