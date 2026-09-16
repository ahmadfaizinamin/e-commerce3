<?php 
namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategory()
    {
        return $this->categoryRepository->getAll();
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    public function getByIdCategory($id)
    {
        return $this->categoryRepository->getById($id);
    }

    public function updateCategory($id, array $data)
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function delteCategory($id)
    {
        return $this->categoryRepository->delete($id);
    }
}