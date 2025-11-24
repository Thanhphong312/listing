<?php

namespace Vanguard\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Vanguard\Product;
use Vanguard\ProductVariants;

class ProductsImport implements ToCollection, ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //
    }
    public function model(array $row)
    {
        $find = Product::where('name', trim($row['name']))->first();
        $maxVariantId = ProductVariants::max('variant_id') ?? 99;

        if ($find == null) {
            $code = substr($row['name'], strpos($row['name'], 'G'));
            if ($this->findCode($code)) {
                if($this->findProduct(trim($row['name']), $row['color'], $row['size'])){
                    $product = new Product();
                    $product->name = $row['name'] ?? null;
                    $product->brand =  $row['brand'] ?? null;
                    $product->warehouse_name = $row['warehohoustone'] ?? null;
                    $product->save();
                    return new ProductVariants([
                        'variant_id' => $maxVariantId+1,
                        'product_id' => $product->id,
                        'price' => $row['price'] ?? null,
                        'sku' => $row['sku'] ?? null,
                        'style' => $row['type'] ?? null,
                        'color' => $row['color'] ?? null,
                        'size' => $row['size'] ?? null,
                        'stock' => $row['stock'] ?? null,
                        'mockup_src' => $row['mockup_src'] ?? null,
                        'weight' => $row['weight'] ?? null,
                        'length' => $row['length'] ?? null,
                        'width' => $row['width'] ?? null,
                        'height' => $row['height'] ?? null,
                    ]);
                }

            }
        } else {
            if($this->findProduct(trim($row['name']), $row['color'], $row['size'])){
                return new ProductVariants([
                    'variant_id' => $maxVariantId+1,
                    'product_id' =>$find->id,
                    'price' => $row['price']??null,
                    'sku' => $row['sku']??null,
                    'style' => $row['type']??null,
                    'color' => $row['color']??null,
                    'size' => $row['size']??null,
                    'stock' => $row['stock']??null,
                    'mockup_src' => $row['mockup_src']??null,
                    'weight' => $row['weight']??null,
                    'length' => $row['length']??null,
                    'width' => $row['width']??null,
                    'height' => $row['height']??null,
                ]);
            }
        }
    }
    public function findProduct($name, $color, $size) {
        if ($this->findCode($name)) {
            $product = Product::where('name', $name)
                ->whereHas('variants', function ($query) use ($color, $size) {
                    $query->where('color', $color);
                    $query->where('size', $size);
                })
                ->first();
            if ($product == null) {
                return 1;
            }
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
}
