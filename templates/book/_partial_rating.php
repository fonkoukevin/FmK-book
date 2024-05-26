<?php
// Récupérer la note moyenne des utilisateurs pour ce livre
$averageRate = $ratingRepository->findAverageByBookId($id) ?? 0; // Assurez-vous que $averageRate est défini et non null

// Convertir la note moyenne en notation d'étoiles
$averageRate = (int)$averageRate; // Convertir en entier
$averageRateStars = str_repeat('★', $averageRate) . str_repeat('☆', 5 - $averageRate);
?>

<div class="card">
    <div class="card-body p-4">
        <div class="row mb-3">
            <h2>Note des utilisateurs</h2>
            <div class="row align-items-center justify-content-center">
                <div class="rate col-6">
                    <span class="large-stars"><?= $averageRateStars ?></span> <!-- Afficher la notation d'étoiles de la note moyenne avec une classe pour agrandir -->
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <h3>Noter ce livre</h3>

            <form method="POST">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-4 py-2">
                            <label for="rate" class="form-label">Votre note :</label>
                        </div>
                        <div class="col-8">
                            <div class="rate enabled">
                                <!-- Afficher les boutons radio en fonction de la note déjà attribuée -->
                                <?php for ($i = 5; $i >= 1; $i--) { ?>
                                    <input type="radio" id="star<?= $i ?>" name="rate" value="<?= $i ?>" <?= (isset($userRating) && $userRating && $userRating->getRate() == $i) ? 'checked' : '' ?>>
                                    <label for="star<?= $i ?>" title="<?= $i ?> étoile<?= $i > 1 ? 's' : '' ?>"><?= $i ?> étoile<?= $i > 1 ? 's' : '' ?></label>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="book_id" value="<?= $book->getId() ?>">
                <?php if (isset($currentUser)) { ?>
                    <input type="hidden" name="user_id" value="<?= $currentUser->getId() ?>">
                <?php } ?>

                <!-- Afficher le champ d'ID de note uniquement si l'utilisateur a déjà noté le livre -->
                <?php if (isset($userRating) && $userRating && $userRating->getId()) { ?>
                    <input type="hidden" name="id" value="<?= $userRating->getId() ?>">
                <?php } ?>

                <div class="mb-3">
                    <input type="submit" name="saveRating" class="btn btn-primary form-control" value="Noter">
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .large-stars {
        font-size: 24px; /* Modifier la taille des étoiles selon vos préférences */
    }
</style>
