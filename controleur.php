<?php

include('bloc.php');
include('article.php');
include('./include/connexion.php');
include('include/twig.php');
$twig = init_twig();


if (isset($_GET['page'])) $page = $_GET['page'];
else $page = '';

if (isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if (isset($_GET['id'])) $id = intval($_GET['id']);
else $id = 0;


switch ($page) {
        case 'bloc':
                switch ($action) {
                        case 'readOne':
                                $view='readOne.twig';
                                $data = [
                                        'bloc' => Bloc::readOne($id)
                                ];
                                break;
                        case 'readAll':
                                $article=Article::readAll();
                                $result = Bloc::readAll();
                                $view = 'readAll.twig';
                                $data=[
                                        'result' => $result,
                                        'article' => $article
                                ];
                                break;
                        case 'createForm':
                                $view='create_bloc.twig';
                                $data=[
                                        'article' => Article::readAll(),
                                ];
                                break;
                        case 'create':
                                $bloc = new Bloc();
                                $bloc->chargePOST();
                                $bloc->create();
                                $view = ['create_bloc.twig']; 
                                $data =[ 
                                        'bloc' => $bloc,
                                        'message' => 'Bloc créée avec succès',
                                ];
                                header('Location: index.php');
                               break;
                        case 'edit':
                                $view = 'edit_bloc.twig';
                                $data = [
                                        'bloc' => Bloc::readOne($id),
                                        'article' => Article::readAll(),
                                ];
                                break;
                        case 'update':
                                $bloc = new Bloc();     
                                $bloc->chargePOST();
                                $bloc->update($id);
                                header('Location: index.php');
                                exit;
                                break;
                        case 'delete':
                                $bloc = Bloc::readOne($id);
                                Bloc::delete($id);
                                $data =[
                                        'message' => 'Bloc supprimé'
                                ];
                                header('Location: index.php');
                                exit;
                                break;
                        case 'render':
                                $view='render.twig';
                                $data =[
                                        'c' =>Bloc::readAll(),
                                        'article' => Article::readAll(),
                                ];
                                break;
                        case 'moveUp':
                                $bloc = Bloc::readOne($id);
                                $bloc->move_up($id, $bloc->position);
                                $bloc = Bloc::readAll();
                                $data =[
                                        'bloc' =>$bloc,
                                ];
                                header('Location: controleur.php?page=bloc&action=readAll');
                                exit;
                                break;
                        case 'moveDown':
                                $bloc = Bloc::readOne($id);
                                $bloc->move_down($id, $bloc->position);
                                $bloc = Bloc::readAll();
                                $data =[
                                        'bloc' => $bloc,
                                ];
                                header('Location: controleur.php?page=bloc&action=readAll');
                                exit;
                                break;
                        case'readByArticle':
                                $article=Article::readOne($id);
                                $bloc=Bloc::readByArticle($article_id);
                                $view='readBlocByArticle.twig';
                                $data=[
                                        'article' => $article,
                                        'bloc' => $bloc,
                                ];
                      
                                break;
                                
                }

        case 'article':
                switch($action){
                        case 'createFormArticle':
                                $view='create_article.twig';
                                $data=[];
                                break;
                        case'createArticle':
                                $article= new Article();
                                $article->chargePost();
                                $article->create();
                                $view = ['create_article.twig']; 
                                $data =[ 
                                        'article' => $article,
                                ];
                                header('Location: index.php');
                                break;
                        case 'readAllarticle':
                                $tableau = Article::readAll();
                                $view='readAll_article.twig';
                                $data=[
                                        'tableau' => $tableau,
                                ];
                                break;
                        case 'readOneArticle':
                                 $view='readOne_article.twig';
                                 $data = [
                                        'article' => Article::readOne($id)
                                ];
                                break;
                        case 'deleteArticle':
                                $article = Article::readOne($id);
                                Article::delete($id);
                                $data =[
                                        'message' => 'Bloc supprimé'
                                ];
                                header('location: index.php');
                                exit;
                                break;
                        case 'editArticle':
                                        $view = 'editArticle.twig';
                                        $data = [
                                                'article' => Article::readOne($id)
                                        ];
                                        break;
                        case 'updateArticle':
                                        $article = new Article();     
                                        $article->chargePOST();
                                        $article->update($id);
                                        header('Location: index.php');
                                        exit;
                                        break;
                        case 'readBlocByArticle':
                                $article = Article::readOne($id);
                                $bloc = Bloc::readByArticle($id);
                                $view = 'readBloc_article.twig';
                                $data = [
                                        'article' => $article,
                                        'bloc' => $bloc
                                ];
                                        break;
                        case 'moveUpArticle':
                                $article = Article::readOne($id);
                                $article->move_up($id, $article->position);
                                $article = Article::readAll();
                                
                                $data =[
                                        'article' =>$article,
                                ];
                                header('Location: controleur.php?page=article&action=readAllarticle');
                                exit;
                                break;
                        case 'moveDownArticle':
                                $article = Article::readOne($id);
                                $article->move_down($id, $article->position);
                                $article = Article::readAll();
                                
                                $data =[
                                        'article' => $article,
                                ];
                                header('Location: controleur.php?page=article&action=readAllarticle');
                                exit;
                                break;
                }
                break;
        default:
                $view = 'base.twig';
                $data = [];
                break;
}

echo $twig->render($view, $data);
