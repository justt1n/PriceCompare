<div class="flex flex-col items-center justify-center px-44  ">
    <!-- Button and modal compare -->
    @if($productCompare)
        <?php $data = session()->get('productCompareArr');
        $totalCompareItems = session('totalCompareItems') ?? 0;
        $cateId = array_values($data)[0]['cate']; ?>
    @else
    <?php $cateId = null; ?>
    @endif
    <button class="btn bg-blue-100 hover:bg-blue-300 click_compare mb-10" onclick="my_modal_3.showModal()"
        data-id="{{$cateId}}">+ {{ __('messages.add_product') }}</button>
    <dialog id="my_modal_3" class="modal ">
        <div class="modal-box w-[1200px] h-[1200px]">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-0 top-0">✕</button>
            </form>
            <form method="" action="">
                <!-- Name -->
                <div>
                    <!-- <x-input-label for="name" :value="__('Search')" /> -->
                    <x-text-input id="searchCompare" class="block mt-1 w-full" type="text" name="searchCompare"
                        :value="old('searchCompare')" autofocus autocomplete="name" placeholder="Tìm tên điện thoại" />
                    <x-input-error :messages="$errors->get('searchCompare')" class="mt-2" />
                </div>
                <div class="flex flex-wrap" id="modalCompare">
                    <!-- Noi do du lieu -->
                </div>

            </form>
        </div>
    </dialog>
    <!-- -------------------------------------------------------------------- -->
    <div class="join w-full compare-container">
        <div class="w-1/4 h-[420px] border-[1px] solid rounded-none flex flex-col px-2 items-start justify-start">
            @if($productCompare)
            <p class="line-clamp-1 py-2">So sánh sản phẩm</p>
            @endif
            @if($productCompare)
            @foreach($productCompare as $product)

            <p class="card-title py-2">{{$product['name']}}</p>
            <p class="last:hidden">&</p>
            @endforeach
            @endif
        </div>
        @if($productCompare)
        @foreach($productCompare as $product)
        <a href="{{ route('user.view.detail', $product['productId']) }}"
            class="w-1/4 h-[420px] border-[1px] solid rounded-none relative flex flex-col items-center ">
            <form method="post" action="{{ route('user.product.compare.delete', $product['productId']) }}">
                @csrf
                <button type="submit" id="delete-compare">
                    <span class="indicator-item badge absolute top-2 right-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-[10px] h-[10px]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </span>
                </button>
            </form>
            <img src="{{$product['image']}}" class="object-cover max-w-[275px] mt-[35px] mb-[10px]" alt="">
            <p class="card-title line-clamp-1 px-8">{{$product['name']}}</p>
            <p class="text-red-600 font-medium">{{number_format($product['price'],0,',','.')}}đ</p>
        </a>
        @endforeach
        @endif
    </div>

    <!-- ---------------------------------------------------------------------------------------------- -->

    <div class="join w-full">
        <div class="w-1/4  border-[1px] solid rounded-none">
            @if($productCompare)

            @foreach($productCompare as $product)
            @foreach($product['attributes'] as $index => $attribute)

            {{-- Chuyển các key của mảng thành tiếng việt --}}
            @php
                if( $index == 'cpu' ) {
                    $index = 'Cấu hình';
                } elseif ($index == 'ram') {
                    $index = 'Ram';
                }  elseif ($index == 'pin') {
                    $index = 'Pin';
                } elseif ($index == 'display') {
                    $index = 'Màn hình';
                } elseif ($index == 'display-tech') {
                    $index = 'Công nghệ màn hình';
                } elseif ($index == 'gpu') {
                    $index = 'Chip đồ họa';
                } elseif ($index == 'os') {
                    $index = 'Hệ điều hành';
                } elseif ($index == 'rom') {
                    $index = 'Bộ nhớ trong';
                } elseif ($index == 'size') {
                    $index = 'Kích thước';
                }
            @endphp

            <p class="border-b p-3 even:bg-gray-50 h-[50px]">{{$index}}</p>
            @endforeach
            @break
            @endforeach
            @endif
        </div>
        @if($productCompare)
        @foreach($productCompare as $product)
        <div class="w-1/4 border-[1px] solid rounded-none relative flex flex-col items-center">
            @foreach($product['attributes'] as $index => $attribute)
            <p class="w-full border-b p-3 line-clamp-1 truncate even:bg-gray-50 h-[50px]">{{$attribute}}</p>
            @endforeach
        </div>
        @endforeach
        @endif
    </div>
    <!-- <div id="divphp server with Ajax functiCompare" class="w-full h-[550px]">

    </div> -->
    @if(!empty($totalCompareItems))
        <input type="hidden" value={{$totalCompareItems}} id="totalCompareItems">
    @endif
</div>
<script>
    $(document).ready(function () {

        // Set time delay keyup prevent spam request
        function delay(callback, ms) {
            var timer = 0;
            return function () {
                var context = this,
                    args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        $('#searchCompare').keyup(delay(function (e) {
            var keyword = $(this).val();
            search(keyword);
        }, 700));

        var category = 0;

        //Bắt sự kiện khi input #searchCompare được focus để hiển thị phần gợi ý
        // $("#searchCompare").on("focus", function () {
        //     search('', category);
        // });

        // $("#searchCompare").keyup(function () {
        //     var keyword = $(this).val();
        //     search(keyword, category);
        // });

        // Chặn ký tự đặc biệt
        $("#searchCompare").keypress(function (event) {

            var charCode = event.which || event.keyCode;
            // Chặn các ký tự đặc biệtcategory
            if (charCode == 33 || charCode == 64 || charCode == 35 || charCode == 36 || charCode == 37 ||
                charCode == 94 || charCode == 38 || charCode == 42 || charCode == 40 || charCode == 41 ||
                charCode == 95 || charCode == 43 || charCode == 123 || charCode == 125 || charCode == 91 ||
                charCode == 93 || charCode == 58 || charCode == 59 || charCode == 60 || charCode == 62 ||
                charCode == 44 || charCode == 46 || charCode == 63 || charCode == 126 || charCode == 92 ||
                charCode == 47 || charCode == 45) {
                event.preventDefault();
            }
        });

        function convertToVNDFormatWithoutDecimal(decimalNumber) {
            var roundedNumber = Math.floor(decimalNumber);
            return roundedNumber.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        }

        $(".click_compare").click(function () {
            // Your click event handler logic here
            category = $(this).data("id");
            search('', category);
        });

        function search(keyword) {

            $.ajax({
                url: "/search",
                method: "POST",
                data: {
                    keyword: keyword,
                    category: category
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {

                    displayResults(data);
                    if (data.length === 0) {
                        $("#modalCompare").append(
                            "<p class='py-2 px-2 text-gray-400'>Không có kết quả tìm kiếm</p>");
                    }
                },

            });
        }

        function displayResults(data) {

            $("#modalCompare").empty();

            data.forEach(function (result) {
                var price = convertToVNDFormatWithoutDecimal(result.min_price);

                $("#modalCompare").append(
                    "<div class='cart w-1/3 pt-2'><div href='' class='card '><div id='product-click' class=''><figure class=''><img href='' src='" +
                    result.image +
                    "' alt='phone' class='w-20 h-22 py-5 object-fill ' /><!-- </a>     --></figure><div class='card-body py-0 px-1'><p class='text-center text-xs'>" +
                    result.name + "</p><p class='text-red-600 text-center  text-xs'>" + price +
                    "đ</p><a href='#' class='text-sky-700 text-center compare-button  text-md hover:scale-105 duration-500 ' data-product-id='" +
                    result.id + "'>{{ __('messages.add_compare') }}</a></div></div></div></div>"
                );

            });
        }

    $(document).on('click', '.compare-button', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var numberData =  $('#totalCompareItems').val();
        if(numberData === undefined){
            numberData = 1;
        }
        else{
            numberData = Number(numberData) + 1;
        }
        if(numberData <= 3){
            $.ajax({
            url: "/product/compare/add/" + productId,
            method: "POST",
            data: {},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                window.location.reload();
            },
            error: function(error) {
                console.error("Error adding product to compare:", error);
            }
        });
        }else{
            my_modal_3.close();
            Swal.fire({
                    toast: true,
                    icon: "error",
                    title: "Tối đa 3 sản phẩm!!",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    position: "top-end",
                });
        }
    });

        // $(document).on('click', '#button-compare', function(e) {
        //     e.preventDefault();
        //     var productId = $('.click_compare ').data('product-id');
        //     $.ajax({
        //         url: "/product/compare/add/" + productId,
        //         method: "POST",
        //         data: {},
        //         async: true,
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(data) {
        //         },
        //         error: function(error) {
        //             console.error("Error adding product to compare:", error);
        //         }
        //     });
        // });

    });
</script>
