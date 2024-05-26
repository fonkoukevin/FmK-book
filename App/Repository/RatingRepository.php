<?php

namespace App\Repository;

use App\Entity\Rating;
use PDO;

class RatingRepository extends Repository
{
    public function findOneByBookIdAndUserId(int $book_id, int $user_id): ?Rating
    {
        $query = $this->pdo->prepare('SELECT * FROM rating WHERE book_id = :book_id AND user_id = :user_id');
        $query->bindValue(':book_id', $book_id, PDO::PARAM_INT);
        $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query->execute();
        $rating = $query->fetch(PDO::FETCH_ASSOC);

        return $rating ? Rating::createAndHydrate($rating) : null;
    }

    public function findAverageByBookId(int $book_id): ?int
    {
        $query = $this->pdo->prepare("SELECT AVG(rate) as rate FROM rating WHERE book_id = :book_id");
        $query->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $query->execute();
        $rate = $query->fetch(PDO::FETCH_ASSOC);

        return $rate && isset($rate['rate']) ? (int) floor($rate['rate']) : null;
    }

    public function persist(Rating $rating): bool
    {
        $sql = $rating->getId() !== null
            ? 'UPDATE rating SET rate = :rate, book_id = :book_id, user_id = :user_id WHERE id = :id'
            : 'INSERT INTO rating (rate, book_id, user_id, created_at) VALUES (:rate, :book_id, :user_id, :created_at)';

        $query = $this->pdo->prepare($sql);
        $query->bindValue(':rate', $rating->getRate(), PDO::PARAM_INT);
        $query->bindValue(':book_id', $rating->getBookId(), PDO::PARAM_INT);
        $query->bindValue(':user_id', $rating->getUserId(), PDO::PARAM_INT);
        if ($rating->getId() === null) {
            $query->bindValue(':created_at', $rating->getCreatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        } else {
            $query->bindValue(':id', $rating->getId(), PDO::PARAM_INT);
        }

        return $query->execute();
    }

    public function findLastRatingByUserIdAndBookId(int $user_id, int $book_id): ?Rating
{
    $query = $this->pdo->prepare('SELECT * FROM rating WHERE user_id = :user_id AND book_id = :book_id ORDER BY created_at DESC LIMIT 1');
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->bindValue(':book_id', $book_id, PDO::PARAM_INT);
    $query->execute();
    $ratingData = $query->fetch(PDO::FETCH_ASSOC);

    return $ratingData ? Rating::createAndHydrate($ratingData) : null;
}

}
