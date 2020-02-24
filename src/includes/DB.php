<?php

/**
 * "Lazy" database class. Database connection will not be estabilished until it is needed.
 */
class DB
{
    private static $source;
    private static $username;
    private static $password;
    private static $options;
    private static $pdo;

    static function configure($source, $username, $password, $options = null)
    {
        self::$source = $source;
        self::$username = $username;
        self::$password = $password;
        self::$options = $options;
        self::$pdo = null;
    }

    static function pdo()
    {
        if (!isset(self::$pdo) || self::$pdo == null) {
            self::$pdo = new PDO(self::$source, self::$username, self::$password, self::$options);
        }

        // TODO: Handle exception

        return self::$pdo;
    }

    /**
     * Query database and return all rows.
     *
     * Example:
     *    DB::query('SELECT * FROM Advisor WHERE AEmail = ? OR AName = ?', 'alex@ewu.edu', 'Alex');
     *    DB::query('SELECT * FROM Advisor WHERE AEmail = :email', ['email' => 'alex@ewu.edu']); // Named parameters
     */
    static function query($query, ...$params)
    {
        return self::exec($query, ...$params)->fetchAll();
    }
    static function query2($query, $table, $cond, ...$params)
    {
        return self::exec2($query, $table, $cond, ...$params)->fetchAll();
    }
    /**
     * Query database and return a single row.
     */
    static function querySingle($query, ...$params)
    {
        return self::exec($query . " LIMIT 1", ...$params)->fetch();
    }

    /**
     * Select all rows (all columns) from $table satisfying $conditions.
     *
     * Example: DB::select('Advisor, 'AEmail = ? OR AName = ?', 'alex@ewu.edu', 'Alex');
     */
    static function select($table, $conditions = '', ...$params)
    {
        $where = empty($conditions) ? '' : 'WHERE';
        return self::query("SELECT * FROM $table $where $conditions", ...$params);
    }

    /**
     * Select a single row (all columns) from $table satisfying $conditions.
     *
     * Example: DB::selectSingle('Advisor, 'AEmail = ? OR AName = ?', 'alex@ewu.edu', 'Alex');
     */
    static function selectSingle($table, $conditions = '', ...$params)
    {
        $where = empty($conditions) ? '' : 'WHERE';
        return self::querySingle("SELECT * FROM $table $where $conditions", ...$params);
    }

    /**
     * Count number of rows satisfying $conditions.
     * WARNING: Do not allow unescaped user input in $table or $conditions.
     *
     * Example: DB::count("Advisor", "AEmail = ?", 'email@ewu.edu');
     */
    static function count($table, $conditions = '', ...$params)
    {
        $where = empty($conditions) ? '' : 'WHERE';
        return (int)self::exec("SELECT COUNT(*) FROM $table $where $conditions", ...$params)->fetchColumn();
    }

    /**
     * Check if there are any rows in $table satisfying $conditions.
     *
     * Example: if (DB::contains("Advisor", "AEmail = ?", 'email@ewu.edu')) { ... }
     */
    static function contains($table, $conditions = '', ...$params)
    {
        return self::count($table, $conditions, ...$params) > 0;
    }

    /**
     * Execute a query and return number of affected rows. Use this function for DELETE, INSERT, or UPDATE statements.
     */
    static function execute($query, ...$params)
    {
        return self::exec($query, ...$params)->rowCount();
    }

    /**
     * Insert a row into $table. $values is an associative array where each key is a column name in the table.
     * Returns true if the row was inserted successfully; false otherwise.
     *
     * Example: DB::insert('advisor', ['AEmail' => 'advisor@ewu.edu', 'AName' => 'Advisor']);
     */
    static function insert($table, $values)
    {
        $valuesFragment = self::makeValueList($values);
        return self::execute("INSERT INTO $table $valuesFragment", array_values($values)) > 0;
    }

    static function replace($table, $values)
    {
        $valuesFragment = self::makeValueList($values);
        return self::execute("REPLACE INTO $table $valuesFragment", array_values($values)) > 0;
    }

    static function update($table, $values, $where, ...$params)
    {
        $columns = [];

        foreach ($values as $k => $v) {
            $columns[] = "`$k` = ?";
        }

        $columns = implode(', ', $columns);

        return self::execute(
                "UPDATE $table SET $columns WHERE $where",
                array_merge(array_values($values), $params)
            ) > 0;
    }

    /**
     * Execute a query and return a PDOStatement object.
     */
    private static function exec($query, ...$params)
    {
        // Allow associative arrays
        if (sizeof($params) == 1 && is_array($params[0])) {
            $params = $params[0];
        }
        $stmt = self::pdo()->prepare($query);
        $stmt->execute($params);

        return $stmt;
    }

    private static function exec2($query, $table, $cond, ...$params)
    {
        // Allow associative arrays
        if (sizeof($params) == 1 && is_array($params[0])) {
            $params = $params[0];
        }
        #echo($query.'<br>');
        #echo($table.'<br>');
        #echo($cond.'<br>');
        #echo($params[0].'<br>');
        $q = $query.$table.$cond;
        $stmt = self::pdo()->prepare($q);
        $stmt->execute($params);

        return $stmt;
    }


    private static function makeValueList($values)
    {
        // Keys = column names
        $columns = array_keys($values);

        // Wrap each column in backticks to allow spaces or reserved keywords in column names
        $columns = array_map(
            function ($k) {
                return "`$k`";
            },
            $columns
        );

        $columns = implode(', ', $columns);

        // Fill $valuePlaceholders with '?, ?, ?, ... , ?'
        $valuePlaceholders = implode(', ', array_fill(0, count($values), '?'));

        return "($columns) VALUES ($valuePlaceholders)";
    }
}