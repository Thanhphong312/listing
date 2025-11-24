<?php

namespace Vanguard\Repositories\Order;

use Vanguard\Models\Order\Order;

interface OrderRepository
{
    /**
     * Get orders
     *
     * @param $request
     * @return mixed
     */
    public function getOrders($request);

    /**
     * Get orders today
     *
     * @return mixed
     */
    public function ordersToday();

    /**
     * Get orders
     *
     * @param $request
     * @return mixed
     */
    public function reports($request);
    
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
     * Find order by its id.
     *
     * @param $id
     * @return null|Order
     */
    public function find($id);

    /**
     * Create new order.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update order specified by it's id.
     *
     * @param $id
     * @param array $data
     * @return Order
     */
    public function update($id, array $data);

    /**
     * Delete order with provided id.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);
    /**
     * import order.
     *
     * @param array $data
     * @return mixed
     */
    public function import(array $data);
}
