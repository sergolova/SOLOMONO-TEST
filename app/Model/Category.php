<?php

namespace Model;

class Category
{
    public int $id;
    public string $name;

    public static function constraints(): array
    {
        return [
            'max_name_length' => 256,
        ];
    }

    public static function FromArray(array $row): Category
    {
        $cat = new Category();
        $cat->id = $row['id'];
        $cat->name = $row['name'];

        return $cat;
    }
}