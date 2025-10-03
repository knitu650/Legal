<?php
namespace App\Core;

abstract class Model {
    protected static $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = ['id'];
    protected $attributes = [];
    protected $original = [];
    protected $timestamps = true;

    public function __construct(array $attributes = []) {
        self::$db = Application::getInstance()->getDb();
        $this->fill($attributes);
    }

    public function fill(array $attributes) {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }

    protected function isFillable($key) {
        return in_array($key, $this->fillable) && !in_array($key, $this->guarded);
    }

    public function setAttribute($key, $value) {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getAttribute($key) {
        return $this->attributes[$key] ?? null;
    }

    public function __get($key) {
        return $this->getAttribute($key);
    }

    public function __set($key, $value) {
        $this->setAttribute($key, $value);
    }

    public function save() {
        if (isset($this->attributes[$this->primaryKey])) {
            return $this->update();
        }
        return $this->insert();
    }

    protected function insert() {
        if ($this->timestamps) {
            $this->attributes['created_at'] = date('Y-m-d H:i:s');
            $this->attributes['updated_at'] = date('Y-m-d H:i:s');
        }

        $columns = implode(', ', array_keys($this->attributes));
        $values = implode(', ', array_fill(0, count($this->attributes), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
        
        $stmt = self::$db->prepare($sql);
        $stmt->execute(array_values($this->attributes));
        
        $this->attributes[$this->primaryKey] = self::$db->lastInsertId();
        $this->original = $this->attributes;
        
        return true;
    }

    protected function update() {
        if ($this->timestamps) {
            $this->attributes['updated_at'] = date('Y-m-d H:i:s');
        }

        $sets = [];
        $params = [];
        
        foreach ($this->attributes as $column => $value) {
            if ($column !== $this->primaryKey) {
                $sets[] = "{$column} = ?";
                $params[] = $value;
            }
        }
        
        $params[] = $this->attributes[$this->primaryKey];
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . 
               " WHERE {$this->primaryKey} = ?";
        
        $stmt = self::$db->prepare($sql);
        $result = $stmt->execute($params);
        
        if ($result) {
            $this->original = $this->attributes;
        }
        
        return $result;
    }

    public function delete() {
        if (!isset($this->attributes[$this->primaryKey])) {
            return false;
        }

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute([$this->attributes[$this->primaryKey]]);
    }

    public static function find($id) {
        $instance = new static;
        $sql = "SELECT * FROM {$instance->table} WHERE {$instance->primaryKey} = ?";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$result) {
            return null;
        }
        
        return new static($result);
    }

    public static function all() {
        $instance = new static;
        $sql = "SELECT * FROM {$instance->table}";
        $stmt = self::$db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return array_map(function($result) {
            return new static($result);
        }, $results);
    }

    public static function where($column, $operator, $value = null) {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $instance = new static;
        $sql = "SELECT * FROM {$instance->table} WHERE {$column} {$operator} ?";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$value]);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return array_map(function($result) {
            return new static($result);
        }, $results);
    }

    public function isDirty() {
        return $this->attributes !== $this->original;
    }

    public function getChanges() {
        return array_diff_assoc($this->attributes, $this->original);
    }
}