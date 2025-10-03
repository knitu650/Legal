<?php
namespace App\Core;

class QueryBuilder {
    protected $db;
    protected $table;
    protected $select = '*';
    protected $where = [];
    protected $bindings = [];
    protected $joins = [];
    protected $orderBy = [];
    protected $groupBy = [];
    protected $having = [];
    protected $limit = null;
    protected $offset = null;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function select($columns) {
        $this->select = is_array($columns) ? implode(', ', $columns) : $columns;
        return $this;
    }

    public function where($column, $operator = null, $value = null) {
        if (is_array($column)) {
            foreach ($column as $key => $value) {
                $this->where($key, '=', $value);
            }
            return $this;
        }

        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->where[] = [$column, $operator, '?'];
        $this->bindings[] = $value;
        return $this;
    }

    public function orWhere($column, $operator = null, $value = null) {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->where[] = ['OR', $column, $operator, '?'];
        $this->bindings[] = $value;
        return $this;
    }

    public function whereIn($column, array $values) {
        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        $this->where[] = [$column, 'IN', "($placeholders)"];
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }

    public function join($table, $first, $operator = null, $second = null, $type = 'INNER') {
        if ($operator === null) {
            $this->joins[] = "$type JOIN $table $first";
        } else {
            $this->joins[] = "$type JOIN $table ON $first $operator $second";
        }
        return $this;
    }

    public function leftJoin($table, $first, $operator = null, $second = null) {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function rightJoin($table, $first, $operator = null, $second = null) {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }

    public function orderBy($column, $direction = 'ASC') {
        $this->orderBy[] = "$column $direction";
        return $this;
    }

    public function groupBy($columns) {
        $this->groupBy = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function having($column, $operator = null, $value = null) {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->having[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }

    public function paginate($perPage = 15, $page = null) {
        $page = $page ?: (isset($_GET['page']) ? (int)$_GET['page'] : 1);
        $this->limit($perPage);
        $this->offset(($page - 1) * $perPage);

        return [
            'data' => $this->get(),
            'total' => $this->count(),
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($this->count() / $perPage)
        ];
    }

    protected function buildQuery() {
        $sql = ["SELECT {$this->select} FROM {$this->table}"];

        if (!empty($this->joins)) {
            $sql[] = implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $conditions = [];
            foreach ($this->where as $where) {
                if ($where[0] === 'OR') {
                    $conditions[] = "OR {$where[1]} {$where[2]} {$where[3]}";
                } else {
                    $conditions[] = "{$where[0]} {$where[1]} {$where[2]}";
                }
            }
            $sql[] = 'WHERE ' . ltrim(implode(' ', $conditions), 'OR ');
        }

        if (!empty($this->groupBy)) {
            $sql[] = 'GROUP BY ' . implode(', ', $this->groupBy);
        }

        if (!empty($this->having)) {
            $sql[] = 'HAVING ' . implode(' AND ', $this->having);
        }

        if (!empty($this->orderBy)) {
            $sql[] = 'ORDER BY ' . implode(', ', $this->orderBy);
        }

        if ($this->limit !== null) {
            $sql[] = "LIMIT {$this->limit}";
        }

        if ($this->offset !== null) {
            $sql[] = "OFFSET {$this->offset}";
        }

        return implode(' ', $sql);
    }

    public function get() {
        $sql = $this->buildQuery();
        $statement = $this->db->prepare($sql);
        $statement->execute($this->bindings);
        return $statement->fetchAll();
    }

    public function first() {
        $this->limit(1);
        $sql = $this->buildQuery();
        $statement = $this->db->prepare($sql);
        $statement->execute($this->bindings);
        return $statement->fetch();
    }

    public function count() {
        $countQuery = preg_replace('/SELECT .* FROM/', 'SELECT COUNT(*) FROM', $this->buildQuery(), 1);
        $statement = $this->db->prepare($countQuery);
        $statement->execute($this->bindings);
        return (int) $statement->fetchColumn();
    }

    public function insert(array $data) {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        
        $statement = $this->db->prepare($sql);
        $statement->execute(array_values($data));
        return $this->db->lastInsertId();
    }

    public function update(array $data) {
        $set = implode(', ', array_map(function ($column) {
            return "$column = ?";
        }, array_keys($data)));

        $sql = "UPDATE {$this->table} SET $set";

        if (!empty($this->where)) {
            $conditions = [];
            foreach ($this->where as $where) {
                if ($where[0] === 'OR') {
                    $conditions[] = "OR {$where[1]} {$where[2]} {$where[3]}";
                } else {
                    $conditions[] = "{$where[0]} {$where[1]} {$where[2]}";
                }
            }
            $sql .= ' WHERE ' . ltrim(implode(' ', $conditions), 'OR ');
        }

        $statement = $this->db->prepare($sql);
        $statement->execute(array_merge(array_values($data), $this->bindings));
        return $statement->rowCount();
    }

    public function delete() {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->where)) {
            $conditions = [];
            foreach ($this->where as $where) {
                if ($where[0] === 'OR') {
                    $conditions[] = "OR {$where[1]} {$where[2]} {$where[3]}";
                } else {
                    $conditions[] = "{$where[0]} {$where[1]} {$where[2]}";
                }
            }
            $sql .= ' WHERE ' . ltrim(implode(' ', $conditions), 'OR ');
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($this->bindings);
        return $statement->rowCount();
    }

    public function raw($sql, array $bindings = []) {
        $statement = $this->db->prepare($sql);
        $statement->execute($bindings);
        return $statement;
    }
}