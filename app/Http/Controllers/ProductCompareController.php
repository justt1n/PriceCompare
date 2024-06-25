<?php

namespace App\Http\Controllers;

use App\Repositories\AttributeRepository;
use App\Repositories\ProductAttributeRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductCompareController extends Controller
{
    public function view()
    {
        $data = Session::get('productCompareArr');
        ;
        return view("compare")->with('data', $data);
    }
    public function addProductCompare($id)
    {
        $productAttribute = new ProductAttributeRepository;
        $productAttributes = $productAttribute->getByProductId($id);
        $products = new ProductRepository;
        $name = [];
        foreach ($productAttributes as $pro) {
            $attributeRepository = new AttributeRepository;
            $tmp = $attributeRepository->getById($pro->attribute_id);
            $tmp2 = $productAttribute->getByAttributeIdAndProductId($pro->attribute_id, $id);
            $name[$tmp->name] = $tmp2?->value;
        }
        $attributesToCompare = [];
        foreach ($name as $key => $value) {
            $key = strtolower($key);
            $map = [
                //man hinh
                'screen-size' => 'display',
                'display' => 'display',
                'màn hình' => 'display',
                'màn hình rộng' => 'display',
                'kích thước màn hình' => 'display',
                'kích thước màn hình (inch)' => 'display',
                'công nghệ màn hình' => 'display-tech',
                //do phan giai
                'độ phân giải màn hình' => 'res',
                'độ phân giải' => 'res',
                'screen-res' => 'res',
                //cpu
                'cpu' => 'cpu',
                'chip set' => 'cpu',
                'công nghệ CPU' => 'cpu',
                'chip' => 'cpu',
                'chip xử lý (cpu)' => 'cpu',
                //gpu
                'model' => 'gpu',
                'vga' => 'gpu',
                'chip đồ họa (gpu)' => 'gpu',
                'chip đồ họa' => 'gpu',
                //rom
                'rom' => 'rom',
                'dung lượng' => 'rom',
                'bộ nhớ trong' => 'rom',
                'ổ cứng' => 'rom',
                'lưu trữ' => 'rom',
                'dung lượng ssd' => 'rom',
                //ram
                'dung lượng ram' => 'ram',
                'ram' => 'ram',
                //can nang
                'weight' => 'weight',
                'trong lượng' => 'weight',
                'kích thước, khối lượng' => 'weight',
                //he dieu hanh
                'hệ điều hành' => 'os',
                //pin
                'pin' => 'pin',
                'dung lượng pin' => 'pin',
                'dung lượng pin (mah)' => 'pin',
                'công suất pin' => 'pin',
                //sw attributeé
                'chất liệu dây' => 'size',
                'kích thước' => 'size',
                //connection
                'kết nối' => 'connect',
                'kết nối không dây' => 'connect',
            ];

            if (array_key_exists($key, $map)) {
                $attributesToCompare[$map[$key]] = $value;
                unset($name[$key]);
            }
        }
        $order = ['cpu', 'ram', 'pin', 'display', 'display-tech', 'gpu', 'os', 'rom', 'size'];
        $attributesToCompare2 = [];

        foreach ($order as $key) {
            if (isset($attributesToCompare[$key])) {
                $attributesToCompare2[$key] = $attributesToCompare[$key];
            } else {
                $attributesToCompare2[$key] = 'Đang cập nhật';
            }
        }
        var_dump($attributesToCompare2['display']);
        $productCompareArr = session('productCompareArr', []);
        $product = $products->getById($id);
        $productCompareArr[$id] = [
            'productId' => $id,
            'name' => $product->name,
            'price' => $product->min_price,
            'image' => $product->image,
            'cate' => $product->category_id,
            'attributes' => $attributesToCompare2,
            'additionAttributes' => $name,
        ];
        //  session()->pull('productCompareArr');
        $total = count($productCompareArr);
        if ($total <= 3) {
            session(['productCompareArr' => $productCompareArr]);
        }
        session(['totalCompareItems' => $total]);
        response()->json(['message' => "Add Success", "data" => $productCompareArr]);
        return redirect()->back();
    }

    public function deleteProductCompare($id, Request $request)
    {
        $productCompareArr = Session::get('productCompareArr');
        if (isset($productCompareArr[$id])) {
            unset($productCompareArr[$id]);
            $total = count($productCompareArr);
            session(['productCompareArr' => $productCompareArr, 'totalCompareItems' => $total]);
        }
        return redirect()->back();
    }
}
