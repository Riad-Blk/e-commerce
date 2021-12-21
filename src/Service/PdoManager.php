<?php

namespace App\Service;

use PDO;
use PDOException;

final class PdoManager
{
    protected $pdo;

    /**
     * Void __construct()
     * 
     * @param string $host host+dbname+dbport
     * @param string $user username
     * @param string $pass password
     */
    public function __construct(string $host, string $user, string $pass)
    {
        try {
            $this->pdo = new PDO($host, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Échec lors de la connexion : '.$e->getMessage();
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $value comment
     *
     * @return void
     */
    private function _params($value)
    {
        if (is_int($value)) {
            // INTEGER
            $param = PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            // BOOLEEN
            $param = PDO::PARAM_BOOL;
        } elseif (is_null($value)) {
            // NULL
            $param = PDO::PARAM_NULL;
        } elseif (is_string($value)) {
            // STRING
            $param = PDO::PARAM_STR;
        } elseif (is_array($value)) {
            // ARRAY
            $param = PDO::PARAM_STR;
        } else {
            $param = PDO::PARAM_NULL;
        }

        return $param;
    }

    /**
     *  Undocumented function
     *
     * DOCUMENTATION :
     *
     * PDO::FETCH_ASSOC: retourne un tableau indexé par le nom
     * de la colonne comme retourné dans le jeu de résultats
     *
     * PDO::FETCH_BOTH (défaut): retourne un tableau indexé par les noms
     * de colonnes et aussi par les numéros de colonnes,
     * commençant à l'index 0, comme retournés dans le jeu de résultats
     *
     * PDO::FETCH_BOUND: retourne TRUE et assigne les valeurs des colonnes
     * de votre jeu de résultats dans les variables PHP à laquelle
     * elles sont liées avec la méthode PDOStatement::bindColumn()
     *
     * PDO::FETCH_CLASS: retourne une nouvelle instance de la classe demandée,
     * liant les colonnes du jeu de résultats aux noms des propriétés
     * de la classe et en appelant le constructeur par la suite, sauf
     * si PDO::FETCH_PROPS_LATE est également donné. Si fetch_style
     * inclut PDO::FETCH_CLASS
     * (c'est-à-dire PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE),
     * alors le nom de la classe est déterminé à partir d'une
     * valeur de la première colonne.
     *
     * PDO::FETCH_INTO : met à jour une instance existante de la classe demandée,
     * liant les colonnes du jeu de résultats
     * aux noms des propriétés de la classe
     *
     * PDO::FETCH_LAZY : combine PDO::FETCH_BOTH et PDO::FETCH_OBJ, créant ainsi
     * les noms des variables de l'objet, comme elles sont accédées
     *
     * PDO::FETCH_NAMED : retourne un tableau de la même forme que PDO::FETCH_ASSOC,
     * excepté que s'il y a plusieurs colonnes avec les mêmes noms,
     * la valeur pointée par cette clé sera un tableau de toutes les valeurs
     * de la ligne qui a ce nom comme colonne.
     *
     * PDO::FETCH_NUM : retourne un tableau indexé par le numéro de la colonne
     * comme elle est retourné dans votre jeu de résultat, commençant à 0
     *
     * PDO::FETCH_OBJ : retourne un objet anonyme avec les noms de propriétés
     * qui correspondent aux noms des colonnes retournés dans le jeu de résultats
     *
     *
     * PDO::FETCH_PROPS_LATE : lorsqu'il est utilisé avec PDO::FETCH_CLASS,
     * le constructeur de la classe est appelé avant que
     * les propriétés ne soient assignées à partir
     * des valeurs de colonne respectives.
     *
     * @param [type] $fetch comment
     *
     * @return void
     */
    protected function fetchPDO($fetch)
    {
        switch ($fetch) {
        case 'ASSOC':
            return PDO::FETCH_ASSOC;
            break;
        case 'BOTH':
            return PDO::FETCH_BOTH;
            break;
        case 'BOUND':
            return PDO::FETCH_BOUND;
            break;
        case 'CLASS':
            return PDO::FETCH_CLASS;
            break;
        case 'INTO':
            return PDO::FETCH_INTO;
            break;
        case 'LAZY':
            return PDO::FETCH_LAZY;
            break;
        case 'NAMED':
            return PDO::FETCH_NAMED;
            break;
        case 'NUM':
            return PDO::FETCH_NUM;
            break;
        case 'OBJ':
            return PDO::FETCH_OBJ;
            break;
        case 'PROPS_LATE':
            return PDO::FETCH_PROPS_LATE;
            break;
        default:
            return PDO::FETCH_BOTH;
            break;
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $requete comment
     * @param string $fetch   comment
     *
     * @return array
     */
    public function select($requete, string $fetch = null): array
    {
        $query = $this->pdo->query($requete);
        $fetch = $this->fetchPDO($fetch);
        $results = $query->fetchAll($fetch);

        return $results;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requete comment
     * @param string $fetch   comment
     *
     * @return array
     */
    public function selectOne($requete, string $fetch = null): array
    {
        $query = $this->pdo->query($requete);
        $fetch = $this->fetchPDO($fetch);
        $results = $query->fetchAll($fetch);

        return !empty($results) ? $results[0] : [];
    }

    /**
     * Undocumented function
     *
     * *    utilisation :
     * *
     * *    $data = [
     * *        'name' => 'franck',
     * *        'surname' => 'ferrero',
     * *        'email' => 'ferrerofranck@yahoo.fr',
     * *    ];
     * *
     * *    $bdd = new bdd();
     * *    $result = $bdd->insert('nom_table', $data);
     * *
     * * ///////////////////////////////////////////////////////////////////////
     * *  $sql = "INSERT INTO users (name, surname, email) VALUES (?, ?, ?)";
     * * ///////////////////////////////////////////////////////////////////////
     *
     * @param string $table comment
     * @param array  $data  comment
     *
     * @return boolean
     */
    public function insert(string $table, array $data): bool
    {
        // REQUETE SQL
        $sql = 'INSERT INTO ';
        $sql .= $table;
        $sql .= ' (';
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i == 0) {
                $sql .= $key;
            } else {
                $sql .= ', '.$key;
            }
            ++$i;
        }
        $sql .= ') VALUES (';
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i == 0) {
                $sql .= '?';
            } else {
                $sql .= ', ?';
            }
            ++$i;
        }
        $sql .= ')';

        // PREPARE SQL
        $stmt = $this->pdo->prepare($sql);

        // INCREMENTATION DES PARAMETRES
        // VALEUR INITIAL 1
        $i = 1;

        // PARAMETRES SET
        foreach ($data as $key => $value) {
            $param = $this->_params($value);
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $stmt->bindValue($i, $value, $param);
            ++$i;
        }

        // EXECUTE
        return $stmt->execute();
    }

    /**
     * Undocumented function
     *
     * *    utilisation :
     * *
     * *    $data = [
     * *        'name' => 'franck',
     * *        'prenom' => 'ferrero',
     * *        'adresse' => 'rue de strasbourg',
     * *    ];
     * *
     * *    $where = [
     * *        'id' => 11,
     * *   ];
     * *
     * *    $bdd = new bdd();
     * *    $result = $bdd->update('nom_table', $data, $where);
     * *
     * * ///////////////////////////////////////////////////////////////////////
     * * UPDATE paople SET name = ? WHERE id = ? AND name = ?
     * * bindParam(1, clavier, PDO::PARAM_STR)
     * * bindParam(2, 7, PDO::PARAM_INT)
     * * bindParam(3, franck, PDO::PARAM_STR)
     * * ///////////////////////////////////////////////////////////////////////
     *
     * @param string $table comment
     * @param array  $data  comment
     * @param array  $where comment
     *
     * @return boolean
     */
    public function update(string $table, array $data, array $where): bool
    {
        // REQUETE SQL
        $sql = 'UPDATE ';
        $sql .= $table;
        $sql .= ' SET ';
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i == 0) {
                $sql .= $key.' = ?';
            } else {
                $sql .= ', '.$key.' = ?';
            }
            ++$i;
        }
        $sql .= ' WHERE ';
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $sql .= $key.' = ?';
            } else {
                $sql .= ' AND '.$key.' = ?';
            }
            ++$i;
        }

        // PREPARE SQL
        $stmt = $this->pdo->prepare($sql);

        // INCREMENTATION DES PARAMETRES
        // VALEUR INITIAL 1
        $i = 1;

        // PARAMETRES SET
        foreach ($data as $key => $value) {
            $param = $this->_params($value);
            $stmt->bindValue($i, $value, $param);
            ++$i;
        }

        // PARAMETRES WHERE
        foreach ($where as $key => $value) {
            $param = $this->_params($value);
            $stmt->bindValue($i, $value, $param);
            ++$i;
        }

        // EXECUTE
        return $stmt->execute();
    }

    /**
     * Undocumented function
     *
     * *    utilisation :
     * *
     * *    $data = [
     * *        'name' => 'franck',
     * *        'prenom' => 'ferrero',
     * *    ];
     * *
     * *
     * *    $bdd = new bdd();
     * *    $result = $bdd->delete('nom_table', $data);
     * *
     * * ///////////////////////////////////////////////////////////////////////
     * * $sql = 'DELETE FROM table WHERE id = ?';
     * * $sql = 'DELETE FROM table WHERE name = ? AND prenom = ?';
     * * data = [
     * *    'name' => 'franck',
     * *    'prenom' => "ferrero",
     * * ];
     * * bindParam(1, franck, PDO::PARAM_STR)
     * * bindParam(2, ferrero, PDO::PARAM_STR)
     * * ///////////////////////////////////////////////////////////////////////
     *
     * @param string $table comment
     * @param array  $data  comment
     *
     * @return boolean
     */
    public function delete(string $table, array $data): bool
    {
        // REQUETE SQL
        $sql = 'DELETE FROM ';
        $sql .= $table;
        $sql .= ' WHERE ';

        $i = 0;
        foreach ($data as $key => $value) {
            if ($i == 0) {
                $sql .= $key.' = ?';
            } else {
                $sql .= ' AND '.$key.' = ?';
            }
            ++$i;
        }

        // PREPARE SQL
        $stmt = $this->pdo->prepare($sql);

        // INCREMENTATION DES PARAMETRES
        // VALEUR INITIAL 1
        $i = 1;

        // PARAMETRES SET
        foreach ($data as $key => $value) {
            $param = $this->_params($value);
            $stmt->bindValue($i, $value, $param);
            ++$i;
        }

        // EXECUTE
        return $stmt->execute();
    }
}
