<?php

namespace Modules;

class BaseRepository
{

    protected $model;

    protected function mapParams($params = [])
    {
        foreach ($params as $key => $value) {
            $this->model = $this->model->where($key, $value);
        }
        return $this->model;
    }

    protected function setPage($perPage = 10)
    {
        $this->model = $this->model->paginate($perPage);
        return $this->model;
    }

    protected function setOrderBy($orderBy = [])
    {
        foreach ($orderBy as $key => $value) {
            $this->model = $this->model->orderBy($key, $value);
        }
        return $this->model;
    }
}