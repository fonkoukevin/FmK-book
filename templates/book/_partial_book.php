<?php
use App\Entity\User;
/** @var  App\Entity\Book $book */
?>
<div class="col-md-4 my-2 d-flex">
    <div class="card">
        <img src="<?= htmlspecialchars($book->getImagePath()); ?>" class="card-img-top" alt="<?= htmlspecialchars($book->getTitle()); ?>">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($book->getTitle()); ?></h5>
            <p class="card-text"><?= htmlspecialchars(substr($book->getDescription(), 0, 100) . '...'); ?></p>
            <a href="index.php?controller=book&action=show&id=<?= $book->getId(); ?>" class="btn btn-primary">Lire la suite</a>
            <?php if (User::isLogged() && User::isAdmin()) { ?>
                <a href="index.php?controller=book&action=edit&id=<?= $book->getId(); ?>" class="btn btn-primary">Modifier</a>
                <a href="index.php?controller=book&action=delete&id=<?= $book->getId(); ?>" class="btn btn-primary">Supprimer</a>
            <?php } ?>
        </div>
    </div>
</div>