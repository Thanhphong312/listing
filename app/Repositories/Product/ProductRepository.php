<?php

namespace Vanguard\Repositories\Product;

use Vanguard\Models\Product\Product;

interface ProductRepository
{
    /**
     * Paginate registered products.
     *
     * @param $perPage
     * @param null $filter
     * @param null $status
     * @return mixed
     */
    public function paginate($perPage, $filter = [], $orderBy = null, $status = null);

    /**
     * Find product by its id.
     *
     * @param $id
     * @return null|Product
     */
    public function find($id);

    /**
     * Create new product.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update product specified by it's id.
     *
     * @param $id
     * @param array $data
     * @return Product
     */
    public function update($id, array $data);

    /**
     * Delete product with provided id.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Number of products in database.
     *
     * @return mixed
     */
    public function count();

    /**
     * Number of products with provided status.
     *
     * @param $status
     * @return mixed
     */
    public function countByStatus($status);

    /**
     * Get latest {$count} products from database.
     *
     * @param $count
     * @return mixed
     */
    public function latest($count = 20);

    /**
     * Set specified store to specified product.
     *
     * @param $productId
     * @param $storeIds
     * @return mixed
     */
    public function addToStores($productId, ...$storeIds);

    /**
     * Remove product from stores.
     *
     * @param $productId
     * @param $storeId
     * @return mixed
     */
    public function removeFromStores($productId, ...$storeIds);

    /**
     * Create new product.
     *
     * @param array $data
     * @return mixed
     */
    public function addVariant(array $data);
    /**
     * Create new product.
     *
     * @param array $data
     * @return mixed
     */
    public function import(array $data);
}
