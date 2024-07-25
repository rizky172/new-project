<?php

namespace App\Models;

trait ModelMetaTrait
{
    public function meta()
    {
        // var_dump('meta model', $this->table); die;
        return $this->hasMany(Meta::class, 'fk_id')
                ->where('table_name', $this->table);
    }

    public function media()
    {
        return $this->meta()
                ->where('key', 'media');
    }

    public function metaIgnoreKey($key = null)
    {
        if(!empty($key))
            return $this->metaIgnoreKeys([$key]);
        else
            return $this->metaIgnoreKeys();
    }

    public function metaIgnoreKeys($keys = [])
    {
        return $this->meta()->whereNotIn('key', $keys);
    }
}
