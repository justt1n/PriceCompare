<div class="bg-white">
    <div>

        <!-- No Reponsive -->
        <main class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-8">
            <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-6">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900">{{ __('messages.category_product') }}</h1>

                <div class="flex items-center">
                    <div class="relative inline-block text-left">
                        @php
                            $currentUrl = url()->current();

                            $params1 = app('request')->input();
                            $params2 = app('request')->input();

                            $params1['order'] = 'asc';
                            $params2['order'] = 'desc';

                            $urlWithParamOrderAsc = $currentUrl . '?' . http_build_query($params1);
                            $urlWithParamOrderDesc = $currentUrl . '?' . http_build_query($params2);
                        @endphp
                        <details class="dropdown cursor-pointer">
                            <summary id="summary_order_price" class="mx-10">
                                {{ request()->query('order') == 'desc' ? __('messages.price_high_to_low') : __('messages.price_low_to_high') }}
                            </summary>
                            <ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52">
                                <li><a id="summary_order_price_asc" class="font-semibold"
                                        href=" {{ $urlWithParamOrderAsc }}">{{__('messages.price_low_to_high')}}</a></li>
                                <li><a id="summary_order_price_desc" class="font-semibold"
                                        href="{{ $urlWithParamOrderDesc }}">{{__('messages.price_high_to_low')}}</a></li>
                            </ul>
                        </details>
                    </div>

                </div>
            </div>

            <section aria-labelledby="products-heading" class="pb-24 pt-6">
                <h2 id="products-heading" class="sr-only">Products</h2>

                <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                    <!-- Filters -->
                    <div class="hidden lg:block">
                        <h3 class="sr-only">Categories</h3>
                        <ul role="list"
                            class="space-y-4 border-b border-gray-200 pb-6 text-sm font-medium text-gray-800">
                            @php
                                $queryParamsKey = request()->query();
                                // unset($queryParamsKey['brand']);
                            @endphp
                            <li>
                                <a href="{{ route('user.product.filter').'?'.http_build_query($queryParamsKey) }}"
                                    class="{{ request()->is('product/filter') ? 'text-orange-600' : '' }}">{{ __('messages.all') }}
                                    ({{ $count['total'] }})</a>
                            </li>
                            <li>
                                <a href="{{ route('user.product.filter', 'mobile').'?'.http_build_query($queryParamsKey)  }}"
                                    class="{{ request()->is('product/filter/mobile') ? 'text-orange-600' : '' }}">{{ __('messages.moblie') }} ({{ $count['count_mobile'] }})</a>
                            </li>
                            <li>
                                <a href="{{ route('user.product.filter', 'tablet').'?'.http_build_query($queryParamsKey)  }}"
                                    class="{{ request()->is('product/filter/tablet') ? 'text-orange-600' : '' }}">{{ __('messages.tablet') }}
                                    ({{ $count['count_tablet'] }})</a>
                            </li>
                            <li>
                                <a href="{{ route('user.product.filter', 'laptop').'?'.http_build_query($queryParamsKey)  }}"
                                    class="{{ request()->is('product/filter/laptop') ? 'text-orange-600' : '' }}">{{ __('messages.laptop') }}
                                    ({{ $count['count_laptop'] }})</a>
                            </li>
                            <li>
                                <a href="{{ route('user.product.filter', 'smartwatch').'?'.http_build_query($queryParamsKey)  }}"
                                    class="{{ request()->is('product/filter/smartwatch') ? 'text-orange-600' : '' }}">{{ __('messages.smartwatch') }}
                                    ({{ $count['count_smartwatch'] }})</a>
                            </li>
                        </ul>

                        @php

                            $params4 = app('request')->input();
                            $urlWithParamPrice = $currentUrl . '?' . http_build_query($params4);

                            $brandForm = '';
                            $orderForm = '';
                            $searchForm = '';
                            if (!empty(request('brand'))) {
                                $brandForm = request('brand');
                            }
                            if (!empty(request('order'))) {
                                $orderForm = request('order');
                            }
                            if (!empty(request('search'))) {
                                $searchForm = request('search');
                            }

                            $currentAllBrand = getURLUnsetBrandOrSearch();

                            function getURLUnsetBrandOrSearch()
                            {
                                $queryParams = request()->query();
                                $newUrl = url()->full();
                                if (array_key_exists('brand', $queryParams)) {
                                    unset($queryParams['brand']);

                                    $newUrl = url()->current() . '?' . http_build_query($queryParams);
                                }
                                if (array_key_exists('search', $queryParams)) {
                                    unset($queryParams['search']);

                                    $newUrl = url()->current() . '?' . http_build_query($queryParams);
                                }
                                if (array_key_exists('page', $queryParams)) {
                                    unset($queryParams['page']);

                                    $newUrl = url()->current() . '?' . http_build_query($queryParams);
                                }
                                return $newUrl;
                            }

                        @endphp

                        <!-- filter price min max-->
                        <div class="border-b border-gray-200 py-6">
                            <form action="{{ $urlWithParamPrice }}" method="get" id="price_form">

                                <input type="hidden" name="order" value="{{ $orderForm }}">
                                <input type="hidden" name="brand" value="{{ $brandForm }}">
                                <input type="hidden" name="search" value="{{ $searchForm }}">
                                <div class="grid">
                                    <div class="py-2">
                                        <label class="" for="price_min">{{ __('messages.price_from') }}</label>
                                        <input id="price_min" class="input-sm w-2/5 rounded-lg ml-3 border-solid"
                                            pattern="\d*" maxlength="11" name="price_min"
                                            value="{{ request('price_min') }}">
                                    </div>
                                    <div class="py-2">
                                        <label for="price_max">{{ __('messages.price_to') }}</label>
                                        <input id="price_max" class="input-sm w-2/5 rounded-lg border-solid"
                                            pattern="\d*" maxlength="11" name="price_max"
                                            value="{{ request('price_max') }}">
                                    </div>
                                </div>

                                <button class="border-solid rounded-lg w-50 hover:bg-orange-500 px-1 py-1 btn mt-4"
                                    type="submit">{{ __('messages.search_by_price') }}</button>

                            </form>

                            {{-- <a href="{{ url()->current()  }}"> --}}
                            <button class="rounded-lg w-50  hover:bg-red-200 px-1 py-1 btn-sm mt-4" type="text"
                                id="delete_search">{{ __('messages.delete_all') }}</button>
                            {{-- </a> --}}
                        </div>

                        <div class="border-b border-gray-200 py-6">
                            <h3 class="-my-3 flow-root">
                                <!-- Expand/collapse section button -->
                                <button type="button"
                                    class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500"
                                    aria-controls="filter-section-0" aria-expanded="false">
                                    <span class="font-medium text-gray-900">{{ __('messages.brand') }}</span>
                                </button>
                            </h3>
                            <!-- Filter section, show/hide based on section state. -->
                            <div class="pt-6" id="filter-section-0">
                                <div class="space-y-4">

                                    <!-- Brand -->
                                    <div id="checkboxes" class="overflow-y-auto h-60">

                                        <div class="flex items-center  {{ request()->query('brand') == '' ? 'text-orange-600' : '' }}"
                                            id="brand_product">
                                            <a href="{{ $currentAllBrand }}" class="hover:bg-blue-200 "><input
                                                    type="button" id="checkbox_brand" name="brand" value="{{ __('messages.all') }}"
                                                    type="checkbox" class="ml-1 hover:cursor-pointer">
                                            </a>
                                        </div>
                                        @php
                                            function capitalizeWordsBeforeSpace($str) {
                                                $words = explode(" ", $str);
                                                $capitalizedWords = array_map('strtolower', $words);
                                                foreach ($capitalizedWords as $key => $value) {
                                                    $capitalizedWords[$key] = ucfirst($value);
                                                }
                                                $result = implode(" ", $capitalizedWords);

                                                return $result;
                                            }
                                        @endphp
                                        @foreach ($count['brands'] as $brand)

                                            @php
                                                $params3 = app('request')->input();
                                                $params3['brand'] = $brand->brand;
                                                $urlWithParamBrand = $currentUrl . '?' . http_build_query($params3);

                                                // Parse URL để lấy query string
                                                $urlComponents = parse_url($urlWithParamBrand);

                                                // Kiểm tra xem query string có tồn tại không
                                                if (isset($urlComponents['query'])) {
                                                    // Xóa tham số 'page' nếu có
                                                    $urlWithParamBrand = preg_replace('/([&\?]page=[^&]*)/', '', $urlWithParamBrand);

                                                    // Loại bỏ dấu & không cần thiết nếu có
                                                    $urlWithParamBrand = rtrim($urlWithParamBrand, '&');
                                                }
                                            @endphp

                                            <div class="flex items-center  {{ request()->query('brand') == $brand->brand ? 'text-orange-600' : '' }}"
                                                id="brand_product" data-brand="{{ $brand->brand }}">
                                                <a href="{{ $urlWithParamBrand }}" class="hover:bg-blue-200 "><input
                                                        type="button" id="checkbox_brand" name="brand"
                                                        value="{{ capitalizeWordsBeforeSpace($brand->brand) }}" type="checkbox"
                                                        class="ml-1 hover:cursor-pointer">
                                                    ({{ $brand->brand_count }})
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product grid -->
                    <div class="lg:col-span-3">
                        <div class="w-full flex flex-wrap mb-12">
                            @foreach ($data as $product)
                                <a href="{{ route('user.view.detail', $product->id) }}"
                                    class="card 2xl:w-1/4 xl:w-1/4 lg:w-1/3 md:w-1/2 sm:1 w-screen h-[400px] bg-base-100 shadow-xl rounded-none border-b border-r border-t border-solid border-gray-100 g-blue-100 hover:shadow-2xl hover:shadow-blue-500/50">
                                    <div id="product-click" class="hover:scale-105 duration-500 ">
                                        <figure class="">
                                            <!-- <a href="{{ route('user.view.detail', $product->id) }}"> -->
                                            <img href="{{ route('user.view.detail', $product->id) }}"
                                                src="{{ $product->image }}" alt="Shoes"
                                                class="w-40 h-44 py-5 object-fill " />
                                            <!-- </a>     -->
                                        </figure>
                                        <div class="card-body ">
                                            <!-- <div class=""><a class="card-title line-clamp-1" href="{{ route('user.view.detail', $product->id) }}">{{ $product->name }}</a></div> -->
                                            <p class="card-title line-clamp-1">{{ $product->name }}</p>
                                            <p class="text-red-600 font-medium">Giá từ
                                                {{ number_format($product->min_price, 0, ',', '.') }} đ</p>
                                            <p class="text-center">
                                            <div class="flex">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                </svg>
                                                <p class="ml-2">{{ __('messages.has') }} <span
                                                        class="text-red-600 font-medium">{{ $product->count_site }}
                                                    </span>{{ __('messages.similar_type') }}</p>
                                            </div>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                            @if (count($data) == 0)
                                <figure class="w-full flex items-center justify-center">
                                    <img src="https://img.freepik.com/premium-vector/search-result-find-illustration_585024-17.jpg?w=740"
                                        alt="">
                                </figure>
                            @endif
                        </div>
                        @if (count($data) > 0)
                            {{ $data->links() }}
                        @endif
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Lấy giá trị của thẻ input có id là price_min và price_max
        // Check xem giá trị của price_min có lớn hơn giá trị của price_max hay không và ngăn ngừa việc submit form
        $('#price_form').submit(function(event) {
            event.preventDefault();

            var minPrice = parseFloat($('#price_min').val());
            var maxPrice = parseFloat($('#price_max').val());

            if (minPrice > maxPrice) {
                Swal.fire({
                    toast: true,
                    icon: "error",
                    title: "Giá thấp nhất phải nhỏ hơn giá cao nhất",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    position: "top-start",
                });
            } else {
                $(this).unbind('submit').submit();
            }
        });

        $('#price_min, #price_max').on('input', function() {
            let inputValue = $(this).val();

            // Loại bỏ các kí tự đặc biệt và chỉ giữ lại số
            inputValue = inputValue.replace(/[^0-9]/g, '');

            // Giới hạn độ dài của chuỗi nhập vào 13 kí tự
            inputValue = inputValue.slice(0, 13);

            $(this).val(inputValue);
        });
        $('#delete_search').click(function() {
            window.location.href = "{{ route('user.product.filter') }}";
        });

    });
</script>
