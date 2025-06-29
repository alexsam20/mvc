<?php

trait Model
{
    use Database;

    protected int $limit = 10;
    protected int $offset = 0;
    protected string $orderType = 'desc';
    protected string $orderColumn = 'id';
    public array $errors = [];

    public function findAll()
    {
        $query = "select * from $this->table order by $this->orderColumn $this->orderType limit $this->limit offset $this->offset";

        return $this->query($query);
    }

    public function where($data, $data_not = [])
    {
        $keys = $this->keysInArray($data);
        $keys_not = $this->keysInArray($data_not);
        $query = "select * from $this->table where ";
        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }
        $query = trim($query, " && ");
        $query .= " order by $this->orderColumn $this->orderType limit $this->limit offset $this->offset";
        $data = array_merge($data, $data_not);

        return $this->query($query, $data);
    }

    public function first($data, $data_not = [])
    {
        $keys = $this->keysInArray($data);
        $keys_not = $this->keysInArray($data_not);
        $query = "select * from $this->table where ";
        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }
        $query = trim($query, " && ");
        $query .= " limit $this->limit offset $this->offset";
        $data = array_merge($data, $data_not);
        $result = $this->query($query, $data);

        if ($result) {
            return $result[0];
        }

        return false;
    }

    public function insert($data)
    {
        /** remove unwanted data */
        if (!empty($this->allowedFields)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedFields)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = $this->keysInArray($data);
        $query = "insert into $this->table (" . implode(', ', $keys) . ") values (:" . implode(', :', $keys) . ")";
        $this->query($query, $data);

        return false;
    }

    public function update($id, $data, $id_column = 'id')
    {
        /** remove unwanted data */
        if (!empty($this->allowedFields)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedFields)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = $this->keysInArray($data);
        $query = "update $this->table set ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . ", ";
        }

        $query = trim($query, ", ");
        $query .= " where $id_column = :$id_column";

        $data[$id_column] = $id;
        $this->query($query, $data);

        return false;
    }

    public function delete($id, $id_column = 'id')
    {
        $data[$id_column] = $id;
        $query = "delete from $this->table where $id_column = :$id_column ";

        $this->query($query, $data);

        return false;
    }

    private function keysInArray(array $data): array
    {
        return array_keys($data);
    }
}