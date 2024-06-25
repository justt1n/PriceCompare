<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SiteRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductSiteRepository;

class SiteController extends Controller
{
    public function __construct(
        $productRepository = new ProductRepository,
        $siteRepository = new SiteRepository,
        $productSiteRepository = new ProductSiteRepository,
    )
    {
        $this->productRepository = $productRepository;
        $this->siteRepository = $siteRepository;
        $this->productSiteRepository = $productSiteRepository;
    }

    public function index($id = null){
        if($id == null){
            $id = 1;
        }
        $sites = [];
        $sites['countProductsAllSite'] = count($this->productSiteRepository->getAll()) ?? 0;
        $sites['sites'] = $this->siteRepository->getAll() ?? [];
        $sites['products'] = $this->siteRepository->getProductWithSite($id) ?? [];
        // dd($sites['products']);
        return view('sites')->with('sites',$sites);
    }

    public function deleteProductSite($id){
        $check = $this->productSiteRepository->delete($id);
        if($check){
            $this->productRepository->decreaseCountSite($id);
        }
        return response()->json($check);
    }
}
