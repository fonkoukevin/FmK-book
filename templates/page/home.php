<?php require_once _ROOTPATH_ . '/templates/header.php'; ?>

<div class="row flex-lg-row-reverse align-items-center g-5 py-5">
    <div class="col-10 col-sm-8 col-lg-6">
        <img src="assets/images/logo-bookeo.jpg" class="d-block mx-lg-auto img-fluid" alt="Bookeo" width="400" loading="lazy">
    </div>
    <div class="col-lg-6">
        <h1 class="display-5 fw-bold lh-1 mb-3">Bookeo - Livre, BD, Manga</h1>
        <p class="lead">Découvrez et partagez vos lectures préférées sur Bookeo, où les livres, BD et mangas prennent vie. Notez, commentez et explorez dès maintenant !</p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="index.php?controller=book&action=list" class="btn btn-primary btn-lg px-4 me-md-2">Voir tous les livres</a>
        </div>
    </div>
</div>

<h2>Découvez les dernières oeuvres</h2>

<div class="row text-center">
    <div class="col-md-4 my-2 d-flex">
        <div class="card">
            <img src="/uploads/books/3-zai-zai-zai-zai.jpg" class="card-img-top" alt="Zaï Zaï Zaï Zaï">
            <div class="card-body">
                <h5 class="card-title">Zaï Zaï Zaï Zaï</h5>
                <p class="card-text">Au passage à la caisse d'un supermarché, un auteur de bande dessinée réalise qu’il n’a pas s...</p>
                <a href="index.php?controller=book&amp;action=show&amp;id=82" class="btn btn-primary">Lire la suite</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 my-2 d-flex">
        <div class="card">
            <img src="/uploads/books/2-histoires-courtes.jpg" class="card-img-top" alt="Histoires courtes">
            <div class="card-body">
                <h5 class="card-title">Histoires courtes</h5>
                <p class="card-text">Ce volume unique rassemble les nouvelles créées par le maître Junji Ito pour l'éditeur japonais ...</p>
                <a href="index.php?controller=book&amp;action=show&amp;id=81" class="btn btn-primary">Lire la suite</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 my-2 d-flex">
        <div class="card">
            <img src="/uploads/books/1-1984.jpg" class="card-img-top" alt="1984">
            <div class="card-body">
                <h5 class="card-title">1984</h5>
                <p class="card-text">L’histoire se passe à Londres en 1984, comme l'indique le titre du roman. Le monde, depuis les gr...</p>
                <a href="index.php?controller=book&amp;action=show&amp;id=80" class="btn btn-primary">Lire la suite</a>
            </div>
        </div>
    </div>
</div>

<?php require_once _ROOTPATH_ . '/templates/footer.php'; ?>