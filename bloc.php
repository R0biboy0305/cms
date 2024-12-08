<?php


class Bloc
{

    public $type;
    public $text;
    public $style;
    public $src;
    public $alt;
    public $id;
    public $article_id;
    public $target_file;
    public $position;
    public $colonne;

    function afficher()
    {
        echo $this->type . '</br>';
        echo $this->text . '</br>';
        echo $this->style . '</br>';
        echo $this->src . '</br>';
        echo $this->alt . '</br>';
    }
    function chargePOST()
    {

        if (isset($_POST['type'])) {
            $this->type = $_POST['type'];
        }
        if (isset($_POST['text'])) {
            $this->text = $_POST['text'];
        }
        if (isset($_POST['colonne'])) {
            $this->colonne = $_POST['colonne'];
        }
        if (isset($_POST['style'])) {
            $this->style = $_POST['style'];
        }
        if (isset($_POST['src'])) {
            $this->src = $_POST['src'];
        }
        if (isset($_POST['alt'])) {
            $this->alt = $_POST['alt'];
        }
        if (isset($_POST['article_id']) && is_numeric($_POST['article_id'])) {
            $this->article_id = intval($_POST['article_id']);
        }
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $this->id = intval($_POST['id']);
        }
        
        if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        $target_dir = "uploads/";
        $this->src= $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($this->src, PATHINFO_EXTENSION));
        
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "Le fhicher est une image" . $check["mime"] . ".<br>";
                $uploadOk = 1;
            } else {
                echo "Le fichier n'est pas une image.<br>";
                $uploadOk = 0;
            }

              $width = $check[0];
              $height = $check[1];
      
              if ($width > 1084 || $height > 1084) {
                  echo "Désolé, votre image fait plus de 1084*1084px";
                  $uploadOk = 0;
              }
            
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $this->src)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " a été uploadé.";
            } else {
                echo "Désolé, une erreur est survenue dans le développement.";
            }
            }
}
    }
    function create(){   

        $sql='SELECT MAX(position) AS max_position FROM bloc';
        $pdo=connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result['max_position'] == 0){

            $new_position = 1;

        }
        else{

        $new_position = $result['max_position'] + 1; 

        }  

        $sql = 'INSERT INTO bloc (type, text, style, src, alt, article_id, position, colonne)
         VALUES (:type, :text, :style, :src, :alt, :article_id, :position, :colonne)';

        $pdo = connexion('bloc');

        $query = $pdo->prepare($sql);
        $query->bindParam(':type', $this->type);
        $query->bindParam(':text', $this->text);
        $query->bindParam(':style', $this->style);
        $query->bindParam(':src', $this->src);
        $query->bindParam(':alt', $this->alt);
        $query->bindParam(':article_id', $this->article_id);
        $query->bindParam(':position', $new_position);
        $query->bindParam(':colonne', $this->colonne);
        $query->execute();
    }


    static function readAll()
    {
        $sql = 'SELECT * FROM bloc ORDER BY position ASC';
        $pdo = connexion('bloc');
        $query = $pdo->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS, 'bloc');
        return $result;
    }

    static function readOne($id)
    {
        $sql = 'Select * from bloc where id = :id';
        $pdo = connexion('bloc');
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $objet = $query->fetchObject('bloc');
        return $objet;
    }

   static function readByArticle($article_id){

        $sql = 'SELECT bloc.*, article.h1 FROM bloc 
                JOIN article ON article.id = bloc.article_id 
                WHERE bloc.article_id = :article_id';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':article_id', $article_id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
   
    } 
    
    function update()
    {     

        $sql = 'Update bloc SET type = :type, text =:text, style =:style, src = :src, alt =:alt, colonne = :colonne WHERE id = :id';
        $pdo=connexion();
        $query = $pdo->prepare($sql);
        $query->bindParam(':id', $this->id, PDO::PARAM_INT);
        $query->bindParam(':type', $this->type, PDO::PARAM_STR);
        $query->bindParam(':text', $this->text, PDO::PARAM_STR);
        $query->bindParam(':style', $this->style, PDO::PARAM_STR);
        $query->bindParam(':src', $this->src, PDO::PARAM_STR);
        $query->bindParam(':alt', $this->alt, PDO::PARAM_STR);
        $query->bindParam(':colonne', $this->colonne, PDO::PARAM_STR);

        $query->execute();
    }

    static function delete($id)
    {
        $sql = 'DELETE FROM bloc WHERE id = :id;';
        $pdo = connexion('bloc');
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    function move_up($id, $position){
        $sql='SELECT id, position FROM bloc WHERE position < :position ORDER BY position DESC LIMIT 1';
        $pdo=connexion();
        $query=$pdo->prepare($sql);
        $query->execute(['position' => $position]);
        $blocPrecedent = $query->fetch();

        if ($blocPrecedent){

            $Updatesql = 'UPDATE bloc SET position = :new_position WHERE :id = id';
            $pdo=connexion();
            $stmt = $pdo->prepare($Updatesql);
            $stmt->execute(['new_position' => $blocPrecedent['position'], 'id' => $id]);
            $stmt->execute(['new_position' => $position, 'id' => $blocPrecedent['id']]);
        }

    }

    function move_down($id, $position) {
       
        $sql='SELECT id, position FROM bloc WHERE position > :position ORDER BY position ASC LIMIT 1';
        $pdo=connexion();
        $query=$pdo->prepare($sql);
        $query->execute(['position' => $position]);
        $blocSuivant = $query->fetch();
    
        if ($blocSuivant) {

            $Updatesql = 'UPDATE bloc SET position = :new_position WHERE id = :id';
            $stmt = $pdo->prepare($Updatesql);
            $stmt->execute(['new_position' => $blocSuivant['position'], 'id' => $id]);
            $stmt->execute(['new_position' => $position, 'id' => $blocSuivant['id']]);
        }
    }
    
}
