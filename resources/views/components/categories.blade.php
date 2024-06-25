<div class="flex bg-base-300 bg-blue-100  lg:px-30 md:px-20 sm:px-10 px-10 mt-auto w-full">
    <div class="hover:text-blue-500  2xl:flex  my-5 border-r-2 border-blue-600 border-solid pl-20 hidden w-2/5 cursor-pointer">
        <a href="/product/filter" class="xl:flex">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <span class="mx-3">{{ __('messages.category_product') }}</span>
        </a>
    </div>
    <div class="flexitems-center my-4 md:w-full w-full 2xl:ms-8 xl:ms-8 2xl:pr-80 xl:pr-60 lg:pr-40">
        <ul
            class="flex w-full h-full justify-between items-center md:w-full lg:flex sm:flex-wrap md:flex-wrap flex-wrap 2xl:justify-around xl:justify-around lg:justify-around">
            <li class=" hover:text-blue-500 ">

                <a href="{{ route('user.product.filter', 'mobile') }}"
                    class="{{ request()->is('product/filter/mobile') ? 'text-orange-600' : '' }} flex align-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                    </svg>
                    <span class="pl-2">
                        {{ __('messages.moblie') }}
                    </span>

                </a>
            </li>

            <li class="hover:text-blue-500 ">
                <a href="{{ route('user.product.filter', 'tablet') }}"
                    class="{{ request()->is('product/filter/tablet') ? 'text-orange-600' : '' }} flex align-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
<path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 19.5h3m-6.75 2.25h10.5a2.25 2.25 0 002.25-2.25v-15a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 4.5v15a2.25 2.25 0 002.25 2.25z" />                    </svg>
                    <span class="pl-2">
                        {{ __('messages.tablet') }}
                    </span>

                </a></li>

            <li class="hover:text-blue-500 "><a href="{{ route('user.product.filter', 'laptop') }} "
                    class="{{ request()->is('product/filter/laptop') ? 'text-orange-600' : '' }} flex align-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                    </svg>
                    <span class="pl-2">
                        {{ __('messages.laptop') }}
                    </span>
                    </a>
            </li>

            <li class="hover:text-blue-500 "><a href="{{ route('user.product.filter', 'smartwatch') }}"
                    class="{{ request()->is('product/filter/smartwatch') ? 'text-orange-600' : '' }} flex align-center">
                    <svg xmlns="http://www.w3.org/2000/svg"fill="currentColor" class="w-6 h-6 bi bi-smartwatch" viewBox="0 0 16 16"> <path d="M9 5a.5.5 0 0 0-1 0v3H6a.5.5 0 0 0 0 1h2.5a.5.5 0 0 0 .5-.5V5z"/> <path d="M4 1.667v.383A2.5 2.5 0 0 0 2 4.5v7a2.5 2.5 0 0 0 2 2.45v.383C4 15.253 4.746 16 5.667 16h4.666c.92 0 1.667-.746 1.667-1.667v-.383a2.5 2.5 0 0 0 2-2.45V8h.5a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5H14v-.5a2.5 2.5 0 0 0-2-2.45v-.383C12 .747 11.254 0 10.333 0H5.667C4.747 0 4 .746 4 1.667zM4.5 3h7A1.5 1.5 0 0 1 13 4.5v7a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 11.5v-7A1.5 1.5 0 0 1 4.5 3z"/> </svg>
                    <span class="pl-2">
                        {{ __('messages.smartwatch') }}
                    </span>
                </a>
            </li>
        </ul>
    </div>
</div>
