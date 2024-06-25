@php
$requestSiteId = request('siteId');
$requestOrderBy = request('orderBy');
@endphp
<div class="py-10">
    <!-- Tab -->
    @if($requestSiteId != null)
    <?php $requestSiteId = request('siteId'); ?>
    @else
    <?php $requestSiteId = 0; ?>
    @endif
    <div role="tablist" class="tabs tabs-lifted w-3/5">
        <a role="tab" href="#site" class="tab tab-active">{{ __('messages.compare_price') }}</a>
        <a role="tab" href="#info" class="tab">{{ __('messages.info_product') }}</a>
        <a role="tab" href="#attribute" class="tab">{{ __('messages.info_attribute') }}</a>
    </div>
    <!-- Filter -->
    <div class="flex w-full h-[64px] items-center justify-between mt-2" id="site">
        <span class="font-semibold">{{ __('messages.price') }} <span>{{$numberSite}}</span> {{ __('messages.similar_type')
            }}</span>
        <div class="flex items-center">

            <details class=" ml-4 dropdown cursor-pointer">
                <summary class="mx-2">{{__('messages.similar_type') }}:
                    {{($requestSiteId == 0) ? __('messages.all')
                    : ($requestSiteId == 1 ? "Thế giới di động"
                    : ($requestSiteId == 2 ? "FPT Shop"
                    : ($requestSiteId == 3 ? "Tiki"
                    : ($requestSiteId == 4 ? "Di Động Việt"
                    : ($requestSiteId == 5 ? "Phong Vũ"
                    : "Điện Thoại Giá Kho")))))}}
                </summary>
                <ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52">
                    <li><a class="font-semibold"
                            href="{{ route('user.view.detail', ['id' => $dataProduct[0]->id , 'siteId' => null,'orderBy' => null]) }}">Tất
                            cả</a></li>
                    <li><a
                            href="{{ route('user.view.detail', ['id' => $dataProduct[0]->id , 'siteId' => 1,'orderBy' => null]) }}">Thế
                            giới di động</a></li>
                    <li><a
                            href="{{ route('user.view.detail', ['id' => $dataProduct[0]->id , 'siteId' => 2,'orderBy' => null]) }}">FPT
                            Shop</a></li>
                    <li><a
                            href="{{ route('user.view.detail', ['id' => $dataProduct[0]->id , 'siteId' => 3,'orderBy' => null]) }}">Tiki
                        </a></li>
                    <li><a
                            href="{{ route('user.view.detail', ['id' => $dataProduct[0]->id , 'siteId' => 4,'orderBy' => null]) }}">Di
                            Động Việt</a></li>
                    <li><a
                            href="{{ route('user.view.detail', ['id' => $dataProduct[0]->id , 'siteId' => 5,'orderBy' => null]) }}">Phong
                            Vũ </a></li>
                    <li><a
                            href="{{ route('user.view.detail', ['id' => $dataProduct[0]->id , 'siteId' => 6,'orderBy' => null]) }}">Điện
                            Thoại Giá Kho</a></li>
                </ul>
            </details>
            <details class="dropdown cursor-pointer">
                <summary class="mx-2">{{__('messages.arrange')}}:
                    {{($requestOrderBy === null) ? __('messages.price') : ($requestOrderBy == "asc" ?
                    __('messages.price_low_to_high') : __('messages.price_high_to_low')) }}
                </summary>
                <ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52">
                    <li><a
                            href="{{ route('user.view.detail', ['id' => $dataProduct[0]->id , 'siteId' => $requestSiteId, 'orderBy' => 'asc']) }}">{{__('messages.price_low_to_high')}}</a>
                    </li>
                    <li><a
                            href="{{ route('user.view.detail', ['id' => $dataProduct[0]->id , 'siteId' => $requestSiteId, 'orderBy' => 'desc']) }}">{{__('messages.price_high_to_low')}}</a>
                    </li>
                </ul>
            </details>
        </div>
    </div>
    <div class="divider mb-4 mt-0 before:bg-gray-400 before:h-[1px] after:bg-gray-400 after:h-[1px]"></div>
    <!-- List Page -->
    <div class="xl:px-0 overflow-scroll max-h-[600px]">
        @if($dataProductSite->isNotEmpty())
        @foreach($dataProductSite as $item)
        <div class="flex flex-col w-full">
            <div class="flex w-full flex-row h-18 card rounded-box">
                <div class="w-1/5 flex flex-col justify-start items-center mr-20">
                    @if($item->site_id === 1)
                    <img src="https://cdn.haitrieu.com/wp-content/uploads/2021/11/Logo-The-Gioi-Di-Dong-MWG-Y-V.png"
                        class="w-[110px] h-[80px] object-contain" alt="">

                    @elseif($item->site_id === 2)
                    <img src="https://i.gyazo.com/d3209e2122472de851c534c609a7884b.png"
                        class="w-[110px] h-[80px] object-contain" alt="">

                    @elseif($item->site_id === 3)
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/64/Logo_Tiki.png"
                        class="w-[110px] h-[80px] object-contain" alt="">

                    @elseif($item->site_id === 4)
                    <img src="https://static.topcv.vn/company_logos/dkO7S7nQlFlOYMeg5DUVFbd4bHFfl6Ls_1671264639____8166d458342e037c03178da0cc3e1ae6.png"
                        class="w-[110px] h-[80px] object-contain" alt="">

                    @elseif($item->site_id === 5)
                    <img src="https://i.gyazo.com/3d6b1174496b3dba3a6e70e88bcd6db6.png"
                        class="w-[110px] h-[80px] object-contain" alt="">
                    @elseif($item->site_id === 6)
                    <img src=" https://dienthoaigiakho.vn/_next/image?url=%2Fimages%2Fcommon%2Flogo.svg&w=3840&q=75"
                        class="w-[110px] h-[80px] object-contain" alt="">
                    @endif
                </div>
                <div class="w-1/5 flex flex-col justify-center items-center mr-32">
                    @if($item->site_id === 1)

                    <span>thegioididong.com</span>
                    @elseif($item->site_id === 2)

                    <span>fptshop.com.vn</span>
                    @elseif($item->site_id === 3)

                    <span>tiki.vn</span>
                    @elseif($item->site_id === 4)

                    <span>didongviet.vn</span>
                    @elseif($item->site_id === 5)
                    <span>phongvu.vn</span>
                    @elseif($item->site_id === 6)
                    <span>dienthoaigiakho.vn</span>
                    @endif
                </div>
                <div class="w-4/5 flex justify-between items-center">
                    @if($item['image'])
                    <img src="{{$item['image']['url']}}" class="object-contain w-[75px] h-[75px] mr-4 mix-blend-multiply" alt="">
                    @else
                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png"
                        class="object-contain w-[75px] h-[75px] mr-4" alt="">
                    @endif
                    <h3 class="w-1/3 line-clamp-2"><a href="{{$item->url}}" target="_blank">{{$item->name}}</a></h3>
                    <div>
                        <p class="text-red-600 font-medium">{{ __('messages.price') }}: {{number_format($item->price,0,',','.')}} đ</p>
                        <p class="text-xs mt-2">{{ __('messages.update') }}: {{date('d-m-Y', strtotime($item->updated_at))}}</p>   
                    </div>
                    <a href="{{$item->url}}" target="blank" class=""><button class="btn bg-orange-600 text-white">{{ __('messages.go_site') }}</button></a>
                </div>
            </div>
            <div class="divider before:bg-gray-400 before:h-[1px] after:bg-gray-400 after:h-[1px] mt-2 mb-4"></div>
        </div>
        @endforeach
        @else
        <div class="flex flex-col w-full">
            <div class="flex w-full flex-col h-80 justify-center items-center card rounded-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-60 h-60 mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                <span>Không có sản phẩm của website này</span>
            </div>
        </div>
        @endif
    </div>
</div>

