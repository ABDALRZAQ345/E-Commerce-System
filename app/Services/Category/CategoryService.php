<?php

namespace App\Services\Category;

abstract class CategoryService
{
    protected $recitationModel;

    protected $id;

    protected $object;

    public function __construct($recitationModel, $id)
    {
        $this->recitationModel = $recitationModel;
        $this->id = $id;
        $this->object = $recitationModel::find($id);
    }

    abstract public function UpdateCategories(array $categoryIds);

    public function getCategories()
    {
        return $this->object->categories();
    }
}
