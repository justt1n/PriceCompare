<?php

namespace App\Http\Controllers;

use App\Helpers\ScrapeHelper;
use App\Http\Helpers\NotificationHelper;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSite;
use App\Repositories\AttributeRepository;
use App\Repositories\ProductAttributeRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ProductsController extends Controller
{

    public function __construct($productRepository = new ProductRepository )
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request){
        $datas = new Product;
        if (!empty($request->search)) {
            $datas = $datas->where("name", "LIKE", '%' . $request->search . '%');
        }
        $datas = $datas->Paginate(10)->withQueryString();

        return view('welcome')->with('datas',$datas);
    }

    public function index_filter(Request $request)
    {
        $count = [];
        $datas = new Product;

        if (!empty($request->category)) {
            if ($request->category == 'mobile') {
                $request->category = 1;
            } elseif ($request->category == 'laptop') {
                $request->category = 2;
            } elseif ($request->category == 'tablet') {
                $request->category = 3;
            } elseif ($request->category == 'smartwatch') {
                $request->category = 4;
            }
            $datas = $datas->where('category_id', $request->category);
        } else {
            $request->category = 0;
        }


        $count['brands'] = $this->productRepository->getAllBrand($request);
        // foreach ($count['brands'] as $key => $value) {
        //     $count['brands'][$key] = ucwords(strtolower($value->brand));
        // }

        // dd($count['brands']);

        $count['total'] = $this->productRepository->countProducts($request, );
        $count['count_mobile'] = $this->productRepository->countProducts($request, 1);
        $count['count_laptop'] = $this->productRepository->countProducts($request, 2);
        $count['count_tablet'] = $this->productRepository->countProducts($request, 3);
        $count['count_smartwatch'] = $this->productRepository->countProducts($request, 4);
        // dd($request->all());
        if(!empty($request->brand)) {
            $datas = $datas->where('brand', $request->brand);
        }
        if(!empty($request->price_min)) {
            $datas = $datas->where('min_price', '>=', $request->price_min);
        }
        if(!empty($request->price_max)) {
            $datas = $datas->where('min_price', '<=', $request->price_max);
        }
        if(!empty($request->order)) {
            if ($request->order == 'asc') {
                $datas = $datas->orderBy('min_price', 'asc');
            } elseif ($request->order == 'desc') {
                $datas = $datas->orderBy('min_price', 'desc');
            }
            $datas = $datas->orderBy('min_price', 'desc');
        } else {
            $datas = $datas->orderBy('min_price', 'asc');
        }

        if(!empty($request->search)) {
            $datas = $datas->where("name", "LIKE", '%' . $request->search . '%');
        }

        $datas = $datas->Paginate(12)->withQueryString();
        // dd( $datas);

        return view('filter')->with('datas',$datas)->with('count',$count);
    }
    public function indexAdmin(Request $request ,$id = null, $orderBy = null){
        $datas = new Product;
        if (!empty($id)) {
            $datas = $datas->where('category_id', $id);
        }
        // dd($orderBy );
        $datas = $datas->orderBy('min_price');
        if (!empty($orderBy)) {
            if ($orderBy == 'asc') {
                $datas = $datas->orderBy('min_price');
            } elseif ($orderBy == 'desc') {
                $datas = $datas->orderByDesc('min_price');
            }
        }
        if (!empty($request->search)) {
            $datas = $datas->where("name", "LIKE", '%' . $request->search . '%');
        }
        $total = $datas->count();
        $datas = $datas->Paginate(10)->withQueryString();
        return view('admin.product')->with('datas',$datas)->with('total',$total);
    }


    public function detailProduct($id , $siteId = null , $orderBy = null){
        $productAttribute = new ProductAttributeRepository;
        $data = Product::where('id',$id)->get();
        $dataProductSites = ProductSite::where('product_id',$id)->get()->sortBy('price');
        $numberSite = count($dataProductSites);
        $dataImage = ProductImage::where('product_site_id',$dataProductSites[0]->id)->limit(3)->get();
        $productAttributes =  $productAttribute->getByProductId($id);
        $name = [];
        foreach($productAttributes as $pro) {
            $attributeRepository = new AttributeRepository;
            $tmp = $attributeRepository->getById($pro->attribute_id);
            $tmp2 = $productAttribute->getByAttributeIdAndProductId($pro->attribute_id, $id);
            $name[$tmp->name] = $tmp2?->value;
        }
        $attributesToCompare = ScrapeHelper::mapAttribute($name);

        // Similar Product
        $dataProductSimilar = Product::where('id',$id)->first();
        $price = $dataProductSimilar['min_price'];
        $category = $dataProductSimilar['category_id'];
        $priceSimilar = $price/4 + 2000000;
        $dataSimilars = Product::where('category_id', $category)
        ->where('brand', $dataProductSimilar['brand'])
        ->where('id','<>', $dataProductSimilar['id'])
        ->where('brand', $dataProductSimilar['brand'])
        ->whereRaw("min_price > {$price} - {$priceSimilar} and min_price <= {$price} + {$priceSimilar}")
        ->limit(5)
        ->get();
        foreach($dataProductSites as $dataProductSite){
            $dataProductSite['image'] = ProductImage::where('product_site_id',$dataProductSite->id)->first();
        }
        // dd($orderBy,$siteId);
        if (!empty($orderBy)) {
            if ($orderBy == 'asc') {
                $dataProductSites = $dataProductSites->sortBy('price');
            } elseif ($orderBy == 'desc') {
                $dataProductSites = $dataProductSites->sortByDesc('price');
            }
        }
        $totalFilterProductSite =  count($dataProductSites->where('site_id',1));
        if (!empty($siteId)) {
            $dataProductSites = $dataProductSites->where('site_id',$siteId);
        }
        if($dataProductSites->isNotEmpty() || !$dataProductSites->isNotEmpty()){
            $maxPriceProductSite = $dataProductSites->max('price');
            //dd($dataProductSites);
        }
        return view('detail')
        ->with('data',$data)
        ->with('dataProductSite',$dataProductSites)
        ->with('attributes',$attributesToCompare)
        ->with('datas',$dataSimilars)
        ->with('dataImages',$dataImage)
        ->with('numberSite',$numberSite)
        ->with('totalFilterProductSite',$totalFilterProductSite)
        ->with('maxPriceProductSite',$maxPriceProductSite);
    }

    public function getProductsByCategory($category)
    {
        $category_id = 0;
        if ($category == 'mobile') {
            $category_id = 1;
        } elseif ($category == 'laptop') {
            $category_id = 2;
        } elseif ($category == 'tablet') {
            $category_id = 3;
        } elseif ($category == 'smartwatch') {
            $category_id = 4;
        }
        $datas = new Product;
        if ( !empty($category_id) ) {
            $datas = $datas->where('category_id', $category_id);
        }

        $datas = $datas->Paginate(10);

        return view('welcome')->with('datas',$datas);
    }

    public function deleteProduct($id){

        $datas = new Product;
        $check = $datas->where('id',$id)->delete();
        return response()->json($check);

    }

    public function search(Request $request){

        $datas = new Product;
        $keyword = $request->keyword;

        $datas = $datas->where("name", "LIKE", '%' . $keyword. '%');

        if(!empty($request->category)) {
            $datas = $datas->where('category_id', $request->category);
        }
        $datas = $datas->limit(120)->get();

        return response()->json($datas);
    }

    public function searchProd(Request $request){

        $datas = new Product;
        $keyword = $request->keyword;

        $datas = $datas->where("name", "LIKE", '%' . $keyword. '%');

        if(!empty($request->category)) {
            $datas = $datas->where('category_id', $request->category);
        }
        $datas = $datas->limit(10)->get();

        return response()->json($datas);
    }

    public function searchFilter(Request $request)
    {
        $count = [];
        $datas = new Product;
        $keyword = $request->search;

        $datas = $datas->where("name", "LIKE", '%' . $keyword. '%');
        $datas = $datas->Paginate(10)->withQueryString();
        $count['brands'] = $this->productRepository->getAllBrand($request, $keyword);

        foreach ($count['brands'] as $key => $value) {
            $count['brands'][$key] = ucwords(strtolower($value->brand));
        }
        $count['total'] = $this->productRepository->countProducts();
        $count['count_mobile'] = $this->productRepository->countProducts(1);
        $count['count_laptop'] = $this->productRepository->countProducts(2);
        $count['count_tablet'] = $this->productRepository->countProducts(3);
        $count['count_smartwatch'] = $this->productRepository->countProducts(4);
        // dd($request->all());
        // return redirect()->route('user.product.filter')->with([
        //     'datas'=> $datas,
        //     'count'=> $count
        // ]);
        return view('filter')->with('datas',$datas)->with('count',$count);
    }

    public function changLanguage(Request $request){
        $lang = $request->language;
        $language = config('app.locale');
        if ($lang == 'en') {
            $language = 'en';
        }
        if ($lang == 'vi') {
            $language = 'vi';
        }
        Session::put('language', $language);
        return redirect()->back();
    }
}
