<?php
require 'Extensions.php';
require 'QueryBuilder.php';

class WhereCondition {
    public $column;
    public $comparingOperator;
    public $value;
    public $logicOperator;

    /**
     * Costruisce una nuova istanza di WhereCondition.
     * @param string $column Nome del campo su cui applicare la condizione
     * @param string $comparingOperator Operatore di confronto per la condizione (ad esempio '=', '>', '<')
     * @param mixed $value Valore da confrontare
     * @param string $logicalOperator Operatore logico per la concatenazione delle condizioni (AND o OR) (Default 'AND')
     */
    public function __construct($column, $comparingOperator, $value, $logicalOperator = '&&') {
        $this->column = $column;
        $this->comparingOperator = $comparingOperator;
        $this->value = $value;
        $this->logicOperator = $logicalOperator;
    }

}

class Repository {

    /**
     * Recupera un singolo record da una tabella del database utilizzando l'ID specificato.
     * @param mysqli $conn Connessione al database.
     * @param string $table Nome della tabella da cui recuperare il record.
     * @param int $idToFind ID del record da cercare.
     * @return bool|mysqli_result Restituisce il risultato della query se il record viene trovato, altrimenti restituisce `false`.
     */
    public static function getById($conn, $table, $id) {

        $queryOnDb = new QueryBuilder($conn, $table);
        $result = $queryOnDb
        ->where("id", SqlOperators::EQUALS, $id)
        ->getQuery();

        return $result;

    }

    /**
     * Ritorna la lista contenente tutti gli elementi di una tabella
     * @param mysqli $conn Connessione con il database
     * @param string $table Nome della tabella nel database dalla quale ricavare la lista
     * @param WhereCondition[] $whereConditions Array associativo delle condizioni WHERE (opzionale). Ogni chiave rappresenta il nome della colonna, e il valore è un array associativo che contiene il valore della condizione e l'operatore logico.
     * @return bool|mysqli_result Risultato della query
     *
     * **Esempio di utilizzo:**
     * 
     * $whereConditions = [
     * 
     *     new WhereCondition('id', '=', 10),
     * 
     *     new WhereCondition('status', '=', 'active', 'OR'),
     * 
     *     new WhereCondition('name', '=', 'myName', 'AND')
     * 
     * ];
     * 
     * $result = Repository::list($conn, "nome_tabella", $whereConditions);
     */
    public static function getAll($conn, $table, $whereConditions = []){
        $queryOnDb = new QueryBuilder($conn, $table);

        foreach ($whereConditions as $condition)
            $queryOnDb->where($condition->column, $condition->comparingOperator, $condition->value, $condition->logicOperator);

        $result = $queryOnDb->getQuery();

        return $result;
    }

    /**
     * Salva o aggiorna un oggetto nel database.
     * Se l'oggetto ha un ID valido e esiste già nel database, viene eseguito un aggiornamento, altrimenti viene eseguito un inserimento.
     * 
     * @param mysqli $conn Connessione con il database
     * @param string $table Nome della tabella nel database
     * @param object $object Oggetto da salvare o aggiornare
     * 
     * @return bool|int Restituisce true se l'operazione è riuscita. 
     * Se l'operazione è stata un inserimento, restituisce l'ID dell'oggetto inserito. 
     * Se l'operazione è stata un aggiornamento, restituisce l'ID dell'oggetto aggiornato.
     * Se l'operazione non è riuscita, restituisce false.
     */
    public static function saveOrUpdate($conn, $table, $object): int
    {

        mysqli_autocommit($conn, false);

        try
        {
            $properties = get_object_vars($object);

            //Update
            if(self::hasValidId($properties) && self::alreadyExists($conn, $table, $properties["id"]))
            {
                $id = $properties['id'];
                unset($properties['id']);
                
                $updateQuery = new QueryBuilder($conn, $table);

                $updateQuery
                ->where('id', SqlOperators::EQUALS, $id)
                ->update($properties);

                $result = $updateQuery->execute();

                if (!$result) {
                    throw new Exception("Errore durante l'aggiornamento del record.");
                }

                $last_insert_id = $id;
                Diagnostics::traceMessage("Un utente ha loggato correttamente nel sito.", TraceLevel::Info, __METHOD__);
            }
            //Save
            else
            {
                $insertQuery = new QueryBuilder($conn, $table);
                $result = $insertQuery->insert($properties);

                if(!$result) {
                    throw new Exception("Errore durante l'inserimento del record.");
                }

                $last_insert_id = mysqli_insert_id($conn);

                Diagnostics::traceMessage("Un utente si è registrato correttamente al sito.", TraceLevel::Info, __METHOD__);
            }

            mysqli_commit($conn);
            mysqli_autocommit($conn, true);

            return $last_insert_id;

        }catch(Exception $e)
        {
            mysqli_rollback($conn);
            mysqli_autocommit($conn, true);
            Diagnostics::traceMessage($e->getMessage(), TraceLevel::Error, __METHOD__);

            return false;
        }

    }

    /**
     * Verifica se un oggetto ha un ID valido.
     * @param array $properties Array delle proprietà dell'oggetto
     * @return bool True se l'oggetto ha un ID valido, altrimenti False
     */
    private static function hasValidId($properties){
        return isset($properties['id']) && $properties['id'];
    }

    /**
     * Verifica se esiste un record con l'ID specificato nella tabella del database.
     * @param mysqli $conn Connessione con il database
     * @param string $table Nome della tabella nel database
     * @param int $id ID del record da verificare
     * @return bool True se il record esiste, altrimenti False
     */
    private static function alreadyExists($conn, $table, $id){
        $queryOnDb = new QueryBuilder($conn, $table);
        
        $result = $queryOnDb
        ->where('id', SqlOperators::EQUALS, $id)
        ->getQuery();

        return $result != false;
    }

}