<?php

/**
 * Class QueryBuilder
 */
class QueryBuilder
{
    private const SELECT = 1;
    private const INSERT = 2;
    private const UPDATE = 3;
    private const DELETE = 4;

    private $type;
    private $alias = '';
    private $fields = '';
    private $join = '';
    private $on = '';
    private $conditions = '';
    private $from = [];

    public function __toString(): string
    {
        $where = ($this->conditions === '') ? '' : ' WHERE ' . $this->conditions;
        $join = $this->join === '' ? '' : ' INNER JOIN ' . $this->join . ' ON ' . $this->on;

        switch ($this->type) {
            case self::SELECT:
                return 'SELECT ' . $this->fields
                    . ' FROM ' . implode(', ', $this->from)
                    . $join
                    . $where;
            case self::INSERT:
                return 'INSERT INTO ' . implode(', ', $this->from)
                    . ' SET ' . $this->fields;
            case self::UPDATE:
                return 'UPDATE ' . implode(', ', $this->from)
                    . ' SET ' . $this->fields
                    . $where;
            case self::DELETE:
                return 'UPDATE ' . implode(', ', $this->from) . ' SET '
                    . (($this->alias === '') ? implode(', ', $this->from) : $this->alias)
                    . '.is_deleted = TRUE'
                    . $where;
            default:
                break;
        }


    }

    public function select(string ...$select): self
    {
        $this->type = self::SELECT;
        $this->fields = implode(', ', $select);
        return $this;
    }

    public function join(string $join): self
    {
        $this->join = $join;
        return $this;
    }

    public function on(string ...$on)
    {
        $this->on = $this->getSqlFormattedString($on);
        return $this;
    }

    public function insert(string ...$insert): self
    {
        $this->type = self::INSERT;
        $this->fields = $this->getSqlFormattedString($insert);
        return $this;
    }

    public function update(string ...$update): self
    {
        $this->type = self::UPDATE;
        $this->fields = $this->getSqlFormattedString($update);
        return $this;
    }

    public function delete(): self
    {
        $this->type = self::DELETE;
//        $this->fields = $this->getSqlFormattedString($delete);
        return $this;
    }

    public function where(string ...$where): self
    {
        $this->conditions = $this->getSqlFormattedString($where, ' AND ');
        return $this;
    }

    public function from(string $table, ?string $alias = null): self
    {
        if ($alias === null) {
            $this->from[] = $table;
        } else {
            $this->from[] = "${table} AS ${alias}";
            $this->alias = $alias;
        }
        return $this;
    }

    public function into(string $table, ?string $alias = null): self
    {
        if ($alias === null) {
            $this->from[] = $table;
        } else {
            $this->from[] = "${table} AS ${alias}";
            $this->alias = $alias;
        }
        return $this;
    }

    private function getSqlFormattedString($arr, $between = ', ')
    {
        $str = '';
        foreach ($arr as $value) {
            $offset = strpos($value, '.');
            if ($offset) $offset++;
            $attr = substr($value, $offset);
            $str .= $value . ' = :' . $attr . $between;
        }
        return rtrim($str, $between);
    }
}
