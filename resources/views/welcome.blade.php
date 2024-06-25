<style>
.loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f7f9fb;
    transition: opacity 0.75s, visibility 0.75s;
    z-index: 40;
}

.loader-hidden {
    opacity: 0;
    visibility: hidden;
}

.loader::after {
    content: "";
    width: 75px;
    height: 75px;
    border: 15px solid #dddddd;
    border-top-color: #4CB9E7;
    border-radius: 50%;
    animation: loading 0.75s ease infinite;
}

@keyframes loading {
    from {
        transform: rotate(0turn);
    }

    to {
        transform: rotate(1turn);
    }
}
</style>
<x-app-user-layout>
    <x-categories></x-categories>
    <div class="2xl:px-40 xl:px-40 lg:px-30 md:px-20 sm:px-10 px-10 mt-auto w-full">
        <div class="text-sm breadcrumbs mb-2 pt-4">
            <ul>
                <li><a href="/">{{ __('messages.home') }}</a></li>
            </ul>
        </div>
        <div class="flex">
            <div id="default-carousel" class="relative w-3/5 mb-4" data-carousel="slide">
                <!-- Carousel wrapper -->
                <div class="relative h-full w-fll overflow-hidden rounded-lg md:h-96">
                    <!-- Item 1 -->
                    <div class=" duration-500 ease-in-out h-full" data-carousel-item>
                        <img src="https://img.websosanh.vn/v2/users/bpi/images/s6jgokfnezqaf.jpg?compress=85"
                            class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 h-full"
                            alt="...">
                    </div>
                    <!-- Item 2 -->
                    <div class=" duration-500 ease-in-out h-full" data-carousel-item>
                        <img src="https://img.websosanh.vn/v2/users/bpi/images/oh9u7qa8sye2w.jpg?compress=85"
                            class=" absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 h-full"
                            alt="...">
                    </div>
                    <!-- Item 3 -->
                    <div class="hidden duration-500 ease-in-out h-full" data-carousel-item>
                        <img src="https://img.websosanh.vn/v2/users/bpi/images/c34cqb85cfdy2.jpg?compress=85"
                            class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 h-full"
                            alt="...">
                    </div>
                    <!-- Item 4 -->
                    <div class="hidden duration-500 ease-in-out h-full" data-carousel-item>
                        <img src="https://img.websosanh.vn/v2/users/bpi/images/pybyey4ubdn5l.jpg?compress=85"
                            class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 h-full"
                            alt="...">
                    </div>
                </div>
                <!-- Slider indicators -->
                <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                    <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1"
                        data-carousel-slide-to="0"></button>
                    <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2"
                        data-carousel-slide-to="1"></button>
                    <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3"
                        data-carousel-slide-to="2"></button>
                    <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4"
                        data-carousel-slide-to="3"></button>
                </div>
                <!-- Slider controls -->
                <button type="button"
                    class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-prev>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 1 1 5l4 4" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button"
                    class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-next>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>
            <div class="flex flex-col w-2/5 ml-4 h-full">
                <div class="w/full h-[183px] mb-4">
                    <img src="https://phongvu.vn/cong-nghe/wp-content/uploads/2022/05/sieu-sale-Phong-Vu.png"
                        class="w-full h-full object-fill" alt="...">
                </div>
                <div class="w/full h-[183px]">
                    <img src="https://fptshop.com.vn/uploads/originals/2018/12/10/636800515041963456_giam-bat-ngo.png"
                        class="w-full h-full object-fill" alt="...">
                </div>
            </div>
        </div>
        <div class="pb-6">
            <h1 class="mb-4 text-2xl font-medium">{{ __('messages.title_home') }}</h1>

            <div class="flex flex-wrap border-l border-solid border-gray-400 mb-4">
                <!-- List product -->
                @foreach ($datas as $data)
                <x-product :data="$data"></x-product>
                @endforeach
            </div>
            <!-- Pagination  -->
            <div style="padding-top:10px" class="w-full flex justify-center">
                <a href="/product/filter">
                    <button type="" class="btn w-48">{{ __('messages.view_more') }}</button>
                </a>
            </div>


        </div>
        <x-categories-area></x-categories-area>
    </div>
    <div class="loader flex flex-col-reverse">
        <!-- <img src="/images/meomeo.gif" alt=""> -->
    </div>
</x-app-user-layout>
<script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>
<script>
window.addEventListener("load", () => {
    const loader = document.querySelector(".loader");
    setTimeout(() => {
        loader.classList.add("loader-hidden");
    }, 200);

    loader.addEventListener("transitionend", () => {
        document.body.removeChild("loader");
    })
})
</script>