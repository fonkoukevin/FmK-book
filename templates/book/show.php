<?php

use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\CommentRepository;
use App\Repository\RatingRepository;
use App\Entity\Author;
use App\Entity\Book;
use App\Tools\NavigationTools;

require_once _TEMPLATEPATH_ . '/header.php';

$id = $_GET["id"];
$bookRepository = new BookRepository();
$commentRepository = new CommentRepository();
$ratingRepository = new RatingRepository();
$books = $bookRepository->findOneById($id);

if (!$books) {
    echo "Book not found";
    require_once _TEMPLATEPATH_ . '/footer.php';
    exit;
}

$typeName = $books->getType()->getName();
$authorNickname = $books->getAuthor()->getFirstName();
$comments = $commentRepository->findAllByBookId($id);
$newComment = new \App\Entity\Comment();

// Traitement de la soumission du formulaire de commentaire
if (isset($_POST['saveComment'])) {
    if (User::isLogged()) {
        $newComment->setUserId(User::getCurrentUserId());
        $newComment->setBookId($id);
        $newComment->setCreatedAt(new \DateTime());
        $newComment->hydrate($_POST);

        $errors = $newComment->validate();
        if (empty($errors)) {
            $commentRepository->persist($newComment);
            header("Location: index.php?controller=book&action=show&id=$id");
            exit;
        }
    } else {
        $errors[] = "Vous devez être connecté pour ajouter un commentaire.";
    }
}

// Traitement de la soumission du formulaire de notation
if (isset($_POST['saveRating'])) {
    if (User::isLogged()) {
        // Créez une nouvelle instance de notation
        $newRating = new \App\Entity\Rating();
        $newRating->setUserId(User::getCurrentUserId());
        $newRating->setBookId($id);
        $newRating->setCreatedAt(new \DateTime());
        $newRating->setRate($_POST['rate']); // Récupérez la note à partir du formulaire

        // Validez les données de notation
        $errors = $newRating->validate();
        if (empty($errors)) {
            // Enregistrez la notation dans la base de données
            $ratingRepository->persist($newRating);
            // Redirigez l'utilisateur vers la page du livre après l'ajout de la note
            header("Location: index.php?controller=book&action=show&id=$id");
            exit;
        }
    } else {
        $errors[] = "Vous devez être connecté pour ajouter une note.";
    }
}

// Affichage de la page du livre
if ($books->getImage() !== null) {
?>

    <div class="row align-items-start g-5 py-5 my-5 bg-body-tertiary">
        <div class="col-10 col-sm-8 col-lg-4">
            <img src="/uploads/books/<?= $books->getImage() ?>" class="d-block mx-lg-auto img-fluid" alt="<?= $books->getTitle() ?>">
        </div>
        <div class="col-lg-4">
            <h1 class="display-5 fw-bold lh-1 mb-3"><?= $books->getTitle() ?></h1>
            <p class="lead"><?= $books->getDescription() ?></p>
        </div>
        <div class="col-md-12 col-lg-4 col-xl-4">
            <?php if (User::isLogged() && User::isAdmin()) { ?>
                <div class="card mb-3">
                    <div class="card-body p-4">
                        <a href="index.php?controller=book&action=edit&id=<?= $books->getId(); ?>" class="btn btn-primary">Modifier</a>
                        <a href="index.php?controller=book&action=delete&id=<?= $books->getId(); ?>" class="btn btn-primary">Supprimer</a>
                    </div>
                </div>
            <?php } ?>

            <div class="card mb-3">
                <div class="card-body p-4">
                    <h2>Auteur : <?= $authorNickname ?></h2>
                    <h2>Type : <?= $typeName ?></h2>
                </div>
            </div>
            
            <?php if (User::isLogged()) { ?>
                <?php require_once _TEMPLATEPATH_ . '/book/_partial_rating.php'; ?>
            <?php } else { ?>
                <p>Vous devez être  <a href="/index.php?controller=auth&action=login" class="btn-outline-primary me-2 <?= NavigationTools::addActiveClass('auth', 'login') ?>">connecté</a> pour ajouter une note à ce livre.</p>
            <?php } ?>

        </div>
    </div>

<?php } else { ?>

    <div class="row align-items-start g-5 py-5 my-5 bg-body-tertiary">
        <div class="col-10 col-sm-8 col-lg-4">
            <img src="/assets/images/default-book.jpg" class="d-block mx-lg-auto img-fluid" alt="Book Image">
        </div>
        <div class="col-lg-4">
            <h1 class="display-5 fw-bold lh-1 mb-3"><?= $books->getTitle() ?></h1>
            <p class="lead"><?= $books->getDescription() ?></p>
        </div>
        <div class="col-md-12 col-lg-4 col-xl-4">
            <?php if (User::isLogged() && User::isAdmin()) { ?>
                <div class="card mb-3">
                    <div class="card-body p-4">
                        <a href="index.php?controller=book&action=edit&id=<?= $books->getId(); ?>" class="btn btn-primary">Modifier</a>
                        <a href="index.php?controller=book&action=delete&id=<?= $books->getId(); ?>" class="btn btn-primary">Supprimer</a>
                    </div>
                </div>
            <?php } ?>

            <div class="card mb-3">
                <div class="card-body p-4">
                    <h2>Auteur : <?= $authorNickname ?></h2>
                    <h2>Type : <?= $typeName ?></h2>
                </div>
            </div>

            <?php if (User::isLogged()) { ?>
                <?php require_once _TEMPLATEPATH_ . '/book/_partial_rating.php'; ?>
            <?php } else { ?>
                <p>Vous devez être <a href="/index.php?controller=auth&action=login" class=" btn-outline-primary me-2 <?= NavigationTools::addActiveClass('auth', 'login') ?>">connecté</a> pour ajouter une note à ce livre.</p>
            <?php } ?>

        </div>
    </div>

<?php } ?>

<div class="row align-items-start justify-content-center">
    <?php require_once _TEMPLATEPATH_ . '/book/_partial_comments.php'; ?>
</div>

<?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>
