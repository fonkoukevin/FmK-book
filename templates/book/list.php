<?php require_once _ROOTPATH_ . '/templates/header.php'; ?>

<!-- Barre de recherche -->
<div class="row mb-3">
    <div class="col-md-6 offset-md-3">
        <form action="index.php" method="GET" class="d-flex">
            <input type="hidden" name="controller" value="book">
            <input type="hidden" name="action" value="search">
            <input type="text" name="q" class="form-control me-2" placeholder="Rechercher un livre..." value="<?= isset($searchTerm) ? htmlspecialchars($searchTerm) : '' ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
    </div>
</div>

<!-- Bouton de filtre -->
<div class="row mb-3">
    <div class="col-md-6 offset-md-3 text-center">
        <button class="btn btn-secondary" onclick="toggleFilter()">Filtrer</button>
    </div>
</div>

<!-- Formulaire de filtre (caché par défaut) -->
<div id="filterForm" class="row mb-3" style="display: none;">
    <div class="col-md-6 offset-md-3">
        <form action="index.php" method="GET">
            <input type="hidden" name="controller" value="book">
            <input type="hidden" name="action" value="list">
            <div class="mb-3">
                <label for="type_id" class="form-label">Catégorie</label>
                <select name="type_id" id="type_id" class="form-select">
                    <option value="">Aucun filtre</option> <!-- Option "Aucun filtre" -->
                    <?php foreach ($types as $type) : ?>
                        <option value="<?= $type->getId(); ?>" <?= isset($selectedTypeId) && $selectedTypeId == $type->getId() ? 'selected' : '' ?>><?= $type->getName(); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Appliquer le filtre</button>
        </form>
    </div>
</div>


<h1><?= isset($searchTerm) ? "Résultats de recherche pour : " . htmlspecialchars($searchTerm) : "Liste complète" ?></h1>

<!-- Affichage des livres -->
<div class="row text-center mb-3">
    <?php
    function compareIdsDesc($a, $b)
    {
        return $b->getId() - $a->getId();
    }

    usort($books, 'compareIdsDesc');
    foreach ($books as $book) {
        if ($book->getImage() !== null) { ?>
            <div class="col-md-4 my-2 d-flex">
                <div class="card">
                    <img src="./uploads/books/<?= $book->getImage() ?>" class="card-img-top" alt="<?= $book->getTitle() ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $book->getTitle(); ?></h5>
                        <p class="card-text"><?= $book->getDescription() ?></p>
                        <a href="index.php?controller=book&amp;action=show&amp;id=<?= $book->getId() ?>" class="btn btn-primary">Lire la suite</a>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="col-md-4 my-2 d-flex">
                <div class="card">
                    <img src="./uploads/books/default-book.jpg" class="card-img-top" alt="<?= $book->getTitle() ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $book->getTitle(); ?></h5>
                        <p class="card-text"><?= $book->getDescription() ?></p>
                        <a href="index.php?controller=book&amp;action=show&amp;id=<?= $book->getId() ?>" class="btn btn-primary">Lire la suite</a>
                    </div>
                </div>
            </div>
    <?php }
    } ?>
</div>

<!-- Génération de la pagination -->
<?php if (!isset($searchTerm)) { ?>
    <div class="row">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                    <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?controller=book&amp;action=list&amp;page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
<?php } ?>

<script>
    function toggleFilter() {
        var filterForm = document.getElementById('filterForm');
        if (filterForm.style.display === 'none') {
            filterForm.style.display = 'block';
        } else {
            filterForm.style.display = 'none';
        }
    }
</script>

<?php require_once _ROOTPATH_ . '/templates/footer.php'; ?>
