<?php

/* -------------------------------------------------------------------------- */
/*                                DB Connexion                                */
/* -------------------------------------------------------------------------- */

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

/* -------------------------------------------------------------------------- */
/*                               User Connexion                               */
/* -------------------------------------------------------------------------- */

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
            WHERE Mail LIKE ? ");
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

        conn()->beginTransaction();
        $stmt = conn()->prepare("
        UPDATE user SET Moderator = 0  WHERE id = ?");
        $stmt->execute([$userId]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};

/* -------------------------------------------------------------------------- */
/*                               User Moderator                               */
/* -------------------------------------------------------------------------- */

function getAllUser() {
    try{
        $req = conn()->query("SELECT * FROM user");
        while($users = $req->fetch()){
            renderUsers($users);
        }
    }
    catch(PDOException $e){
        die('Erreur connexion : '.$e->getMessage());
    }
}

function backUser($userId){
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        UPDATE user SET Suppression = NULL WHERE id = ?");
        $stmt->execute([$userId]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};


/* -------------------------------------------------------------------------- */
/*                              Comment Connexion                             */
/* -------------------------------------------------------------------------- */

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
        $rs = conn()->query("
            SELECT et.*, COUNT(UserId) likes, MAX(UserId = '{$_SESSION['user']['id']}' ) AS myLike FROM (
                SELECT c.id, c.User_id, c.CreationDate, c.ImgUrl, c.Text, c.Suppression, c.ReplyTo_id, c.checkedByAdmin, l.UserId, u.FirstName, u.Service FROM comments c
                LEFT JOIN like_number l ON l.ComId = c.id
                LEFT JOIN USER u ON u.id= c.User_id
                WHERE c.Suppression IS NULL AND ReplyTo_id IS NULL
            ) et
            GROUP BY et.id
            ORDER BY CreationDate DESC LIMIT 5");
        $comments = [];
        while($comment = $rs->fetch()){
            $comment['replies'] = [];
            
            $replies = conn()->query("
            SELECT et.*, COUNT(UserId) likes, MAX(UserId = '{$_SESSION['user']['id']}' ) AS myLike FROM (
                SELECT c.id, c.User_id, c.CreationDate, c.ImgUrl, c.Text, c.Suppression, c.ReplyTo_id, c.checkedByAdmin, l.UserId, u.FirstName, u.Service FROM comments c
                LEFT JOIN like_number l ON l.ComId = c.id
                LEFT JOIN USER u ON u.id= c.User_id
                WHERE c.Suppression IS NULL AND ReplyTo_id = '{$comment['id']}'
            ) et
            GROUP BY et.id
            ORDER BY CreationDate DESC");
            while($reply = $replies->fetch()){
                $comment['replies'][] = $reply;    
            }
            $comment["NbOfResponse"] = count($comment['replies']);
            $comments[] = $comment;
        }
        //Count responses
        $rs = conn()->query("
            SELECT c.id, COUNT(r.id) replies
            FROM comments c
            LEFT JOIN comments r ON r.ReplyTo_id = c.id AND r.Suppression IS NULL
            WHERE c.ReplyTo_id IS NULL AND c.Suppression IS NULL
            GROUP BY c.id
            ORDER BY c.CreationDate DESC");
        while($ccount = $rs->fetch()) {
            $id = $ccount['id'];
            foreach ($comments as &$comment) {
                if ($comment["id"] == $id) {
                    $comment['NbOfResponse'] = $ccount["replies"];
                    break;
                }
            }
        }
        return $comments;
    }
    catch(PDOException $e){
        die('Erreur connexion : '.$e->getMessage());
    }
}

function getNextCom($comId) {
    try{
        $rs = conn()->query("
            SELECT et.*, COUNT(UserId) likes, MAX(UserId = '{$_SESSION['user']['id']}' ) AS myLike FROM (
                SELECT c.id, c.User_id, c.CreationDate, c.ImgUrl, c.Text, c.Suppression, c.ReplyTo_id, c.checkedByAdmin, l.UserId, u.FirstName, u.Service FROM comments c
                LEFT JOIN like_number l ON l.ComId = c.id
                LEFT JOIN USER u ON u.id= c.User_id
                WHERE c.Suppression IS NULL AND ReplyTo_id IS NULL AND c.id < {$comId}
            ) et
            GROUP BY et.id
            ORDER BY CreationDate DESC LIMIT 5");
        $comments = [];
        while($comment = $rs->fetch()){
            $comment['replies'] = [];
            
            $replies = conn()->query("
            SELECT et.*, COUNT(UserId) likes, MAX(UserId = '{$_SESSION['user']['id']}' ) AS myLike FROM (
                SELECT c.id, c.User_id, c.CreationDate, c.ImgUrl, c.Text, c.Suppression, c.ReplyTo_id, c.checkedByAdmin, l.UserId, u.FirstName, u.Service FROM comments c
                LEFT JOIN like_number l ON l.ComId = c.id
                LEFT JOIN USER u ON u.id= c.User_id
                WHERE c.Suppression IS NULL AND ReplyTo_id = '{$comment['id']}'
            ) et
            GROUP BY et.id
            ORDER BY CreationDate DESC");
            while($reply = $replies->fetch()){
                $comment['replies'][] = $reply;    
            }
            $comment["NbOfResponse"] = count($comment['replies']);
            $comments[] = $comment;
        }
        //Count responses
        $rs = conn()->query("
            SELECT c.id, COUNT(r.id) replies
            FROM comments c
            LEFT JOIN comments r ON r.ReplyTo_id = c.id AND r.Suppression IS NULL
            WHERE c.ReplyTo_id IS NULL AND c.Suppression IS NULL
            GROUP BY c.id
            ORDER BY c.CreationDate DESC");
        while($ccount = $rs->fetch()) {
            $id = $ccount['id'];
            foreach ($comments as &$comment) {
                if ($comment["id"] == $id) {
                    $comment['NbOfResponse'] = $ccount["replies"];
                    break;
                }
            }
        }
        return $comments;
    }
    catch(PDOException $e){
        die('Erreur connexion : '.$e->getMessage());
    }
};



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
};

function responsesCom($comId){
    try {
        $req = conn()->prepare("
            SELECT et.*, COUNT(UserId) likes, MAX(UserId = '{$_SESSION['user']['id']}' ) AS myLike FROM (
            SELECT c.id, c.User_id, c.CreationDate, c.ImgUrl, c.Text, c.Suppression, c.ReplyTo_id, c.checkedByAdmin, l.UserId, u.FirstName, u.Service FROM comments c
            LEFT JOIN like_number l ON l.ComId = c.id
            LEFT JOIN USER u ON u.id= c.User_id
            WHERE c.Suppression IS NULL AND c.id = '{$comId}'
            ) et
            GROUP BY et.id
            ORDER BY CreationDate DESC"); 
        $req->execute([$comId]);
        $comment = $req->fetch();
        $comment['replies'] = [];
        $replies = conn()->query(" 
            SELECT et.*, COUNT(UserId) likes, MAX(UserId = '{$_SESSION['user']['id']}' ) AS myLike FROM (
            SELECT c.id, c.User_id, c.CreationDate, c.ImgUrl, c.Text, c.Suppression, c.ReplyTo_id, c.checkedByAdmin, l.UserId, u.FirstName, u.Service FROM comments c
            LEFT JOIN like_number l ON l.ComId = c.id
            LEFT JOIN USER u ON u.id= c.User_id
            WHERE c.Suppression IS NULL AND ReplyTo_id = '{$comId}'
            ) et
            GROUP BY et.id
            ORDER BY CreationDate DESC");
        while($reply = $replies->fetch()){
            $comment['replies'][] = $reply;    
        }
        return $comment;
    }
        
     catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};


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
};

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

/* -------------------------------------------------------------------------- */
/*                                Com Moderator                               */
/* -------------------------------------------------------------------------- */

function checkedByAdmin($comId ,$userId) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        UPDATE comments SET checkedByAdmin = 1 ");
        $stmt->execute([$comId]);
        conn()->commit();

        conn()->beginTransaction();
        $stmt = conn()->prepare("
        UPDATE user SET ModerationDate = NOW()  WHERE id = ?");
        $stmt->execute([$userId]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};

function moderationDate() {
    try {
        $dates = conn()->query("SELECT * FROM user WHERE user.ModerationDate = 
        (SELECT MAX(ModerationDate) FROM user)");
        while($date = $dates->fetch()){
            checkModeration($date);
        }
    
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
}

?>