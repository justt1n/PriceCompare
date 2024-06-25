<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SiteRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

class DashboardController extends Controller
{
    public function __construct(
        $productRepository = new ProductRepository,
        $userRepository = new UserRepository,
        $siteRepository = new SiteRepository,
        $categoryRepository = new CategoryRepository,
    )
    {
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->siteRepository = $siteRepository;
        $this->categoryRepository = $categoryRepository;
    }
    public function show()
    {
        $counts = [];
        $counts['product'] = $this->productRepository->countAllProducts() ?? 0;
        $counts['site'] = $this->siteRepository->countAllSites() ?? 0;
        $counts['category'] = $this->categoryRepository->countAllCategories() ?? 0;
        $counts['user'] = $this->userRepository->countAllUsers() ?? 0;
        $counts['admin'] = $this->userRepository->countAllAdmins() ?? 0;
        $counts['countProductSite'] = [];
        if(!empty( $this->siteRepository->countProductBySite(1))) {
            $counts['countProductSite']['TGDD'] = $this->siteRepository->countProductBySite(1);
        }
        if(!empty( $this->siteRepository->countProductBySite(2))) {
            $counts['countProductSite']['FPTShop'] = $this->siteRepository->countProductBySite(2);
        }
        if(!empty( $this->siteRepository->countProductBySite(3))) {
            $counts['countProductSite']['Tiki'] = $this->siteRepository->countProductBySite(3);
        }
        if(!empty( $this->siteRepository->countProductBySite(4))) {
            $counts['countProductSite']['Di Động Việt'] = $this->siteRepository->countProductBySite(4);
        }
        if(!empty( $this->siteRepository->countProductBySite(5))) {
            $counts['countProductSite']['Phong Vũ'] = $this->siteRepository->countProductBySite(5);
        }
        if(!empty( $this->siteRepository->countProductBySite(6))) {
            $counts['countProductSite']['Điện Thoại Giá Kho'] = $this->siteRepository->countProductBySite(6);
        }

        return view('dashboard-admin')->with('counts', $counts);
    }

}
