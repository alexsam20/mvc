<?php

class Model
{
    use Database;

    protected string $table = 'users';
    protected int $limit = 10;
    protected int $offset = 0;

    public function where($data, $data_not = [])
    {
        /*$fields = array_keys($data);
        $columns = implode(', ', $fields);
        $binds = implode(', ', array_map(static fn ($field) => ":$field", $fields));*/

        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
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

        return $this->query($query, $data);
    }

    public function first($data, $data_not = [])
    {
        //
    }

    public function insert($data)
    {
        //
    }

    public function update($id, $data, $id_column = 'id')
    {
        //
    }

    public function delete($id, $id_column = 'id')
    {
        //
    }
}