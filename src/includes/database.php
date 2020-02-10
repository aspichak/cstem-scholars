<?php
/**
  * "Lazy" database class. Database connection will not be estabilished until it is needed.
  */
class DB {
    private static $source;
    private static $username;
    private static $password;
    private static $options;
    private static $pdo;

    static function configure($source, $username, $password, $options = NULL) {
        self::$source   = $source;
        self::$username = $username;
        self::$password = $password;
        self::$options  = $options;
        self::$pdo      = NULL;
    }

    static function pdo() {
        if (!isset(self::$pdo) || self::$pdo == NULL)
            self::$pdo = new PDO(self::$source, self::$username, self::$password, self::$options);

        // TODO: Handle exception

        return self::$pdo;
    }

    /**
      * Execute a query and return number of affected rows. Use this function 
      * for DELETE, INSERT, or UPDATE statements.
      */
    static function execute($query, ...$params) {
        return self::exec($query, $params)->rowCount();
    }

    /**
      * Query database and return all rows.
      */
    static function query($query, ...$params) {
        return self::exec($query, $params)->fetchAll();
    }

    /**
      * Query database and return a single row.
      */
    static function querySingle($query, ...$params) {
        return self::exec($query . " LIMIT 1", $params)->fetch();
    }

    /**
      * Count number of rows satisfying $conditions.
      * WARNING: Do not allow unescaped user input in $table or $conditions.
      */
    static function count($table, $conditions = "", ...$params) {
        return self::exec("SELECT COUNT(*) FROM $table WHERE $conditions", $params)->fetchColumn();
    }

    /**
      * Execute a query and return a PDOStatement object.
      */
    private static function exec($query, $params) {
        // Allow associative arrays
        if (sizeof($params) == 1 && is_array($params[0]))
            $params = $params[0];

        $stmt = self::pdo()->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
}