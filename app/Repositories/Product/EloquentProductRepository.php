<?php

namespace Vanguard\Repositories\Product;

use Vanguard\Repositories\Store\StoreRepository;
use Vanguard\Product;
use Maatwebsite\Excel\Facades\Excel;
use Vanguard\Imports\ProductsImport;
use Maatwebsite\Excel\Validators\ValidationException;
use Vanguard\Http\Requests\Request;
use Vanguard\Jobs\UpdateProductJsonJobs;
use Vanguard\ProductVariants;

class EloquentProductRepository implements ProductRepository
{
    /**
     * @var StoreRepository
     */
    private $storeRepository;

    // public function __construct(StoreRepository $storeRepository)
    // {
    //     $this->storeRepository = $storeRepository;
    // }

    /**
     * {@inheritdoc}
     */
    public function find($request)
    {
        $product = Product::where('id', $request->id)->first();
        $productVariants = $product->variants()->orderBy('color', 'asc')->orderBy('size', 'asc');
        if (!empty($request->filter_sku)) {
            $productVariants->where('sku', 'like', '%' . $request->filter_sku . '%');
            // dd($productVariants->get());
        }
        if (!empty($request->filter_variant_id)) {
            $productVariants->where('variant_id', $request->filter_variant_id);
        }
        if (!empty($request->filter_size)) {
            $productVariants->where('size', $request->filter_size);
        }
        if (!empty($request->filter_color)) {
            $productVariants->where('color', $request->filter_color);
        }
        return $productVariants->get();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $product = Product::updateOrCreate(
            ['remote_id' => $data['remote_id']],
            $data
        );
        //add tags
        $tagsArray = explode(',', $data['tags']);
        $product->attachTags($tagsArray);

        // //add category
        // $category = Category::firstOrCreate(['name' => $data['category']], ['product_id' => $product->id]);
        // $product->categories()->attach($category->id);
        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($perPage, $filter = [], $orderBy = null, $status = null)
    {
        $query = Product::with('variants'); //get variants
        // dd($query->get()[0]->variants);
        // ->selectRaw('GROUP_CONCAT(DISTINCT color) as product_colors')
        // ->selectRaw('GROUP_CONCAT(DISTINCT size) as product_sizes')
        // ->selectRaw('MAX(brand) as product_brands')
        // ->selectRaw('GROUP_CONCAT(warehouse_name) as product_warehouse_names')
        $appends = [];
        if (!empty($filter['name'])) {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }
        if (!empty($filter['brand'])) {
            $query->where('brand', 'like', '%' . $filter['brand'] . '%');
        }
        if (!empty($orderBy)) {
            $appends['sort_by'] = $orderBy;

            // Split the sort option into column and direction
            $parts = explode('_', strtolower($orderBy));
            $partCounts = count($parts);
            if ($partCounts >= 2) {
                $sortDirection = array_pop($parts);
                $sortBy = implode('_', $parts);

                // Apply sorting
                if (in_array($sortBy, Product::SORTABLE) && in_array($sortDirection, ['asc', 'desc'])) {
                    $query->orderBy($sortBy, $sortDirection);
                }
            }
        }

        $result = $query->paginate($perPage);

        if (!empty($appends)) {
            $result->appends($appends);
        }
        // dd($result);
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {

        $productVariant = ProductVariants::find($id);
        $json = $productVariant->update($data);
        \Log::info("json");
        \Log::info($json);
        return $productVariant;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $product = $this->find($id->id);

        return $product->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return Product::count();
    }

    /**
     * {@inheritdoc}
     */
    public function countByStatus($status)
    {
        return Product::where('status', $status)->count();
    }

    /**
     * {@inheritdoc}
     */
    public function latest($count = 20)
    {
        return Product::orderBy('created_at', 'DESC')->limit($count)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function addToStores($productId, ...$storeIds)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function removeFromStores($productId, ...$storeIds)
    {
    }
    public function addVariant(array $data)
    {
        if (!empty($data['size'])) {

            $colos = ProductVariants::distinct()->pluck('color');
            foreach ($colos as $color) {
                if($this->findProduct($data['id'],$color, $data['size'])){
                    $style = ProductVariants::where('product_id',$data['id'])->first()->style;
                    $add = new ProductVariants();
                    $add->product_id = $data['id'];
                    $add->sku = $this->getSku($data['id'], $color, $data['size']);
                    $add->size = $data['size'];
                    $add->color = $color;
                    $add->style = $style;
                    $add->save();
                }
            }
            \Log::info("jsonProduct");
            UpdateProductJsonJobs::dispatch();
            return 1;
        }
        if (!empty($data['color'])) {
            $sizes = ProductVariants::distinct()->pluck('size');
            foreach ($sizes as $size) {
                if($this->findProduct($data['id'],$data['color'],$size)){
                    $style = ProductVariants::where('product_id',$data['id'])->first()->style;
                    $add = new ProductVariants();
                    $add->product_id = $data['id'];
                    $add->sku = $this->getSku($data['id'], $data['color'], $size);
                    $add->size = $size;
                    $add->color = $data['color'];
                    $add->style = $style;
                    $add->save();
                }
            }
            \Log::info("jsonProduct");
            UpdateProductJsonJobs::dispatch();
            return 1;
        }
        return 0;
    }
    public function getSku($product_id, $color, $size){
        $sku = "";
        if($product_id==1){
            $sku = 'USG5000UL-'.$color.'-'.$size;
        }
        if($product_id==2){
            $sku = 'USG18000UL-'.$color.'-'.$size;
        }
        if($product_id==3){
            $sku = 'USG18500UL-'.$color.'-'.$size;
        }
        return $sku;
    }
    public function findProduct($id, $color, $size)
    {
        $product = Product::where('id', $id)
            ->whereHas('variants', function ($query) use ($color, $size) {
                $query->where('color', $color);
                $query->where('size', $size);
            })
            ->first();
        if ($product == null) {
            return 1;
        }
        return 0;
    }

    public function findCode($codecheck)
    {
        $products = Product::select('name')->get();
        foreach ($products as $product) {
            $code = substr($product->name, strpos($product->name, 'G'));
            if (trim($code) == trim($codecheck)) {
                return 0;
            }
        }
        return 1;
    }
    public function import(array $data)
    {
        // try{
        Excel::import(new ProductsImport(), $data['file']);
        return 1;
        // }catch (ValidationException $e) {
        //     return 0;
        // } catch (\Exception $e) {
        //     return 0;
        // }
    }
}
