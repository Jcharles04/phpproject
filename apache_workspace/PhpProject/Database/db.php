<?php

/**
 * @var PDO $conn
 */
global $conn;
$conn = new PDO('mysql:host=localhost;dbname=groupomania', 'groupomania', 'gpM@n1a');


/**
 * @return PDO
 */
function conn() {
    global $conn;
    return $conn;
}

/**
 * test dic
 * @param String $user
 * @param String $pw
 * @return array|null: user data if found else null
 */
function login(string $userName, string $pw) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
            SELECT `id`,
            	`Name`,
            	`FirstName`,
            	`Service`,
            	`Mail`,
            	`PassWord`,
            	`Moderator`,
            	`CreationDate`,
            	`Suppression`
        	FROM `groupomania`.`user`
            WHERE Mail LIKE ?");
        $res = $stmt->execute([$userName]);
        if (!$res) {
            $err = error_get_last();
            error_log(print_r($err, true));
            throw new Exception("Erreur requête [" . $err['message'] . "]: " . $stmt->queryString);
        }
        $values = $stmt->fetch();
        if ($values) { //Trouvé : vérifier le MDP
            if ($pw == password_verify($pw, $values['PassWord'])) { //Corrrespond
                return $values;
            } else {
                return null;
            }
        } else { //Pas de valeur :: utilisateur inconnu
            return null;
        }
        conn()->commit();
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};

function signin(string $name, string $firstname, string $service, string $mail, string $pw ) {
    try {
        $stmt = conn()->prepare("SELECT * FROM user WHERE Mail=?");
        $stmt->execute([$mail]);
        $user = $stmt->fetch();
        if ($user) {
            $err = error_get_last();
            error_log(print_r($err, true));
            throw new Exception("Erreur: l'email est déjà utilisé");
        } else {
            conn()->beginTransaction();
            $stmt = conn()->prepare("
            INSERT INTO user(Name, FirstName, Service, Mail, PassWord)  VALUES(?, ?, ?, ?, ?)");
            $stmt->execute([$name, $firstname, $service, $mail, $pw]);
            conn()->commit();
        } 

    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};

function deleteUser($userId) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        UPDATE user SET Suppression = NOW()  WHERE id = ?");
        $stmt->execute([$userId]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};

function getAllUser() {
    try{
        conn();
        $req = conn()->query("SELECT * FROM user");
        while($users = $req->fetch()){
            renderUsers($users);
        }
    }
    catch(PDOException $e){
        die('Erreur connexion : '.$e->getMessage());
    }
}


function postCom($userId, ?string $file, ?string $textarea ) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        INSERT INTO comments(User_id, ImgUrl, Text )  VALUES(?, ?, ?)");
        $stmt->execute([$userId, $file, $textarea ]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};


function getAllCom(){
    try{
        $comments = conn()->query("SELECT et.*, COUNT(UserId) likes, MAX(UserId = '{$_SESSION['user']['id']}' ) AS myLike FROM (
            SELECT c.id, c.User_id, c.CreationDate, c.ImgUrl, c.Text, c.Suppression, c.ReplyTo_id, l.UserId, u.FirstName, u.Service FROM comments c
            LEFT JOIN like_number l ON l.ComId = c.id
            LEFT JOIN USER u ON u.id= c.User_id
            WHERE c.Suppression IS NULL AND ReplyTo_id IS NULL
        ) et
        GROUP BY et.id
        ORDER BY CreationDate DESC LIMIT 20");
        while($comment = $comments->fetch()){
            $comment['replies'] = [];
            $replies = conn()->query("SELECT et.*, COUNT(UserId) likes, MAX(UserId = '{$_SESSION['user']['id']}' ) AS myLike FROM (
                SELECT c.id, c.User_id, c.CreationDate, c.ImgUrl, c.Text, c.Suppression, c.ReplyTo_id, l.UserId, u.FirstName, u.Service FROM comments c
                LEFT JOIN like_number l ON l.ComId = c.id
                LEFT JOIN USER u ON u.id= c.User_id
                WHERE c.Suppression IS NULL AND ReplyTo_id = '{$comment['id']}'
            ) et
            GROUP BY et.id
            ORDER BY CreationDate DESC");
            while($reply = $replies->fetch()){
                $comment['replies'][] = $reply;     
            }
            renderComment($comment);
        }
    }
    catch(PDOException $e){
        die('Erreur connexion : '.$e->getMessage());
    }
}


function getOneCom($comId) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        SELECT * FROM comments WHERE id = ? AND Suppression IS NULL");
        $stmt->execute([$comId]);
        $values = $stmt->fetch();
        return $values;
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
    
} 

function supImg($file, $comId) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        UPDATE comments SET ImgUrl = ? WHERE id = ?");
        $stmt->execute([ $file, $comId]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};


function modifyCom(?string $file, ?string $textarea, $comId) {

    if($file == NULL) {
        try {
            conn()->beginTransaction();
            $stmt = conn()->prepare("
            UPDATE comments SET Text = ?  WHERE id = ?");
            $stmt->execute([ $textarea, $comId]);
            conn()->commit();
            
        } catch(Exception $e) {
            if (conn() -> inTransaction()) {
                conn()->rollBack();
            }
            throw $e;
        }
    
    } else {
        try {
            conn()->beginTransaction();
            $stmt = conn()->prepare("
            UPDATE comments SET imgUrl = ?, Text = ?  WHERE id = ?");
            $stmt->execute([ $file, $textarea, $comId]);
            conn()->commit();
            
        } catch(Exception $e) {
            if (conn() -> inTransaction()) {
                conn()->rollBack();
            }
            throw $e;
        }
    }
};

function deleteCom($comId) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        UPDATE comments SET Suppression = NOW()  WHERE id = ?");
        $stmt->execute([$comId]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
}

function sendReply($userId, $text, $comId) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        INSERT INTO comments(User_id, Text, ReplyTo_Id )  VALUES(?, ?, ?)");
        $stmt->execute([$userId, $text, $comId ]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};

function sendLike($comId, $userId) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        INSERT INTO like_number(ComId, UserId) VALUES(?,?)");
        $stmt->execute([$comId, $userId]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};

function dropLike($comId, $userId) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        DELETE FROM like_number WHERE comId = ? AND userId = ?");
        $stmt->execute([$comId, $userId]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};

?>