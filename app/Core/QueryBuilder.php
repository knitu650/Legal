<?php

namespace App\Core;

class QueryBuilder
{
    protected $db;
    protected $table;
    protected $wheres = [];
    protected $orderBy = [];
    protected $limit;
    protected $offset;
    protected $bindings = [];
    
    public function __construct($db, $table)
    {
        $this->db = $db;
        $this->table = $table;
    }
    
    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $this->wheres[] = "$column $operator ?";
        $this->bindings[] = $value;
        
        return $this;
    }
    
    public function orWhere($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $lastIndex = count($this->wheres) - 1;
        if ($lastIndex >= 0) {
            $this->wheres[$lastIndex] .= " OR $column $operator ?";
        } else {
            $this->wheres[] = "$column $operator ?";
        }
        
        $this->bindings[] = $value;
        
        return $this;
    }
    
    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy[] = "$column $direction";
        return $this;
    }
    
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
    
    public function get()
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        
        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }
        
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }
        
        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->bindings);
        
        return $stmt->fetchAll();
    }
    
    public function first()
    {
        $this->limit(1);
        $results = $this->get();
        
        return !empty($results) ? $results[0] : null;
    }
    
    public function count()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->bindings);
        
        return $stmt->fetch()->total;
    }
}
