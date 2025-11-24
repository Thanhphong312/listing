<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\StoreProducts;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Vanguard\Product;
use Vanguard\Models\Store\Store;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Telegram\Bot\Laravel\Facades\Telegram;

class rePostProductToTiktokShop implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $id;
    private $discount;
    public $imageattribute = [];
    public function __construct($id, $discount)
    {
        $this->id = $id;
        $this->discount = $discount;
    }
    public function uniqueId()
    {
        return $this->id;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $storeproduct = StoreProducts::find($this->id);
        \Log::channel('export-product-tiktokshop')->info("Start post to tiktok");
        \Log::channel('export-product-tiktokshop')->info($storeproduct->id);

        if ($storeproduct) {
            $store = Store::find($storeproduct->store_id);
            $product = $storeproduct;
            \Log::channel('export-product-tiktokshop')->info("store");
            \Log::channel('export-product-tiktokshop')->info($store->id);
            \Log::channel('export-product-tiktokshop')->info("product");
            \Log::channel('export-product-tiktokshop')->info($product->product_id);

            if (!empty($storeproduct->remote_id)) {
                return;
            }
            try {
                $custom_data = json_decode($product->data);
                $clientAppPartner = (new ConnectAppPartnerService())->connectAppPartner($store);

                if (!isset($clientAppPartner['client'])) {
                    \Log::channel('export-product-tiktokshop')->error("{$store->name} - title: {$product->product_id} - store has no app");
                    return;
                }

                $clientAppPartner = $clientAppPartner['client'];
                // dd($clientAppPartner);
                $warehouse_id = $this->getDefaultWarehouseId($clientAppPartner);

                $dataJson = $custom_data->product;
                $description = $dataJson->description;

                if (str_contains($description, '<img') == true) {
                    $description = preg_replace_callback(
                        '/<img\s+[^>]*src=["\']([^"\']+)["\'][^>]*>/i',
                        function ($matches)  {
                            $originalSrc = $matches[1]; // Extract the original src URL
                            // $newSrcData = $this->uploadDescriptionImages($clientAppPartner, $originalSrc);
                            $newSrc = "";
                            if(str_contains($originalSrc, 'f194e6f8faf44b6197e5f3e9d1c4190d') == true){
                                $newSrc =  'https://p16-oec-ttp.tiktokcdn-us.com/tos-useast5-i-omjb5zjo8w-tx/f194e6f8faf44b6197e5f3e9d1c4190d~tplv-omjb5zjo8w-origin-jpeg.jpeg';
                            }else{
                                $newSrc = "https://p19-oec-ttp.tiktokcdn-us.com/tos-useast5-i-omjb5zjo8w-tx/ea6530172fd7443e8b88aabe10c662f0~tplv-omjb5zjo8w-origin-jpeg.jpeg"; // Get the new src URL from the upload function
                            }
        
                            return str_replace($originalSrc, $newSrc, $matches[0]);
                        },
                        $description
                    );
                }
                $dataJson->description = $description;
                // dd($dataJson->description);
                $title = $dataJson->title . " " . $store->keyword ?? "";
                $product_type = $dataJson->category;
                // $product_attributes = $this->getProductAttributes($product_type, $dataJson->set);
                $product_attributes = $dataJson->selectedAttributes ?? $this->getProductAttributes($product_type, $dataJson->set);

                if (property_exists($dataJson, 'aerosols')) {
                    array_push(
                        $product_attributes,
                        [
                            "id" => "101571",
                            "name" => "Aerosols",
                            "values" => [
                                [
                                    "id" => "1000059",
                                    "name" => "no"
                                ]
                            ]
                        ]
                    );
                }
                if (property_exists($dataJson, 'flammable_liquid')) {
                    array_push(
                        $product_attributes,
                        [
                            "id" => "101574",
                            "name" => "Flammable Liquid",
                            "values" => [
                                [
                                    "id" => "1000059",
                                    "name" => "no"
                                ]
                            ]
                        ]
                    );
                }
                if (property_exists($dataJson, 'contains_batteries_or_cells')) {
                    array_push(
                        $product_attributes,
                        [
                            "id" => "101610",
                            "name" => "Contains Batteries or Cells?",
                            "values" => [
                                [
                                    "id" => "1000325",
                                    "name" => "None"
                                ]
                            ]
                        ]
                    );
                }
                if (property_exists($dataJson, 'other_dangerous_goods_or_hazardous_materials')) {

                    array_push(
                        $product_attributes,
                        [
                            "id" => "101619",
                            "name" => "Other Dangerous Goods or Hazardous Materials",
                            "values" => [
                                [
                                    "id" => "1000059",
                                    "name" => "no"
                                ]
                            ]
                        ]
                    );
                }
                

                array_push(
                    $product_attributes,
                    [
                        "id" => "101400",
                        "name" => "CA Prop 65: Carcinogens",
                        "values" => [
                            [
                                "id" => $dataJson->ca_prop_65_carcinogens ? ($dataJson->ca_prop_65_carcinogens == "no" ? "1000059" : "1000058") : "1000059",
                                "name" => $dataJson->ca_prop_65_carcinogens ?? "no"
                            ]
                        ]
                    ]
                );
                array_push(
                    $product_attributes,
                    [
                        "id" => "101395",
                        "name" => "CA Prop 65: Repro. Chems",
                        "values" => [
                            [
                                "id" => $dataJson->ca_prop_65_repro_chems ? ($dataJson->ca_prop_65_repro_chems == "no" ? "1000059" : "1000058") : "1000059",
                                "name" => $dataJson->ca_prop_65_repro_chems ?? "no"
                            ]
                        ]
                    ]
                );
                \Log::channel('export-product-tiktokshop')->info("product_attributes");
                \Log::channel('export-product-tiktokshop')->info($product_attributes);

                $category_id = (string) $dataJson->category_id;
                // $category_id = $this->getCategoryId($product_type, $dataJson->set);
                \Log::channel('export-product-tiktokshop')->info("category_id");
                \Log::channel('export-product-tiktokshop')->info($category_id);
                $main_images = $this->uploadMainImages($clientAppPartner, $dataJson->images);
                \Log::channel('export-product-tiktokshop')->info("main_images");
                \Log::channel('export-product-tiktokshop')->info($main_images);
                if (isset($dataJson->imagevariants)) {
                    $attribute_images = $this->uploadAttributeImage($clientAppPartner, $dataJson->imagevariants);
                } else {
                    $attribute_images = [];
                }
                \Log::channel('export-product-tiktokshop')->info("attribute_images");
                \Log::channel('export-product-tiktokshop')->info($attribute_images);
                $this->imageattribute = $attribute_images;
                $size_chart = (isset($dataJson->imagesizechart)&&filter_var($dataJson->imagesizechart, FILTER_VALIDATE_URL))? $this->uploadSizeChart($clientAppPartner, $dataJson->imagesizechart) : null;
                if (property_exists($dataJson, 'aerosols')) {
                    $skus[] = [
                        "price" => [
                            "currency" => "USD",
                            "amount" => $dataJson->variants->only_price
                        ],
                        "sales_attributes" => [],
                        "seller_sku" => "",
                        "inventory" => [
                            [
                                "quantity" => (int) $dataJson->variants->only_quantity,
                                "warehouse_id" => $warehouse_id
                            ]
                        ]
                    ];
                    
                }else{
                    $skus = $this->createSkus($clientAppPartner, $dataJson->variants, $dataJson->images, $warehouse_id);
                }
                \Log::channel('export-product-tiktokshop')->info("package_weight");
                \Log::channel('export-product-tiktokshop')->info($dataJson->weight);
                \Log::channel('export-product-tiktokshop')->info("package_dimensions");
                \Log::channel('export-product-tiktokshop')->info($dataJson->height);
                \Log::channel('export-product-tiktokshop')->info("package_dimensions");
                \Log::channel('export-product-tiktokshop')->info($dataJson->length);
                \Log::channel('export-product-tiktokshop')->info("package_dimensions");
                \Log::channel('export-product-tiktokshop')->info($dataJson->width);

                $dataProduct = [
                    "description" => $dataJson->description??$this->getDefaultDescription(),
                    "title" => $title,
                    "is_cod_open" => true,
                    "category_id" => $category_id,
                    "category_version" => "v2",
                    "main_images" => $main_images,
                    "skus" => $skus,
                    "package_weight" => [
                        "value" => (string) $dataJson->weight ?? "0.3",
                        "unit" => "KILOGRAM"
                    ],
                    "package_dimensions" => [
                        "height" => (string) $dataJson->height ?? "5",
                        "length" => (string) $dataJson->length ?? "15",
                        "unit" => "CENTIMETER",
                        "width" => (string) $dataJson->width ?? "15",
                    ],
                    "product_attributes" => $product_attributes,
                ];

                if ($size_chart) {
                    $dataProduct["size_chart"] = ['image' => $size_chart];
                }
                // dd($dataProduct);
                \Log::channel('export-product-tiktokshop')->info("dataProduct");
                \Log::channel('export-product-tiktokshop')->info($dataProduct);
                $createProduct = $clientAppPartner->Product->createProduct($dataProduct);
                $storeproduct->remote_id = $createProduct['product_id'];
                $storeproduct->message = "success";
                $storeproduct->save();
                syncProductTiktokPostJob::dispatch($storeproduct->store_id, $createProduct['product_id'], $this->discount)->onQueue('sync-product-post_tiktok');
                \Log::channel('export-product-tiktokshop')->info("{$store->name} - title: {$product->title} - post success. Remote ID: {$createProduct['product_id']}");

            } catch (\Exception $e) {
                \Log::channel('export-product-tiktokshop')->error("{$store->name} - title: {$product->title} - {$e->getMessage()}");
                // Check if the error message contains 'cURL error 56' or 'Unable'
                $storeproduct->message = $e->getMessage();
                $storeproduct->save();
                if (str_contains($e->getMessage(), 'Unable to parse response string as JSON') == true || str_contains($e->getMessage(), 'request is limited') == true || str_contains($e->getMessage(), 'System error') == true || str_contains($e->getMessage(), 'Internal system error') == true ) {
                    // Dispatch the job to the 'post-product-to-tiktok' queue, with a 2-second delay
                    PostProductToTiktokShop::dispatch($this->id, $this->discount)
                        ->delay(now()->addSeconds(2))  // Using `now()` for better clarity and consistency
                        ->onQueue('re-post-product-to-tiktok');
                }
                try {
                    Telegram::sendMessage([
                        'chat_id' => $store->user->group_id,
                        'text' => 'Post Product to Tiktok Error ID: '.$storeproduct->id.' - '.$e->getMessage(),
                    ]);
                } catch (\Throwable $th) {
                    \Log::channel('telegram-wh')->info($th);
                }
                
            }
        }

    }

    private function getDefaultWarehouseId($clientAppPartner)
    {
        $warehouses = $clientAppPartner->Logistic->getWarehouseList()['warehouses'];
        foreach ($warehouses as $warehouse) {
            if ($warehouse['is_default']) {
                return $warehouse['id'];
            }
        }
        return null; // Or handle this case as needed
    }

    private function getDefaultDescription()
    {
        $description = "<p><strong>Welcome to the store!</strong></p><p>_ Experience unparalleled comfort and style with our versatile collection of hoodies, sweatshirts, and t-shirts. Crafted with a passion for providing the perfect shopping experience, our products are designed to keep you warm and cozy throughout the winter.</p><p>_ Feel free to explore a wide range of soft, comfy hoodies that are perfect for the season. We take pride in offering customization options, allowing you to choose your preferred color or even request a custom design. Should you have any questions or specific concerns, our dedicated team is always ready to assist – just drop us a message.</p><p>_ The standout feature of our products lies in the captivating images printed on the fabric using cutting-edge digital printing technology. Unlike embroidered designs, these images are seamlessly integrated, ensuring they neither peel off nor fade over time. Our hoodies, made from a blend of 50% cotton and 50% polyester, provide a classic fit with a double-lined hood and color-matched drawcord.</p><p>_ For those seeking premium shirts, our collection of soft, high-quality shirts is a perfect fit. Immerse yourself in 100% cotton shirts, available in various colors and styles. The innovative digital printing technology ensures that the vibrant images on these shirts remain intact for the long haul.</p><p>_ Embrace the winter chill with our warm sweatshirts, designed with your comfort in mind. The images are intricately printed using advanced digital technology, creating a lasting impression. The sweatshirts, featuring a classic fit and 1x1 rib with spandex, guarantee enhanced stretch and recovery.</p><p>_ Elevate your winter wardrobe with our curated selection of cozy and stylish apparel. Your satisfaction is our priority, and we look forward to making your shopping experience truly exceptional.</p><p><strong>RETURNS OR EXCHANGES</strong></p><p>All of our shirts are custom printed so we do not accept returns or exchanges due to the sizing so please make sure you take all the steps to ensure you get the size you are wanting. However, if there are any issues with the shirt itself, please message us and we'd be happy to help correct the error.</p><p><strong>PRODUCTION AND SHIPPING</strong></p><p>Production: 1-3 days Standard Shipping : 3-6 business days after production time</p><p><strong>THANK YOU</strong></p>";
        return $description;
    }

    private function getProductAttributes($product_type, $set)
    {
        $attributes = [];
        if ($product_type === 'T-shirt' || $product_type === 'T-shits') {
            $attributes = [
                [
                    "id" => "100397",
                    "name" => "Season",
                    "values" => [
                        ["id" => "1005840", "name" => "All Seasons"],
                        ["id" => "1001163", "name" => "Winter"],
                        ["id" => "1001161", "name" => "Spring"],
                        ["id" => "1000905", "name" => "Fall"],
                        ["id" => "1001162", "name" => "Summer"],
                    ],
                ],
                [
                    "id" => "100347",
                    "name" => "Quantity per Pack",
                    "values" => [
                        ["id" => "1000256", "name" => "1"],
                    ],
                ],
                [
                    "id" => "100401",
                    "name" => "Care Instructions",
                    "values" => [
                        ["id" => "1001199", "name" => "Machine Washable"],
                    ],
                ],
                [
                    "id" => "100394",
                    "name" => "Clothing Length",
                    "values" => [
                        ["id" => "1000907", "name" => "Medium"],
                    ],
                ],
                [
                    "id" => "100392",
                    "name" => "Occasion",
                    "values" => [
                        ["id" => "1001124", "name" => "Casual"],
                    ],
                ],
                [
                    "id" => "100395",
                    "name" => "Sleeve Length",
                    "values" => [
                        ["id" => "1001141", "name" => "Short Sleeve"],
                    ],
                ],
                [
                    "id" => "100399",
                    "name" => "Fit",
                    "values" => [
                        ["id" => "1001180", "name" => "Fitted"],
                    ],
                ],
                [
                    "id" => "100198",
                    "name" => "Pattern",
                    "values" => [
                        ["id" => "1001186", "name" => "Graphic"],
                    ],
                ],
                [
                    "id" => "100396",
                    "name" => "Sleeve Type",
                    "values" => [
                        ["id" => "1005904", "name" => "Normal Type"],
                    ],
                ],
                [
                    "id" => "100398",
                    "name" => "Style",
                    "values" => [
                        ["id" => "1001167", "name" => "Chic"],
                        ["id" => "1001174", "name" => "Retro"],
                        ["id" => "1001168", "name" => "Elegant"],
                        ["id" => "1001176", "name" => "Street"],
                        ["id" => "1001165", "name" => "Basics"],
                        ["id" => "1002665", "name" => "Party"],
                        ["id" => "1000520", "name" => "Sports"],
                        ["id" => "1001124", "name" => "Casual"],
                        ["id" => "1001524", "name" => "Fashion"],
                        ["id" => "1003600", "name" => "Vintage"],
                        ["id" => "1019943", "name" => "Y2K"],
                    ],
                ],
                [
                    "id" => "101127",
                    "name" => "Size Type",
                    "values" => [
                        ["id" => "1005904", "name" => "Normal Type"],
                    ],
                ],
                [
                    "id" => "100393",
                    "name" => "Neckline",
                    "values" => [
                        ["id" => "1001126", "name" => "Crew Neck"],
                    ],
                ],
                [
                    "id" => "100701",
                    "name" => "Material",
                    "values" => [
                        ["id" => "1000039", "name" => "Cotton"],
                    ],
                ],
                [
                    "id" => "101400",
                    "name" => "CA Prop 65: Carcinogens",
                    "values" => [
                        ["id" => "1000059", "name" => "No"],
                    ],
                ],
                [
                    "id" => "101395",
                    "name" => "CA Prop 65: Repro. Chems",
                    "values" => [
                        ["id" => "1000059", "name" => "No"],
                    ],
                ],
            ];
        } else if ($product_type === 'Sweater' || $product_type === 'Sweatshirt') {
            $attributes = [
                [
                    "id" => "101400",
                    "name" => "CA Prop 65: Carcinogens",
                    "values" => [
                        [
                            "id" => "1000059",
                            "name" => "No"
                        ]
                    ]
                ],
                [
                    "id" => "101395",
                    "name" => "CA Prop 65: Repro. Chems",
                    "values" => [
                        [
                            "id" => "1000059",
                            "name" => "No"
                        ]
                    ]
                ],
                [
                    "id" => "100397",
                    "name" => "Season",
                    "values" => [
                        [
                            "id" => "1005840",
                            "name" => "All Seasons"
                        ],
                        [
                            "id" => "1001163",
                            "name" => "Winter"
                        ],
                        [
                            "id" => "1001161",
                            "name" => "Spring"
                        ],
                        [
                            "id" => "1000905",
                            "name" => "Fall"
                        ],
                        [
                            "id" => "1001162",
                            "name" => "Summer"
                        ]
                    ]
                ],
                [
                    "id" => "100347",
                    "name" => "Quantity per Pack",
                    "values" => [
                        [
                            "id" => "1000256",
                            "name" => "1"
                        ]
                    ]
                ],
                [
                    "id" => "100401",
                    "name" => "Care Instructions",
                    "values" => [
                        [
                            "id" => "1001199",
                            "name" => "Machine Washable"
                        ]
                    ]
                ],
                [
                    "id" => "100394",
                    "name" => "Clothing Length",
                    "values" => [
                        [
                            "id" => "1001140",
                            "name" => "Long"
                        ]
                    ]
                ],
                [
                    "id" => "100392",
                    "name" => "Occasion",
                    "values" => [
                        [
                            "id" => "1001124",
                            "name" => "Casual"
                        ]
                    ]
                ],
                [
                    "id" => "100395",
                    "name" => "Sleeve Length",
                    "values" => [
                        [
                            "id" => "1001144",
                            "name" => "Long Sleeve"
                        ]
                    ]
                ],
                [
                    "id" => "100399",
                    "name" => "Fit",
                    "values" => [
                        [
                            "id" => "1001180",
                            "name" => "Fitted"
                        ]
                    ]
                ],
                [
                    "id" => "100198",
                    "name" => "Pattern",
                    "values" => [
                        [
                            "id" => "1007943",
                            "name" => "Digital Print"
                        ]
                    ]
                ],
                [
                    "id" => "100396",
                    "name" => "Sleeve Type",
                    "values" => [
                        [
                            "id" => "1005904",
                            "name" => "Normal Type"
                        ]
                    ]
                ],
                [
                    "id" => "100398",
                    "name" => "Style",
                    "values" => [
                        [
                            "id" => "1001171",
                            "name" => "Cute"
                        ],
                        [
                            "id" => "1001168",
                            "name" => "Elegant"
                        ],
                        [
                            "id" => "1001174",
                            "name" => "Retro"
                        ],
                        [
                            "id" => "1001167",
                            "name" => "Chic"
                        ],
                        [
                            "id" => "1001176",
                            "name" => "Street"
                        ],
                        [
                            "id" => "1002665",
                            "name" => "Party"
                        ],
                        [
                            "id" => "1019943",
                            "name" => "Y2K"
                        ],
                        [
                            "id" => "1000520",
                            "name" => "Sports"
                        ],
                        [
                            "id" => "1001124",
                            "name" => "Casual"
                        ],
                        [
                            "id" => "1004424",
                            "name" => "Classic"
                        ],
                        [
                            "id" => "1001165",
                            "name" => "Basics"
                        ],
                        [
                            "id" => "1001172",
                            "name" => "Minimalist"
                        ]
                    ]
                ],
                [
                    "id" => "101127",
                    "name" => "Size Type",
                    "values" => [
                        [
                            "id" => "1005904",
                            "name" => "Normal Type"
                        ]
                    ]
                ],
                [
                    "id" => "100393",
                    "name" => "Neckline",
                    "values" => [
                        [
                            "id" => "1001126",
                            "name" => "Crew Neck"
                        ]
                    ]
                ],
                [
                    "id" => "100701",
                    "name" => "Material",
                    "values" => [
                        [
                            "id" => "1000039",
                            "name" => "Cotton"
                        ]
                    ]
                ],
                [
                    "id" => "100149",
                    "name" => "Country of Origin",
                    "values" => [
                        [
                            "id" => "1089295",
                            "name" => "USA"
                        ]
                    ]
                ],
                [
                    "id" => "101761",
                    "name" => "Top Length",
                    "values" => [
                        [
                            "id" => "1000907",
                            "name" => "Medium"
                        ]
                    ]
                ]
            ];

        } else if ($product_type === 'Hoodie') {
            $attributes = [
                [
                    "id" => "100397",
                    "name" => "Season",
                    "values" => [
                        [
                            "id" => "1005840",
                            "name" => "All Seasons"
                        ]
                    ]
                ],
                [
                    "id" => "100394",
                    "name" => "Clothing Length",
                    "values" => [
                        [
                            "id" => "1001140",
                            "name" => "Long"
                        ]
                    ]
                ],
                [
                    "id" => "100401",
                    "name" => "Care Instructions",
                    "values" => [
                        [
                            "id" => "1001199",
                            "name" => "Machine Washable"
                        ]
                    ]
                ],
                [
                    "id" => "100392",
                    "name" => "Occasion",
                    "values" => [
                        [
                            "id" => "1001124",
                            "name" => "Casual"
                        ]
                    ]
                ],
                [
                    "id" => "100395",
                    "name" => "Sleeve Length",
                    "values" => [
                        [
                            "id" => "1001144",
                            "name" => "Long Sleeve"
                        ]
                    ]
                ],
                [
                    "id" => "100198",
                    "name" => "Pattern",
                    "values" => [
                        [
                            "id" => "1007943",
                            "name" => "Digital Print"
                        ]
                    ]
                ],
                [
                    "id" => "100398",
                    "name" => "Style",
                    "values" => [
                        [
                            "id" => "1001171",
                            "name" => "Cute"
                        ]
                    ]
                ],
                [
                    "id" => "101127",
                    "name" => "Size Type",
                    "values" => [
                        [
                            "id" => "1005904",
                            "name" => "Regular"
                        ]
                    ]
                ],
                [
                    "id" => "100701",
                    "name" => "Material",
                    "values" => [
                        [
                            "id" => "1000039",
                            "name" => "Cotton"
                        ]
                    ]
                ],
                [
                    "id" => "101400",
                    "name" => "CA Prop 65: Carcinogens",
                    "values" => [
                        [
                            "id" => "1000059",
                            "name" => "No"
                        ]
                    ]
                ],
                [
                    "id" => "101395",
                    "name" => "CA Prop 65: Repro. Chems",
                    "values" => [
                        [
                            "id" => "1000059",
                            "name" => "No",
                        ]
                    ]
                ]
            ];
        }
        \Log::channel('export-product-tiktokshop')->info("set: " . $set);
        if (isset($set) && !empty($set) && $set == 1) {
            \Log::channel('export-product-tiktokshop')->info("set = femile");
            if ($product_type == 'Hoodie' || $product_type == 'hoodie') {

                $attributes[] = [
                    "id" => "100409",
                    "name" => "Clothing Styles",
                    "values" => [
                        [
                            "id" => "1007463",
                            "name" => "Pullover Hoodie"
                        ]
                    ]
                ];
            }
            if ($product_type == 'Sweatshirt' || $product_type == 'Sweatshirt') {

                $attributes[] = [
                    "id" => "100409",
                    "name" => "Clothing Styles",
                    "values" => [
                        [
                            "id" => "1007447",
                            "name" => "Sweatshirt"
                        ]
                    ]
                ];
            }
        } else {
            \Log::channel('export-product-tiktokshop')->info("set = mile");
            if ($product_type == 'Hoodie' || $product_type == 'hoodie') {

                $attributes[] = [
                    "id" => "100409",
                    "name" => "Clothing Styles",
                    "values" => [
                        [
                            "id" => "1007463",
                            "name" => "Pullover Hoodie"
                        ]
                    ]
                ];
            }
            if ($product_type == 'Sweatshirt' || $product_type == 'Sweatshirt' || $product_type == 'Sweater') {

                $attributes[] = [
                    "id" => "100409",
                    "name" => "Clothing Styles",
                    "values" => [
                        [
                            "id" => "1007447",
                            "name" => "Sweatshirt"
                        ]
                    ]
                ];
            }
        }
        return $attributes;
    }

    private function getCategoryId($product_type, $set)
    {
        if ($set == 1) {
            return $product_type === 'Hoodie' ? '601295' : ($product_type === 'Sweatshirt' ? '601295' : '601302');
        }
        return $product_type === 'Hoodie' ? '601213' : ($product_type === 'Sweatshirt' ? '601213' : '1165840');
    }
    private function uploadDescriptionImages($clientAppPartner, $src)
    {
        $uploadProductImage = $clientAppPartner->Product->uploadProductImage($src, 'DESCRIPTION_IMAGE');

        return ["uri" => $uploadProductImage['uri']];
    }
    private function uploadMainImages($clientAppPartner, $images)
    {
        $main_images = [];
        foreach ($images as $key => $image) {
            if ($key < 9 && !isset($image->color)) {
                $uploadProductImage = $clientAppPartner->Product->uploadProductImage($image->src, 'MAIN_IMAGE');
                $main_images[] = ["uri" => $uploadProductImage['uri']];
            }
        }
        return $main_images;
    }
    private function uploadAttributeImage($clientAppPartner, $json)
    {
        $attri_images = [];
        foreach ($json as $key => $image) {
            if (isset($image->color) && !empty($image->color)) {
                $uploadProductImage = $clientAppPartner->Product->uploadProductImage($image->src, 'ATTRIBUTE_IMAGE');
                $attri_images[$key]['color'] = $image->color;
                $attri_images[$key]['url'] = ["uri" => $uploadProductImage['uri']];
                \Log::channel('export-product-tiktokshop')->info("dataProduct");
                \Log::channel('export-product-tiktokshop')->info($image->color);
                \Log::channel('export-product-tiktokshop')->info(["uri" => $uploadProductImage['uri']]);
            }
        }
        return $attri_images;
    }
    private function uploadSizeChart($clientAppPartner, $src)
    {
        $uploadProductImage = $clientAppPartner->Product->uploadProductImage($src, 'SIZE_CHART_IMAGE');
        return ["uri" => $uploadProductImage['uri']];
    }

    private function createSkus($clientAppPartner, $variants, $images, $warehouse_id)
    {
        $skus = [];
        foreach ($variants as $variant) {
            $sales_attributes = [
                [
                    "id" => "100000",
                    "name" => "Color",
                    "value_name" => $variant->option2 ?? $variant->option2
                ],
                [
                    "id" => "100007",
                    "name" => "Size",
                    "value_name" => $variant->option3 ?? $variant->option3
                ]
            ];

            $imgurl = $this->findImageId($variant->option2);
            if ($imgurl) {
                // $sku_img = $clientAppPartner->Product->uploadProductImage($imgurl, 'ATTRIBUTE_IMAGE');
                $sales_attributes[0]["sku_img"] = $imgurl;
            }

            if (!empty($variant->option1)) {
                $sales_attributes[] = [
                    "name" => "Type",
                    "value_name" => $variant->option1
                ];
            }

            $variantConvert = [
                "price" => [
                    "currency" => "USD",
                    "amount" => $variant->price
                ],
                "sales_attributes" => $sales_attributes,
                "seller_sku" => "",
                "inventory" => [
                    [
                        "quantity" => (int) ($variant->quantity ?? 999),
                        "warehouse_id" => $warehouse_id
                    ]
                ]
            ];

            if (!in_array($variantConvert, $skus)) {
                $skus[] = $variantConvert;
            }
        }
        return $skus;
    }

    public function findImageId($color)
    {
        foreach ($this->imageattribute as $image) {
            if ($image['color'] === trim($color)) {
                return $image['url'];
            }
        }
        return null;
    }
}
