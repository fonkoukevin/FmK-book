<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CommentRepository;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Rating;
use App\Entity\Type;
use App\Tools\FileTools;
use App\Repository\TypeRepository;
use App\Repository\AuthorRepository;
use App\Repository\RatingRepository;


class BookController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'show':
                        $this->show();
                        break;
                    case 'add':
                        $this->add();
                        break;
                    case 'edit':
                        $this->edit();
                        break;
                    case 'delete':
                        $this->delete();
                        break;
                    case 'search': // Ajoutez ce cas
                            $this->search();
                            break;
                    case 'list':
                        $this->list();
                        break;
                        case 'filterByCategory': // Ajoutez ce cas
                            $this->filterByCategory();
                            break;
                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                        break;
                }
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
    /*
    Exemple d'appel depuis l'url
        ?controller=book&action=show&id=1
    */
    protected function show()
    {
        $errors = [];
    
        try {
            if (isset($_GET['id'])) {
    
                $id = (int)$_GET['id'];
                $bookRepository = new BookRepository();
                $book = $bookRepository->findOneById($id);
    
                if ($book) {
                    $comment = new Comment();
                    $commentRepository = new CommentRepository();
    
                    if (isset($_POST['saveComment'])) {
                        if (!User::isLogged()) {
                            throw new \Exception("Accès refusé");
                        }
    
                        $comment->hydrate($_POST);
                        $comment->setBookId($id);
                        $comment->setUserId(User::getCurrentUserId());
    
                        $errors = $comment->validate();
    
                        if (empty($errors)) {
                            $commentRepository->persist($comment);
                            // Rediriger pour éviter le re-post du formulaire
                            header('Location: ' . $_SERVER['REQUEST_URI']);
                            exit;
                        }
                    }
    
                    $comments = $commentRepository->findAllByBookId($id);
    
                    $this->render('book/show', [
                        'book' => $book,
                        'comments' => $comments,
                        'newComment' => $comment,
                        'rating' => '',
                        'averageRate' => '',
                        'errors' => $errors,
                    ]);
                } else {
                    $this->render('errors/default', [
                        'error' => 'Livre introuvable'
                    ]);
                }
            } else {
                throw new \Exception("L'id est manquant en paramètre");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
    

    protected function add()
    {
        $this->add_edit();
    }

    protected function edit()
    {
        try {
            if (isset($_GET['id'])) {
                $this->add_edit((int)$_GET['id']);
            } else {
                throw new \Exception("L'id est manquant en paramètre");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function add_edit($id = null)
    {

        try {
            // Cette action est réservé aux admin
            if (!User::isLogged() || !User::isAdmin()) {
                throw new \Exception("Accès refusé");
            }
            $bookRepository = new BookRepository();
            $errors = [];
            // Si on a pas d'id on est dans le cas d'une création
            if (is_null($id)) {
                $book = new Book();
            } else {
                // Si on a un id, il faut récupérer le livre
                $book = $bookRepository->findOneById($id);
                if (!$book) {
                    throw new \Exception("Le livre n'existe pas");
                }
            }

            // @todo Récupération des types
                $typeRepository = new TypeRepository();
                $types = $typeRepository->findAll();

            // @todo Récupération des auteurs
                  
                  $authorRepository =new AuthorRepository(); 
                  $authors = $authorRepository->findAll();

            if (isset($_POST['saveBook'])) {
                //@todo envoyer les données post à la méthode hydrate de l'objet $book
                     $book->hydrate($_POST);
                //@todo appeler la méthode validate de l'objet book pour récupérer les erreurs (titre vide)               
                $errors =$book->validate();

                // Si pas d'erreur on peut traiter l'upload de fichier
                if (empty($errors)) {
                    $fileErrors = [];
                    // On lance l'upload de fichier
                    if (isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] !== '') {
                        //@todo appeler la méthode static uploadImage de la classe FileTools et stocker le résultat dans $res
                     //   $res = FileTools::uploadImage(  $_FILES['file']['tmp_name'],$_FILES['file']['name'] );
                        $res = FileTools::uploadImage(  $_FILES['file']['name'] ,$_FILES['file']['tmp_name']);

                        if (empty($res['errors'])) {
                            //@todo décommenter cette ligne
                            $book->setImage($res['fileName']);
                        } else {
                            $fileErrors = $res['errors'];
                        }
                    }
                    if (empty($fileErrors)) {
                        // @todo si pas d'erreur alors on appelle persit de bookRepository en passant $book

                           $bookRepository->persist($book);
                        // @todo On redirige vers la page du livre (avec header location)
                        header('location: index.php?controller=book&action=show&id=' . $book->getId()); 
                    } else {
                        $errors = array_merge($errors, $fileErrors);
                    }
                }
            }

            $this->render('book/add_edit', [
                'book' => $book,
                'types' => '',
                'authors' => '',
                'pageTitle' => 'Ajouter un livre',
                'errors' => ''
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function list()
    {
        $bookRepository = new BookRepository();
        $typeRepository = new TypeRepository();
        $types = $typeRepository->findAll();
    
        // On récupère la page courante, si page de page on met à 1
        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
        } else {
            $page = 1;
        }
    
        // Récupérer le type_id si présent, sinon mettre à null
        $typeId = isset($_GET['type_id']) && $_GET['type_id'] !== '' ? (int)$_GET['type_id'] : null;
    
        // Récupérer les livres en fonction du type sélectionné
        $livres = $bookRepository->findAll(_HOME_BOOK_LIMIT_, $page, $typeId);
        $totallivres = $bookRepository->count($typeId);
        $totalpages = ceil($totallivres / _HOME_BOOK_LIMIT_);
    
        $this->render('book/list', [
            'books' => $livres,
            'types' => $types,
            'totalPages' => $totalpages,
            'page' => $page,
            'selectedTypeId' => $typeId
        ]);
    }
    

    protected function delete()
    {
        try {
            // Cette action est réservé aux admin
            if (!User::isLogged() || !User::isAdmin()) {
                throw new \Exception("Accès refusé");
            }

            if (!isset($_GET['id'])) {
                throw new \Exception("L'id est manquant en paramètre");
            }
            $bookRepository = new BookRepository();

            $id = (int)$_GET['id'];

            $book = $bookRepository->findOneById($id);

            if (!$book) {
                throw new \Exception("Le livre n'existe pas");
            }
            if ($bookRepository->removeById($id)) {
                // On redirige vers la liste de livre
                header('location: index.php?controller=book&action=list&alert=delete_confirm');
            } else {
                throw new \Exception("Une erreur est survenue l'ors de la suppression");
            }

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
    protected function search()
    {
        try {
            if (isset($_GET['q'])) {
                $searchTerm = $_GET['q'];
                $bookRepository = new BookRepository();
                $books = $bookRepository->searchByTitleOrDescription($searchTerm);
    
                $this->render('book/list', [
                    'books' => $books,
                    'searchTerm' => $searchTerm,
                    'totalPages' => 1, // Pas de pagination pour la recherche
                    'page' => 1
                ]);
            } else {
                throw new \Exception("Le terme de recherche est manquant");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function filterByCategory()
    {
        try {
            if (isset($_GET['categoryId'])) {
                $categoryId = (int)$_GET['categoryId'];
                $bookRepository = new BookRepository();
                $typeRepository = new TypeRepository();
                
                $books = $bookRepository->findByCategoryId($categoryId);
                $types = $typeRepository->findAll();

                $this->render('book/list', [
                    'books' => $books,
                    'types' => $types,
                    'selectedCategory' => $categoryId,
                    'totalPages' => 1, // Pas de pagination pour le filtre par catégorie
                    'page' => 1
                ]);
            } else {
                throw new \Exception("L'identifiant de la catégorie est manquant");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
    
}
