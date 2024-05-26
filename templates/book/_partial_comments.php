<?php 
$firstComment = !empty($comments) ? $comments[0] : null;
?>

<div class="col-md-12 col-lg-8 col-xl-8">
    <div class="card">
        <div class="card-body p-4">
            <h2>Commentaires</h2>
            <div class="row">
            <?php if (!empty($comments)) { ?>
                <?php foreach($comments as $comment) { ?>  
                    <div class="col">
                        <div class="d-flex flex-start bg-body-tertiary p-2 my-1">
                            <div class="flex-grow-1 flex-shrink-1">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">
                                            <span class="small">
                                                <?= $comment->getUser() !== null ? $comment->getUser()->getFirstName() : "Utilisateur non défini" ?>
                                                - <?= $comment->getCreatedAt()->format('Y-m-d H:i:s') ?>
                                            </span>
                                        </p>
                                    </div>
                                    <p class="small mb-0">
                                        <?= $comment->getComment() ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>Aucun commentaire pour le moment.</p>
            <?php } ?>
            </div>
            <form method="POST">
                <div class="mb-3">
                    <label for="comment" class="form-label">Commenter</label>
                    <textarea type="text" class="form-control" id="comment" name="comment" rows="5"></textarea>
                </div>
                <?php if ($firstComment !== null) { ?>
                    <input type="hidden" name="book_id" value="<?= $firstComment->getBookId() ?>">
                    <input type="hidden" name="user_id" value="<?= $firstComment->getUser() !== null ? $firstComment->getUser()->getId() : 0 ?>">
                <?php } ?>
                <input type="submit" name="saveComment" class="btn btn-primary" value="Commenter">
            </form>
        </div>
    </div>
</div>
