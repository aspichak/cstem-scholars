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

    public static function primaryKey()
    {
        return static::$primaryKey;
    }

    /**
     * Converts a string or an associative array into a common representation of the primary key.
     *
     * @param $key mixed A string or an associative array representing the model primary key.
     * @return array Model primary key in associative array form. If the key is composite but not complete
     *               (not all columns present), the missing columns will be filled in with nulls.
     */
    public static function normalizeKey($key)
    {
        $primaryKey = is_array(static::primaryKey()) ? static::primaryKey() : [static::primaryKey()];

        if (!is_array($key)) {
            if (count($primaryKey) != 1) {
                throw new InvalidArgumentException('Invalid key passed to a model with a composite primary key');
            }

            return [static::primaryKey() => $key];
        } else {
            // Remove extra columns that don't belong in the composite primary key
            $key = array_intersect_key($key, array_flip($primaryKey));

            // Add missing columns if necessary
            foreach ($primaryKey as $k) {
                if (!array_key_exists($k, $key)) {
                    $key[$k] = null;
                }
            }

            return $key;
        }
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
     * @return Model object instance or NULL if there wasn't one found.
     */
    public static function first($query = '', ...$params)
    {
        $model = static::select($query, ...$params)->fetch();

        if (!$model) {
            $model = null;
        }

        return $model;
    }

    /**
     * Gets a single object by its primary key.
     *
     * @param $key mixed Primary key of the desired object. If the key is composite, it should be passed in the form of
     *                   an associative array.
     *
     * @return Model
     */
    public static function get($key)
    {
        return static::first(...static::byKey($key));
    }

    public static function delete($where = '', ...$params)
    {
        return DB::delete(static::table(), $where, ...$params);
    }

    public static function deleteByKey($key)
    {
        $numDeleted = static::delete(...static::byKey($key));
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

    public static function byKey($primaryKey)
    {
        $primaryKey = static::normalizeKey($primaryKey);
        $keys = array_map(fn($key) => "$key = ?", array_keys($primaryKey));
        $query = implode(' AND ', $keys);
        return [$query, ...array_values($primaryKey)];
    }

    public function key()
    {
        if (is_array(static::primaryKey())) {
            $primaryKey = [];

            foreach (static::primaryKey() as $k) {
                $primaryKey[$k] = $this->$k;
            }

            return $primaryKey;
        } else {
            return $this->{static::primaryKey()};
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

        return $this;
    }

    public function save()
    {
        if (!$this->isValid()) {
            return false;
        }

        $key = $this->key();

        if (!static::exists(...static::byKey($key))) {
            $res = static::insert($this->values());

            // Try to get an auto_increment key
            if (is_string(static::primaryKey())) {
                $lastInsertID = DB::pdo()->lastInsertID(static::primaryKey());


                if ($lastInsertID != 0) {
                    $this->{static::primaryKey()} = $lastInsertID;
                }
            }

            return $res;
        } else {
            return static::update($this->values(), ...static::byKey($key));
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
            if (is_array(static::primaryKey())) {
                $columns = array_merge(static::primaryKey(), $columns);
            } else {
                $columns[] = static::primaryKey();
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
