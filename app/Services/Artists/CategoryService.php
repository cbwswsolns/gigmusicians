<?php

namespace App\Services\Artists;

use App\Models\Artists\Category;

class CategoryService
{
    /**
     * Category model instance
     *
     * @var App\Models\Artists\Category
     */
    protected $category;


    /**
     * Create a new category service instance.
     *
     * @param App\Models\Artists\Category $category [the category model instance]
     *
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Index method
     *
     * @return Illuminate\Support\Collection
     */
    public function index()
    {
        /* Return sorted categories
           Note: Will return empty collection if no categories exist */
        return $this->category->BySortOrder()->get();
    }


    /**
     * Store category method.
     *
     * @param array $data [the data to use to create a new category model]
     *
     * @return App\Models\Artists\Category
     */
    public function store(array $data)
    {
        // Note: Will be set to +1 if no profiles exist
        $sortorder = $this->category->BySortOrder()->get()->max('sortorder') + 1;

        $category = $this->category->create(['name' => $data['name'], 'sortorder' => $sortorder]);

        return $category;
    }


    /**
     * Update category method
     *
     * @param array                       $data     [the data to use to update the category model]
     * @param App\Models\Artists\Category $category [the category model to update]
     *
     * @return void
     */
    public function update(array $data, Category $category)
    {
        $category->update($data);
    }


    /**
     * Delete method
     *
     * @param \App\Models\Artists\Category $category [the category model to delete]
     *
     * @return void
     */
    public function delete(Category $category)
    {
        /* Associated stored files will be deleted via a model "deleting" event listener.
           Associated related/child records will be deleted (via "on cascade" implementation) */
        $category->delete();
    }
}
