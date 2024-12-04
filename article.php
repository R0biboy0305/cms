<?php 

class Article{

    public $id;
    public $h1;
    public $h2;
    public $style_h1;
    public $style_h2;
    public $auteur;
    public $position;

    function create()
    {   
        $sql='SELECT MAX(position) AS max_position FROM article';
        $pdo=connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);


        $new_position = $result['max_position'] + 1;   



        $sql = 'INSERT INTO article (h1, h2, auteur, position)
         VALUES (:h1, :h2, :auteur, :position)';

        $pdo = connexion();

        $query = $pdo->prepare($sql);
        $query->bindParam(':h1', $this->h1);
        $query->bindParam(':h2', $this->h2);
        $query->bindParam(':auteur', $this->auteur);
        $query->bindParam(':position', $new_position->position);
        $query->execute();
    }

    static function readAll(){
        $sql ='SELECT * FROM article ORDER BY position ASC';
        $pdo=connexion('article');
        $query=$pdo->prepare($sql);
        $query->execute();

        $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'article');
        return $tableau;
    }

    static function readOne($id){

        $sql ='SELECT * FROM article WHERE :id = id';
        $pdo=connexion();
        $query=$pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $resultat=$query->fetchObject('article');
        return $resultat;
    }

    function chargePost(){

        if(isset($_POST['id'])){
            $this->id=$_POST['id'];
        }
        if(isset($_POST['h1'])){
            $this->h1=$_POST['h1'];
        }
        if(isset($_POST['h2'])){
            $this->h2=$_POST['h2'];
        }
        if(isset($_POST['style_h1'])){
            $this->style_h1=$_POST['style_h1'];
        }
        if(isset($_POST['style_h2'])){
            $this->style_h2=$_POST['style_h2'];
        }
        if(isset($_POST['auteur'])){
            $this->auteur=$_POST['auteur'];
        }
        if(isset($_POST['position'])){
            $this->h2=$_POST['position'];
        }

    }

    static function delete($id)
    {
        $sql = 'DELETE FROM article WHERE id = :id;';
        $pdo = connexion('article');
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    function update(){

        $sql = 'Update article SET h1 = :h1, h2 = :h2, auteur = :auteur WHERE :id = id';
        $pdo = connexion('article');
        $query = $pdo->prepare($sql);
        $query->bindParam(':id', $this->id, PDO::PARAM_INT);
        $query->bindParam(':h1', $this->h1, PDO::PARAM_STR);
        $query->bindParam(':h2', $this->h2, PDO::PARAM_STR);
        $query->bindParam(':auteur', $this->auteur, PDO::PARAM_STR);

        $query->execute();

    }

    function move_up($id, $position){
        $sql='SELECT id, position FROM article WHERE position < :position ORDER BY position DESC LIMIT 1';
        $pdo=connexion();
        $query=$pdo->prepare($sql);
        $query->execute(['position' => $position]);
        $blocPrecedent = $query->fetch();

        if ($blocPrecedent){

            $Updatesql = 'UPDATE article SET position = :new_position WHERE :id = id';
            $pdo=connexion();
            $stmt = $pdo->prepare($Updatesql);
            $stmt->execute(['new_position' => $blocPrecedent['position'], 'id' => $id]);
            $stmt->execute(['new_position' => $position, 'id' => $blocPrecedent['id']]);
        }

    }

    function move_down($id, $position) {
       
        $sql='SELECT id, position FROM article WHERE position > :position ORDER BY position ASC LIMIT 1';
        $pdo=connexion();
        $query=$pdo->prepare($sql);
        $query->execute(['position' => $position]);
        $blocSuivant = $query->fetch();
    
        if ($blocSuivant) {

            $Updatesql = 'UPDATE article SET position = :new_position WHERE id = :id';
            $stmt = $pdo->prepare($Updatesql);
            $stmt->execute(['new_position' => $blocSuivant['position'], 'id' => $id]);
            $stmt->execute(['new_position' => $position, 'id' => $blocSuivant['id']]);
        }
    }


}