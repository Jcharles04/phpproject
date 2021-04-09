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
            	`Email`,
            	`MotDePasse`,
            	`Moderateur`,
            	`DateCreation`,
            	`Suppression`
        	FROM `groupomania`.`user`
            WHERE Email LIKE ?");
        $res = $stmt->execute([$userName]);
        if (!$res) {
            $err = error_get_last();
            error_log(print_r($err, true));
            throw new Exception("Erreur requête [" . $err['message'] . "]: " . $stmt->queryString);
        }
        $values = $stmt->fetch();
        if ($values) { //Trouvé : vérifier le MDP
            if ($pw == password_verify($pw, $values['MotDePasse'])) { //Corrrespond
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
        $stmt = conn()->prepare("SELECT * FROM user WHERE Email=?");
        $stmt->execute([$mail]);
        $user = $stmt->fetch();
        if ($user) {
            $err = error_get_last();
            error_log(print_r($err, true));
            throw new Exception("Erreur: l'email est déjà utilisé");
        } else {
            conn()->beginTransaction();
            $stmt = conn()->prepare("
            INSERT INTO user(Name, FirstName, Service, Email, MotDePasse)  VALUES(?, ?, ?, ?, ?)");
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
}

function postCom($userId, ?string $file, ?string $textarea ) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        INSERT INTO commentaire(User_id, ImgUrl, Text )  VALUES(?, ?, ?)");
        $stmt->execute([$userId, $file, $textarea ]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
};

function getOneCom($comId) {
    try {
        conn()->beginTransaction();
        $stmt = conn()->prepare("
        SELECT * FROM commentaire WHERE id = ? AND Suppression IS NULL");
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
        UPDATE commentaire SET ImgUrl = ? WHERE id = ?");
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
            UPDATE commentaire SET Text = ?  WHERE id = ?");
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
            UPDATE commentaire SET imgUrl = ?, Text = ?  WHERE id = ?");
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
        UPDATE commentaire SET Suppression = NOW()  WHERE id = ?");
        $stmt->execute([$comId]);
        conn()->commit();
        
    } catch(Exception $e) {
        if (conn() -> inTransaction()) {
            conn()->rollBack();
        }
        throw $e;
    }
}

?>