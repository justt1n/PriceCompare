<?php

namespace App\Repositories;

use App\Interfaces\IProductRepository;
use App\Models\Product;

class ProductRepository implements IProductRepository
{
    private Product $product;

    /**
     * Create a new controller instance.
     *
     * @param $product
     *
     * @return void
     */
    public function __construct()
    {
        $this->product = new Product;
    }

    public function save(array $products)
    {
        $dataCreate = [
            'name' => $products['name'],
            'brand' => $products['brand'],
            'public' => $products['public'],
            'active' => $products['active'],
            'image' => $products['image'],
            'min_price' => $products['min_price'],
            'count_site' => $products['count_site'],
            'min_price_site_id' => $products['min_price_site_id'],
            'category_id' => $products['category_id'],
            'created_by' => $products['created_by'],
        ];
        return $this->product::create($dataCreate);
    }

    public function update($id, array $products)
    {
        $dataUpdate = [
            'name' => $products['name'],
            'brand' => $products['brand'],
            'public' => $products['public'],
            'active' => $products['activve'],
            'image' => $products['image'],
            'min_price' => $products['min_price'],
            'min_price_site_id' => $products['min_price_site_id'],
            'category_id' => $products['category_id'],
            'created_by' => $products['created_by'],
        ];
        if (isset($products['updated_by'])) {
            $dataUpdate['updated_by'] = $products['updated_by'];
        }
        if (isset($products['deleted_by'])) {
            $dataUpdate['deleted_by'] = $products['deleted_by'];
        }

        return $this->product::where('id', $id)->update($dataUpdate);
    }
    public function delete($id)
    {
        return $this->product->where('id', $id)->delete();
    }

    public function getById($id)
    {
        return $this->product->where('id', $id)->first();
    }

    public function getAll()
    {
        return $this->product->all();
    }

    public function compare()
    {
        return;
    }
    public function getBySiteId($siteId)
    {
        return;
    }
    public function getByName($name)
    {
        return $this->product->where('name', $name)->first();
    }
    public function getBySiteIdAndProductId($siteId, $productId)
    {
        return;
    }

    public function updateMinPrice($id, $price, $site_id)
    {
        return $this->product->where('id', $id)->update(['min_price' => $price, 'min_price_site_id' => $site_id]);
    }
    public function updateCountSite($id)
    {
        return $this->product->where('id', $id)->increment('count_site');
    }
    public function countProducts($request, $category_id = 0)
    {
        $query = $this->product;
        if (!empty($request->brand)) {
            $query = $query->where('brand', $request->brand);
        }
        if(!empty($request->search)){
            $query = $query->where('name', 'like', '%' . request('search') . '%');
        }
        if (!empty($request->price_min)) {
            $query = $query->where('min_price', '>=', request('price_min'));
        }
        if (!empty($request->price_max)) {
            $query = $query->where('min_price', '<=', request('price_max'));
        }
        if ($category_id == 0) {
            return $query->count();
        }
        return $query->where('category_id', $category_id)->count();
    }

    public function getAllBrand($request)
    {
        $query = $this->product;
        if (!empty($request->category)) {
            $query = $query->where('category_id', $request->category);
        }
        if(!empty($request->search)){
            $query = $query->where('name', 'like', '%' . request('search') . '%');
        }
        if (!empty($request->price_min)) {
            $query = $query->where('min_price', '>=', request('price_min'));
        }
        if (!empty($request->price_max)) {
            $query = $query->where('min_price', '<=', request('price_max'));
        }
        $query = $query->select('brand', \DB::raw('COUNT(*) as brand_count'))
                            ->groupBy('brand')
                            ->get();
        return $query;
    }

    public function countAllProducts()
    {
        return $this->product->count();
    }

    public function decreaseCountSite($id)
    {
        return $this->product->where('id', $id)->decrement('count_site');
    }
}
