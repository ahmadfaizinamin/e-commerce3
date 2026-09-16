<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProduct()
    {
        return Cache::remember('products_all', 60, function () {
            Log::channel('toko')->info('[CACHE] Tidak ada Cache, mengambil data dari DB.');

            return $this->productRepository->getAll()->toArray();
        });
    }

    public function createProduct(array $data)
    {
        Cache::forget('products_all');
        Log::channel('toko')->info('[CACHE] Menghapus Cache (Menambah Produk).');

        return $this->productRepository->create($data);
    }

    public function getByIdProduct($id)
    {
        return Cache::remember("product_$id", 60, function () use ($id) {
            Log::channel('toko')->info('[CACHE] Tidak ada Cache, mengambil data dari DB.');

            return $this->productRepository->getById($id)->toArray();
        });
    }

    public function updateProduct($id, array $data)
    {
        Cache::forget('products_all');
        Cache::forget("product_$id");
        Log::channel('toko')->info("[CACHE] Menghapus Cache (Update Deskripsi Produk {$id}).");

        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        Cache::forget('products_all');
        Cache::forget("product_$id");
        Log::channel('toko')->info("[CACHE] Menghapus Cache (Hapus Produk {$id}).");

        return $this->productRepository->delete($id);
    }
}
