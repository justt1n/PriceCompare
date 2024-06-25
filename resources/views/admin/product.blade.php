@php
    $detailsSummary = 'All';
    $temp = 0;
    $arrangePrice = 'Order By Asc';
    if (request()->is('admin/product/category/1') || request()->is('admin/product/category/1/desc') || request()->is('admin/product/category/1/asc')) {
        $detailsSummary = 'Mobile';
        $temp = 1;
    } elseif (request()->is('admin/product/category/2') || request()->is('admin/product/category/2/desc') || request()->is('admin/product/category/2/asc')) {
        $detailsSummary = 'Laptop';
        $temp = 2;
    } elseif (request()->is('admin/product/category/3') || request()->is('admin/product/category/3/desc') || request()->is('admin/product/category/3/asc')) {
        $detailsSummary = 'Tablet';
        $temp = 3;
    } elseif (request()->is('admin/product/category/4') || request()->is('admin/product/category/4/desc') || request()->is('admin/product/category/4/asc')) {
        $detailsSummary = 'Smartwatch';
        $temp = 4;
    }

    if (request()->is('admin/product/category/1/desc') || request()->is('admin/product/category/2/desc') || request()->is('admin/product/category/3/desc') || request()->is('admin/product/category/4/desc') || request()->is('admin/product/category/0/desc')) {
        $arrangePrice = 'Order By Desc';
    } elseif (request()->is('admin/product/category/1/asc') || request()->is('admin/product/category/2/asc') || request()->is('admin/product/category/3/asc') || request()->is('admin/product/category/4/asc')|| request()->is('admin/product/category/0/asc')) {
        $arrangePrice = 'Order By Asc';
    }
@endphp
<x-app-layout>
    <section class="pl-80 pr-40 py-10">
        <div class="w-full py-2 border rounded-xl bg-base-300 mb-4">
            <h1 class="text-3xl my-4 px-4">Product Page</h1>
        </div>
        <div class="overflow-x-auto">
            <div class="flex w-full h-[64px] items-center justify-between mt-2" id="site">
                <span class="font-semibold">Total: {{ $total }}</span>
                <form action="/admin/product" id="formSearch" method="get">
                    <div class="relative text-gray-600 focus-within:text-gray-400 w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2" id="icon-search">
                            <button type="submit" class="p-1 focus:outline-none focus:shadow-outline">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </span>
                        <input type="search" name="search" id="search"
                            class="input input-bordered py-2 text-sm rounded-md pl-10 focus:outline-none focus:bg-white focus:text-gray-900 w-full"
                            value="{{ request()->input('search') }}" placeholder="Search..." autocomplete="off">
                    </div>
                </form>
                <div>
                    <details class="dropdown cursor-pointer">
                        <summary class="mx-2 detailsSummary">Type: {{ $detailsSummary }}</summary>
                        <ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52">
                            <!-- class="font-semibold" -->
                            <li><a href="{{ route('admin.view.product') }}">Tất cả</a></li>
                            <li><a href="{{ route('admin.view.getProductsByCategory', 1) }}">Mobile</a></li>
                            <li><a href="{{ route('admin.view.getProductsByCategory', 2) }}">Laptop</a></li>
                            <li><a href="{{ route('admin.view.getProductsByCategory', 3) }}">Tablet</a></li>
                            <li><a href="{{ route('admin.view.getProductsByCategory', 4) }}">Smartwatch</a></li>
                        </ul>
                    </details>
                    <details class="dropdown cursor-pointer pr-10">
                        <summary class="mx-2 detailsSummary">{{ $arrangePrice }}</summary>
                        <ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52">
                            <!-- <li><a class="font-semibold">Nổi bật</a></li> -->
                            <li><a
                                    href="{{ route('admin.view.getProductsByCategory', ['id' => $temp, 'orderBy' => 'asc']) }}">Order
                                    By Asc</a></li>
                            <li><a
                                    href="{{ route('admin.view.getProductsByCategory', ['id' => $temp, 'orderBy' => 'desc']) }}">Order
                                    By Desc</a></li>
                        </ul>
                    </details>
                </div>
            </div>
            <div class="divider mb-4 mt-0 before:bg-gray-400 before:h-[1px] after:bg-gray-400 after:h-[1px]"></div>
            <table class="table mb-8 ">
                <!-- head -->
                @php
                    $count = 0;
                    $count = count($datas);
                @endphp
                @if ($count != 0)
                    <thead>
                        <tr class="text-lg">
                            <th></th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Min Price</th>
                            @if (auth()->user()->role == 'superAdmin')
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                @endif
                <tbody>
                    <!-- body -->
                    @foreach ($datas as $data)
                        <tr class="hover">
                            <th>{{ ($datas->currentPage() - 1) * 10 + $loop->index + 1 }}</th>
                            <td class="image-container hover:scale-[2.0] hover:-translate-x-1/5 hover:-translate-y-2/3 duration-500">
                                <img src="{{ $data->image }}" class="w-16 h-16 small-image" id="smallImage" alt="">
                                <div class="large-image" id="largeImage">
                                    <!-- Large Image Content Goes Here -->
                                </div>
                            </td>
                            <td class="hover:scale-105 duration-300">
                                <a  href="/detail/{{$data->id}}" target="blank">
                                    {{ $data->name }}</td>
                                </a>
                            <td>{{ ucwords(strtolower($data->brand)) }}</td>
                            <td class="text-red-600 text-xs">{{ number_format($data->min_price, 0, ',', '.') }} đ</td>

                            @if (auth()->user()->role == 'superAdmin')
                                <td>
                                    <div class="cursor-pointer hover:scale-125 duration-300" id="delete_product" data-id="{{ $data->id }}" data-name="{{ $data->name }}">
                                        <svg width="55px" height="55px" viewBox="-266.24 -266.24 1556.48 1556.48" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000" stroke="#000000" stroke-width="0.01024"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M667.8 362.1H304V830c0 28.2 23 51 51.3 51h312.4c28.4 0 51.4-22.8 51.4-51V362.2h-51.3z" fill="#CCCCCC"></path><path d="M750.3 295.2c0-8.9-7.6-16.1-17-16.1H289.9c-9.4 0-17 7.2-17 16.1v50.9c0 8.9 7.6 16.1 17 16.1h443.4c9.4 0 17-7.2 17-16.1v-50.9z" fill="#CCCCCC"></path><path d="M733.3 258.3H626.6V196c0-11.5-9.3-20.8-20.8-20.8H419.1c-11.5 0-20.8 9.3-20.8 20.8v62.3H289.9c-20.8 0-37.7 16.5-37.7 36.8V346c0 18.1 13.5 33.1 31.1 36.2V830c0 39.6 32.3 71.8 72.1 71.8h312.4c39.8 0 72.1-32.2 72.1-71.8V382.2c17.7-3.1 31.1-18.1 31.1-36.2v-50.9c0.1-20.2-16.9-36.8-37.7-36.8z m-293.5-41.5h145.3v41.5H439.8v-41.5z m-146.2 83.1H729.5v41.5H293.6v-41.5z m404.8 530.2c0 16.7-13.7 30.3-30.6 30.3H355.4c-16.9 0-30.6-13.6-30.6-30.3V382.9h373.6v447.2z" fill="#211F1E"></path><path d="M511.6 798.9c11.5 0 20.8-9.3 20.8-20.8V466.8c0-11.5-9.3-20.8-20.8-20.8s-20.8 9.3-20.8 20.8v311.4c0 11.4 9.3 20.7 20.8 20.7zM407.8 798.9c11.5 0 20.8-9.3 20.8-20.8V466.8c0-11.5-9.3-20.8-20.8-20.8s-20.8 9.3-20.8 20.8v311.4c0.1 11.4 9.4 20.7 20.8 20.7zM615.4 799.6c11.5 0 20.8-9.3 20.8-20.8V467.4c0-11.5-9.3-20.8-20.8-20.8s-20.8 9.3-20.8 20.8v311.4c0 11.5 9.3 20.8 20.8 20.8z" fill="#211F1E"></path></g></svg>
                                    </div>
                                </td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                    @if ($count == 0)
                    <div class="flex justify-center ">
                        <figure class="w-1/2 ">
                            <img src="https://img.freepik.com/premium-vector/search-result-find-illustration_585024-17.jpg?w=740"
                                alt="" class="mix-blend-multiply">
                        </figure>

                    </div>
                    @endif
                </tbody>
            </table>
            {{ $datas->links() }}
        </div>
    </section>
</x-app-layout>

<script>

    $(document).ready(function() {

        $(document).on('click', '#delete_product', function() {

            var idProduct = $(this).data('id');
            let nameProduct = $(this).data('name');
            Swal.fire({
                title: 'Do you want to delete <div class="text-blue-500">'+ nameProduct +'?</div>',
                text: "You won't be able to revert this!",
                icon: 'warning',
                width: 800,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/product/delete/' + idProduct,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if(response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: 'Delete <div class="text-blue-500">'+ nameProduct +'</div> successfully!',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: 'Delete <div class="text-blue-500">'+ nameProduct +'</div> failed!',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });
                            }

                            timeOut = setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: "error",
                                title: 'Delete <div class="text-blue-500">'+ nameProduct +'</div> failed!',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                            });
                        }

                    });
                }
            });
        });

        // Chan ky tu dac biet
        $("#search").keypress(function(event) {

            var charCode = event.which || event.keyCode;
            // Chặn các ký tự đặc biệt
            if (charCode == 33 || charCode == 64 || charCode == 35 || charCode == 36 || charCode == 37 ||
                charCode == 94 || charCode == 38 || charCode == 42 || charCode == 40 || charCode == 41 ||
                charCode == 95 || charCode == 43 || charCode == 123 || charCode == 125 || charCode == 91 ||
                charCode == 93 || charCode == 58 || charCode == 59 || charCode == 60 || charCode == 62 ||
                charCode == 44 || charCode == 46 || charCode == 63 || charCode == 126 || charCode == 92 ||
                charCode == 47 || charCode == 45 ) {
                event.preventDefault();
            }
        });
    });
</script>
