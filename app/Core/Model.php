<?php

namespace App\Core;

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }
    
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $query = new QueryBuilder($this->db, $this->table);
        return $query->where($column, $operator, $value);
    }
    
    public function create($data)
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        
        return $this->find($this->db->lastInsertId());
    }
    
    public function update($id, $data)
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "$column = ?";
        }
        $setString = implode(', ', $sets);
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $setString WHERE {$this->primaryKey} = ?");
        $values = array_values($data);
        $values[] = $id;
        $stmt->execute($values);
        
        return $this->find($id);
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }
    
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    public function paginate($perPage = 15, $page = 1)
    {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $total = $stmt->fetch()->total;
        
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} LIMIT ? OFFSET ?");
        $stmt->execute([$perPage, $offset]);
        $items = $stmt->fetchAll();
        
        return [
            'data' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
        ];
    }
}
