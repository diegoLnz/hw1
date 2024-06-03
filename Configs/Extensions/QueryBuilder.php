<?php
require_once "GenericExtensions.php";
require_once "Diagnostics.php";

abstract class OrderBy {
    const ASC = 'ASC';
    const DESC = 'DESC';
}

abstract class SqlOperators {
    const EQUALS = '=';
    const NOT_EQUAL = '<>';
    const GREATER_THAN = '>';
    const LESS_THAN = '<';
    const GREATER_THAN_OR_EQUAL = '>=';
    const LESS_THAN_OR_EQUAL = '<=';
    const LIKE = 'LIKE';
    const NOT_LIKE = 'NOT LIKE';
    const IN = 'IN';
    const NOT_IN = 'NOT IN';
    const BETWEEN = 'BETWEEN';
    const IS_NULL = 'IS NULL';
    const IS_NOT_NULL = 'IS NOT NULL';
}

abstract class LogicalOperators {
    const LOGICAL_AND = "AND";
    const LOGICAL_OR = 'OR';
}

class QueryBuilder {
    private $conn;
    private $table;
    private $select = '*';
    private $where = '';
    private $groupBy = '';
    private $orderBy = '';
    private $having = '';
    private $limit = '';
    private $offset = '';
    private $join = '';
    private $updateSet = '';
    private $delete = '';
    private $isFirstWhere = true;

    /**
     * Costruttore di query
     * @param mysqli $conn La connection
     * @param string $table La tabella del DB sulla quale si eseguirà la query
     */
    public function __construct($conn, $table) {
        $this->conn = $conn;
        $this->table = $table;
    }

    /**
     * SQL SELECT
     * @param string $properties Le proprietà da estrarre dalla table
     */
    public function select(string $properties): QueryBuilder
    {
        $this->select = !GenericExtensions::isNullOrEmptyString($properties) 
            ? $properties 
            : '#';

        return $this;
    }

    /**
     * SQL WHERE
     * @param string $column Colonna di riferimento (Es: "Column1")
     * @param string $operator Operatore di confronto (Es: "<", ">", SqlOperators::EQUALS)
     * @param string $value Valore da confrontare (Es: "Value1")
     * @param string $logicalOperator In caso di where precedenti ad essa, specificare concatenare con "AND" o "OR"
     */
    public function where($column, $operator, $value, $logicalOperator = '&&') {
        if (!$this->isFirstWhere) {
            $this->where .= " $logicalOperator ";
        }
        $this->isFirstWhere = false;
        $this->where .= "$column $operator '$value'";
        return $this;
    }

    /**
     * Apri un gruppo di where cominciate da una parentesi
     */
    public function beginWhereGroup($logicalOperator = 'AND') {
        if (!$this->isFirstWhere) {
            $this->where .= " $logicalOperator ";
        }
        $this->where .= "(";
        $this->isFirstWhere = true;
        return $this;
    }
    
    /**
     * Chiudi un gruppo di where cominciate da una parentesi
     */
    public function endWhereGroup() {
        $this->where .= ")";
        return $this;
    }

    /**
     * SQL GROUP BY
     * @param string $column Colonna di riferimento (Es: "Column1")
     */
    public function groupBy($column) {
        $this->groupBy = "GROUP BY $column";
        return $this;
    }

    /**
     * SQL ORDER BY
     * @param string $column Colonna di riferimento (Es: "Column1")
     * @param string $direction Verso dell' ordinamento (Es: "ASC", "DESC")
     */
    public function orderBy($column, $direction = 'ASC') {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    /**
     * SQL HAVING
     * @param string $column Colonna di riferimento (Es: "Column1")
     * @param string $operator Operatore di confronto (Es: "<", ">", SqlOperators::EQUALS)
     * @param string $value Valore da confrontare (Es: "Value1")
     * @param string $logicalOperator In caso di where precedenti ad essa, specificare concatenare con "AND" o "OR"
     */
    public function having($column, $operator, $value, $logicalOperator = 'AND') {
        if ($this->having !== '') {
            $this->having .= " $logicalOperator ";
        }
        $this->having .= "$column $operator '$value'";
        return $this;
    }

    /**
     * SQL LIMIT
     * @param int $limit Valore da passare al LIMIT
     */
    public function limit($limit) {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    /**
     * SQL OFFSET
     * @param int $offset Valore da passare all' OFFSET
     */
    public function offset($offset) {
        $this->offset = "OFFSET $offset";
        return $this;
    }

    /**
     * SQL JOIN
     * @param string $table Tabella sulla quale eseguire la JOIN
     * @param string $condition Le chiavi sulle quali effettuare la JOIN (Es: "Key1 = Key2")
     * @param string $type Tipo di JOIN (Es: INNER, OUTER, LEFT, RIGHT)
     */
    public function join($table, $condition, $type = '') {
        $this->join .= " $type JOIN $table ON $condition";
        return $this;
    }

    /**
     * SQL UPDATE
     * @param $data Da passare in formato ["Proprietà1" => "Valore1", "Proprietà2" => "Valore2", ...]
     */
    public function update($data) {
        foreach ($data as $column => $value) {
            $this->updateSet .= "$column = '$value', ";
        }
        return $this;
    }

    /**
     * SQL DELETE
     * No params
     */
    public function delete() {
        $this->delete = "DELETE";
        return $this;
    }

    /**
     * Esegue le query SELECT
     * No params
     */
    public function getQuery() {
        $query = "SELECT $this->select
                  FROM $this->table";

        if ($this->join !== '') {
            $query .= $this->join;
        }

        if ($this->where !== '') {
            $query .= " WHERE $this->where";
        }

        if ($this->groupBy !== '') {
            $query .= " $this->groupBy";
        }

        if ($this->having !== '') {
            $query .= " HAVING $this->having";
        }

        if ($this->orderBy !== '') {
            $query .= " $this->orderBy";
        }

        if ($this->limit !== '') {
            $query .= " $this->limit";
        }

        if ($this->offset !== '') {
            $query .= " $this->offset";
        }

        $result = $this->runQuery($query);
        $this->clearParams();
        return $result;
    }

    /**
     * Esegue le query UPDATE e DELETE
     * No params
     */
    public function execute() {
        $query = "";
        
        if ($this->updateSet !== '') {
            $query = "UPDATE $this->table SET " . rtrim($this->updateSet, ', ');
        } 
        elseif ($this->delete !== '') {
            $query = "DELETE FROM $this->table";
        }

        if ($this->join !== '') {
            $query .= $this->join;
        }
        
        if ($this->where !== '') {
            $query .= " WHERE $this->where";
        }
        
        $result = $this->runQuery($query);
        $this->clearParams();
        return $result;
    }

    /**
     * SQL INSERT INTO, Viene eseguita da sola senza ausilio di *get()* o *execute()*
     * @param array $data Da passare in formato ["Column1" => "Value1", "Column2" => "Value2", ...]
     */
    public function insert($data) {
        $columns = implode(',', array_keys($data));
        $values = "'" . implode("','", array_values($data)) . "'";
        
        $query = "INSERT INTO $this->table($columns)
                  VALUES ($values)";
        
        $result = $this->runQuery($query);
        
        $this->clearParams();
        return $result;
    }

    private function runQuery($query)
    {
        try
        {
            $result = mysqli_query($this->conn, $query);
            if (!$result) {
                die("Errore: " . mysqli_error($this->conn));
            }

            return $result;
        } catch (Exception $e) {
            Diagnostics::traceMessage($e->getMessage(), TraceLevel::Error, __METHOD__);
        }
    }

    private function clearParams() {
        $this->where = '';
        $this->groupBy = '';
        $this->orderBy = '';
        $this->having = '';
        $this->limit = '';
        $this->offset = '';
        $this->join = '';
        $this->updateSet = '';
        $this->delete = '';
        $this->isFirstWhere = false;
    }

}

