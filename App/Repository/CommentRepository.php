<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Repository\UserRepository;
use App\Db\Mysql;
use App\Tools\StringTools;

class CommentRepository extends Repository
{
    public function findAllByBookId(int $book_id): array
    {
        $query = $this->pdo->prepare("SELECT * FROM comment WHERE book_id = :book_id ORDER BY id ASC");
        $query->bindParam(':book_id', $book_id, $this->pdo::PARAM_STR);
        $query->execute();
        $comments = $query->fetchAll($this->pdo::FETCH_ASSOC);
    
        $commentsArray = [];
        if (!empty($comments)) {
            $userRepository = new UserRepository();
            foreach ($comments as $commentData) {
                $comment = Comment::createAndHydrate($commentData); // Créer une instance de Comment
                $user = $userRepository->findOneById($commentData['user_id']); // Récupérer l'utilisateur associé
                if ($user) {
                    $comment->setUser($user); // Affecter l'utilisateur à l'objet Comment
                    $commentsArray[] = $comment;
                } else {
                    throw new \Exception("Aucun utilisateur trouvé pour le commentaire avec l'ID " . $commentData['id']);
                }
            }
        }
    
        return $commentsArray;
    }
    
    

    public function persist(Comment $comment)
    {
        try {
            $this->pdo->beginTransaction();
    
            if ($comment->getId() !== null) {
                $query = $this->pdo->prepare('UPDATE comment SET comment = :comment, book_id = :book_id,  
                                                    user_id = :user_id WHERE id = :id');
                $query->bindValue(':id', $comment->getId(), $this->pdo::PARAM_INT);
            } else {
                $query = $this->pdo->prepare('INSERT INTO comment (comment, book_id, user_id, created_at) 
                                                    VALUES (:comment, :book_id, :user_id, :created_at)');
                $query->bindValue(':created_at', date('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
            }
    
            $query->bindValue(':comment', $comment->getComment(), $this->pdo::PARAM_STR);
            $query->bindValue(':book_id', $comment->getBookId(), $this->pdo::PARAM_INT);
            $query->bindValue(':user_id', $comment->getUserId(), $this->pdo::PARAM_INT);
    
            $query->execute();
    
            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            // Gérer l'erreur de manière appropriée
            return false;
        }
    }
    
}