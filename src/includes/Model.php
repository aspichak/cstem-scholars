<?php

abstract class Model
{
    protected static $table = null;
    protected static $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = [];

    public function __construct($form = [])
    {
        assert(
            empty(array_intersect(static::fillable(), $this->guarded)),
            'Fillable and guarded columns cannot intersect'
        );

        // Initialize the fields inside the object to null
        foreach ($this->columns() as $f) {
            $this->$f = $this->$f ?? null;
        }

        // Fill out the fields from the provided form
        $this->fill($form);
    }

    public static function table()
    {
        return static::$table ?? static::class;
    }

    /**
     * Returns a PDOStatement object. Use PDOStatement::fetch() or
     * PDOStatement::fetchAll() to fetch objects one-by-one or all at once.
     */
    public static function select($query = '', ...$params)
    {
        $stmt = DB::select(static::table(), $query, ...$params);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
        return $stmt;
    }

    /**
     * Returns an array of Model objects.
     */
    public static function all($query = '', ...$params)
    {
        return static::select($query, ...$params)->fetchAll();
    }

    /**
     * Fetches a single object.
     */
    public static function first($query = '', ...$params)
    {
        return static::select($query, ...$params)->fetch();
    }

    /**
     * Gets a single object by primary key.
     *
     * @param $key Primary key of the desired object
     */
    public static function get($key)
    {
        return static::first(...static::queryByKey($key));
    }

    public static function delete($where = '', ...$params)
    {
        return DB::delete(static::table(), $where, ...$params);
    }

    public static function deleteByKey($key)
    {
        $numDeleted = static::delete(...static::queryByKey($key));
        assert($numDeleted <= 1, 'At most one record should be deleted');
        return $numDeleted > 0;
    }

    public static function count($where = '', ...$params)
    {
        return DB::count(static::table(), $where, ...$params);
    }

    public static function exists($where = '', ...$params)
    {
        return static::count($where, ...$params);
    }

    public static function insert($values)
    {
        return DB::insert(static::table(), $values);
    }

    public static function update($values, $where, ...$params)
    {
        return DB::update(static::table(), $values, $where, ...$params);
    }

    protected static function queryByKey($primaryKey)
    {
        if (is_array($primaryKey)) {
            // Ensure the key is complete and doesn't contain extra columns
            if (sizeof(array_diff(array_keys($primaryKey), static::$primaryKey)) != 0) {
                throw new InvalidArgumentException('Bad composite primary key');
            }

            $keys = array_map(fn($key) => "$key = ?", array_keys($primaryKey));
            $query = implode(' AND ', $keys);

            return [$query, ...array_values($primaryKey)];
        } else {
            return [static::$primaryKey . ' = ?', $primaryKey];
        }
    }

    public function key()
    {
        if (is_array(static::$primaryKey)) {
            $primaryKey = [];

            foreach (static::$primaryKey as $k) {
                $primaryKey[$k] = $this->$k;
            }

            return $primaryKey;
        } else {
            return $this->{static::$primaryKey};
        }
    }

    public function errors()
    {
        $errors = [];

        foreach ($this->fillable() as $column) {
            $validator = $this->fillable[$column] ?? null;

            if (is_callable($validator)) {
                $error = $validator($this->$column);

                if ($error) {
                    $errors[$column] = $error;
                }
            }
        }

        return $errors;
    }

    public function isValid()
    {
        return empty($this->errors());
    }

    public function fill($form)
    {
        $fields = array_intersect($this->fillable(), array_keys($form));

        foreach ($fields as $f) {
            $this->$f = $form[$f];
        }
    }

    public function save()
    {
        if (!$this->isValid()) {
            return false;
        }

        $key = $this->key();

        if (!static::exists(...static::queryByKey($key))) {
            $res = static::insert($this->values());

            // Try to get an auto_increment key
            if (is_string(static::$primaryKey)) {
                $lastInsertID = DB::pdo()->lastInsertID(static::$primaryKey);


                if ($lastInsertID != 0) {
                    $this->{static::$primaryKey} = $lastInsertID;
                }
            }

            return $res;
        } else {
            return static::update($this->values(), ...static::queryByKey($key));
        }
    }

    public function values($includePrimaryKey = true)
    {
        $values = [];

        foreach ($this->columns($includePrimaryKey) as $column) {
            $values[$column] = $this->$column;
        }

        return $values;
    }

    public function columns($includePrimaryKey = true)
    {
        $columns = array_merge($this->fillable(), $this->guarded);

        if ($includePrimaryKey) {
            if (is_array(static::$primaryKey)) {
                $columns = array_merge(static::$primaryKey, $columns);
            } else {
                $columns[] = static::$primaryKey;
            }
        }

        return $columns;
    }

    private function fillable()
    {
        $fillable = [];

        foreach ($this->fillable as $k => $v) {
            $fillable[] = is_int($k) ? $v : $k;
        }

        return $fillable;
    }
}
