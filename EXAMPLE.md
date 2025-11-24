# Code example

## 1. Create the Model and migration:

First, create the ProductJson model implement ProductInterface. Run the following command to generate it:
```
php artisan make:model Product -m
```

Then, define the model properties and relationships in app/Models/Product.php:

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vanguard\Models\Interfaces\Product\ProductInterface;

class Product extends Model implement ProductInterface
{
    protected $fillable = ['title', 'description', 'json_data'];
}

```

Then, define the ProductJson table and columns in database/migrations/2023_10_09_021247_create_products_table.php
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

```

## 2. Create the Repository:

Now, create a repository for handling database interactions. Run this command to generate the repository:

```
php artisan make:repository ProductRepository
```
Create the repository in app/Repositories/ProductRepository.php:

```
<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function all()
    {
        return Product::all();
    }

    public function find($id)
    {
        return Product::find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $Product = Product::find($id);
        $Product->update($data);
        return $Product;
    }

    public function delete($id)
    {
        return Product::destroy($id);
    }
}

```

## 3. Create the Service:

Next, create a service to handle the business logic of your application.
Create the service in `app/Services/ProductService.php`:

```
<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $ProductRepository;

    public function __construct(ProductRepository $ProductRepository)
    {
        $this->ProductRepository = $ProductRepository;
    }

    public function getAllProducts()
    {
        return $this->ProductRepository->all();
    }

    public function getProductById($id)
    {
        return $this->ProductRepository->find($id);
    }

    public function createProduct(array $data)
    {
        return $this->ProductRepository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        return $this->ProductRepository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->ProductRepository->delete($id);
    }
}
```

## 4. Usage in Controller:

Finally, you can use the ProductService in your controller to perform CRUD operations and manage the application's business logic:

```
<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $ProductService;

    public function __construct(ProductService $ProductService)
    {
        $this->ProductService = $ProductService;
    }

    public function index()
    {
        $Products = $this->ProductService->getAllProducts();
        return view('Products.index', compact('Products'));
    }

    // Implement other CRUD methods here
}

```