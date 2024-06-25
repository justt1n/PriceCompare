<a href="{{ route('user.view.detail', $data->id) }}" class="card 2xl:w-1/5 xl:w-1/4 lg:w-1/3 md:w-1/2 sm:1/2 w-screen h-[400px] bg-base-100 shadow-xl rounded-none border-b border-r border-t border-solid border-gray-100 g-blue-100 hover:shadow-2xl hover:shadow-blue-500/50">
    <div id="product-click" class="hover:scale-105 duration-500 ">
        <figure class="">
            <!-- <a href="{{ route('user.view.detail', $data->id) }}"> -->
            <img
                href="{{ route('user.view.detail', $data->id) }}"
                src="{{$data->image}}"
                alt="Shoes" class="w-40 h-44 py-5 object-fill " />
            <!-- </a>     -->
        </figure>
        <div class="card-body ">
            <!-- <div class=""><a class="card-title line-clamp-1" href="{{ route('user.view.detail', $data->id) }}">{{$data->name}}</a></div> -->
            <p class="card-title line-clamp-1" >{{$data->name}}</p>
            <p class="text-red-600 font-medium">{{ __('messages.price') }} {{number_format($data->min_price,0,',','.')}} đ</p>
            <p class="text-center">
            <div class="flex">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" id="add1"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <p class="ml-2">{{ __('messages.has') }} <span class="text-red-600 font-medium">{{ $data->count_site}} </span>{{ __('messages.similar_type') }}</p>
            </div>
            </p>
        </div>
    </div>
</a>

<script>
     $(document).ready(function() {

        $('#add1').click(function() {
            
        })
     });
</script>

