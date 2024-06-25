<div class="flex w-full mb-10 xl:flex-row lg:flex-col md:flex-col sm:flex-col">
    <div class="xl:w-2/5 lg:w-full md:w-full lg:mb-[20px] md:mb-[20px] xl:mb-[0px] flex mr-4">
        <div class="flex xl:flex-col  xl:w-1/5 lg:flex-row lg:w-full lg:h-full md:flex-row md:w-full md:h-[250px]">
            @if($dataImages != null)
            @foreach($dataImages as $dataImage)
            <div
                class="w-full xl:h-[120px] lg:h-full md:h-full border-r border-b border-l first:border-t border-solid border-gray-400 flex items-center justify-center">
                <img src="{{$dataImage->url}}" class="xl:w-20 xl:h-20 lg:h-full md:h-full mix-blend-multiply" alt=""
                    onclick="myFunction(this);">
            </div>
            @endforeach
            @else
            @foreach(range(0,3) as $data)
            <div
                class="w-full xl:h-[120px] lg:h-full md:h-full border-r border-b border-l first:border-t border-solid border-gray-400 flex items-center justify-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png"
                    class="xl:w-20 xl:h-20 lg:h-full md:h-full" alt="" onclick="myFunction(this);">
            </div>
            @endforeach
            @endif
        </div>
        <div class="h-full xl:w-4/5 lg:w-1/5 lg:hidden md:w-1/5 md:hidden sm:hidden xl:block">
            <div class="border border-solid border-gray-400 mr-10 h-full ml-4 flex items-center justify-center ">
                <img src="{{$data[0]->image}}" class="w-80 h-80 mix-blend-multiply" id="expandedImg" alt="">
            </div>
        </div>
    </div>
    <div class="w-3/5 flex flex-col justify-between ">
        <div>
            <div>
                <h1 class="mb-4 text-2xl font-bold">{{$data[0]->name}}</h1>
                <!-- <form method="POST" action="{{ route('user.product.compare.add', $data[0]->id) }}" class="swap-off fill-current">
                    @csrf
                    <button class="btn" type="submit">+</button>
                </form>
                <form method="post" action="{{ route('user.product.compare.delete', $data[0]->id) }}" class="swap-on fill-current">
                    @csrf
                    <button class="btn" type="submit">-</button>
                </form> -->
            </div>
            <p class="text-red-600 font-medium">{{ __('messages.price') }}: {{number_format($data[0]->min_price,0,',','.')}} đ</p>
            <p class="flex mt-4 text-sm">{{ __('messages.price_min_max') }}
                <span class="text-red-600 font-medium mx-1">{{number_format($data[0]->min_price,0,',','.')}}đ</span>
                {{ __('messages.to') }}
                <span class="text-red-600 font-medium mx-1">{{number_format($maxPriceProductSite,0,',','.')}}đ</span>
            </p>
        </div>
        <div class="flex justify-start lg:hidden md:hidden xl:flex">
            @foreach($productSite->slice(0, 4) as $site)
            <div class="border border-solid border-gray-400 w-[220px] h-[240px] mr-4">
                <div class="p-10 h-full flex flex-col items-center justify-between relative">
                    <span
                        class="w-[127px] h-[41px] absolute border-t-[25px] border-l-[12px] solid border-l-[#f3f4f6] border-t-green-600 right-[-1px] top-[-1px] text-center leading-3 before:content-['Giá_Hấp_Dẫn'] before:absolute before:top-[-19px] before:left-[4px] before:font-normal before:pt-[1px] text-white"></span>
                    @if($site->site_id === 1)
                    <img src="https://cdn.haitrieu.com/wp-content/uploads/2021/11/Logo-The-Gioi-Di-Dong-MWG-Y-V.png"
                        class="w-[110px] h-[80px] object-contain" alt="">

                    @elseif($site->site_id === 2)
                    <img src="https://i.gyazo.com/d3209e2122472de851c534c609a7884b.png"
                        class="w-[110px] h-[80px] object-contain" alt="">

                    @elseif($site->site_id === 3)
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/64/Logo_Tiki.png"
                        class="w-[110px] h-[80px] object-contain" alt="">

                    @elseif($site->site_id === 4)
                    <img src="https://static.topcv.vn/company_logos/dkO7S7nQlFlOYMeg5DUVFbd4bHFfl6Ls_1671264639____8166d458342e037c03178da0cc3e1ae6.png"
                        class="w-[110px] h-[80px] object-contain" alt="">

                    @elseif($site->site_id === 5)
                    <img src="https://i.gyazo.com/3d6b1174496b3dba3a6e70e88bcd6db6.png"
                        class="w-[110px] h-[80px] object-contain" alt="">
                    @elseif($site->site_id === 6)
                    <img src=" https://dienthoaigiakho.vn/_next/image?url=%2Fimages%2Fcommon%2Flogo.svg&w=3840&q=75"
                        class="w-[110px] h-[80px] object-contain" alt="">
                    @endif
                    <p class="text-red-600 font-medium">{{number_format($site->price,0,',','.')}} đ</p>
                    <a href="{{$site->url}}" target="blank"><button class="btn bg-orange-600 text-white">{{ __('messages.view_now') }}</button></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>


    <script>
    function myFunction(imgs) {
        var expandImg = document.getElementById("expandedImg");
        var imgText = document.getElementById("imgtext");
        expandImg.src = imgs.src;
    }
    </script>
</div>