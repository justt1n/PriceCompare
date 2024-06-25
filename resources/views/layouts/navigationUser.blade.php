<nav class="" id="navi">
    <div class="navbar bg-base-100 py-4 2xl:px-40 xl:px-40 lg:px-30 md:px-20 sm:px-10 px-10 mt-auto w-full ">
        <div class="flex w-1/5">
            <a href="{{ route('user.view.home') }}">
                <x-application-logo></x-application-logo>
            </a>
        </div>
        <div class="flex justify-between w-4/5 gap-2 ">
            @php
            $totalCompareItems = session('totalCompareItems') ?? 0;
            @endphp

            {{-- Search --}}
            {{-- @if (!request()->is('/')) --}}
            <div class="form-control w-4/5">
                <form action="{{ route('user.product.filter') }}" id="formSearch" method="get">
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
                            class="input input-bordered py-2 text-sm rounded-md pl-10 focus:outline-none focus:bg-white focus:text-gray-900 w-4/5"
                            value="{{ request()->input('search') }}" placeholder="Tìm kiếm" autocomplete="off">
                    </div>
                </form>
                <div id="suggestions-container" class="static">
                    <div class="absolute z-40  w-2/5 pt-2">
                        <ul id="suggestions-list" class="w-full bg-white " ></ul>
                    </div>
                </div>
            </div>
            {{-- @endif --}}

            <div class="flex justify-end">

                {{-- Compare --}}
                @if($totalCompareItems > 3) $totalCompareItems = 3;
                @endif
                <div class="indicator ml-8">
                    <span class="indicator-item badge badge-secondary">{{ $totalCompareItems }}</span>
                    <a href="/product/compare" class="btn">{{ __('messages.compare') }}</a>
                </div>
                {{-- Locate --}}
                <div class="dropdown ml-2">
                    <div tabindex="0" role="button" class="hover:bg-gray-100 m-1 p-1">
                        <div class="flex items-center">
                            <img src="{{ asset('/images/vietnam.png') }}" alt="" id="logoLanguage" class="w-8 h-8 mr-[8px]">
                            <p id="language">Vietnam</p>
                        </div>
                    </div>
                    <ul tabindex="0" id="languageDropdown"
                        class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-44">
                        <li onclick="toggleLanguage()">
                            <div>
                                <a href="{!! route('user.language', ['vi']) !!}" id="languageChange" class="flex items-center">
                                    <img src="{{ asset('/images/united-kingdom.png') }}" id="logoLanguageSecond"  alt=""
                                        class="w-8 h-8 mr-[8px]">
                                    <p id="languageSecond">English</p>
                                </a>
                            </div>
                        </li>
                        
                    </ul>
                </div>
            </div>



        </div>
    </div>
</nav>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var savedLanguage = localStorage.getItem("language");
    var savedLogoLanguage = localStorage.getItem("logo-language");
    var savedLogoLanguageSecond = localStorage.getItem("logo-language-second");
    var savedLanguageSecond = localStorage.getItem("languageSecond");
    console.log(savedLanguageSecond);
    if (savedLanguage) {
        document.getElementById("language").innerHTML = savedLanguage;
        document.getElementById("logoLanguage").src = savedLogoLanguage;
        document.getElementById("logoLanguageSecond").src = savedLogoLanguageSecond;
        document.getElementById("languageSecond").innerHTML = savedLanguageSecond;
    }
});

function toggleLanguage() {
    var languageElement = document.getElementById("language");
    var logoElement = document.getElementById("logoLanguage");
    var logoElementSecond = document.getElementById("logoLanguageSecond");
    var languageChange = document.getElementById("languageChange");
    var languageSecond = document.getElementById("languageSecond");
    var currentLanguage = languageElement.innerHTML.toLowerCase();
    if (currentLanguage === "vietnam") {
        languageElement.innerHTML = "English";
        languageSecond.innerHTML = "Vietnam";
        logoElement.src = "{{ asset('/images/united-kingdom.png') }}";
        logoElementSecond.src = "{{ asset('/images/vietnam.png') }}";
        languageChange.href = "{!! route('user.language', ['en']) !!}"
    } else {
        languageElement.innerHTML = "Vietnam";
        languageSecond.innerHTML = "English";
        logoElement.src = "{{ asset('/images/vietnam.png') }}";
        logoElementSecond.src = "{{ asset('/images/united-kingdom.png') }}";
        languageChange.href = "{!! route('user.language', ['vi']) !!}"
    }
    localStorage.setItem("language", languageElement.innerHTML);
    localStorage.setItem("logo-language", logoElement.src);
    localStorage.setItem("logo-language-second", logoElementSecond.src);
    localStorage.setItem("languageSecond", languageSecond.innerHTML);
}
</script>
<script>
$(document).ready(function() {

    function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function() {
                callback.apply(context, args);
            }, ms || 0);
        };
    }

    $('#search').keyup(delay(function(e) {
        var keyword = $(this).val();
        search(keyword);

    }, 700));


    // Chan ky tu dac biet
    $("#search").keypress(function(event) {

        var charCode = event.which || event.keyCode;
        // Chặn các ký tự đặc biệt
        if (charCode == 33 || charCode == 64 || charCode == 35 || charCode == 36 || charCode ==
            37 ||
            charCode == 94 || charCode == 38 || charCode == 42 || charCode == 40 || charCode ==
            41 ||
            charCode == 95 || charCode == 43 || charCode == 123 || charCode == 125 || charCode ==
            91 ||
            charCode == 93 || charCode == 58 || charCode == 59 || charCode == 60 || charCode ==
            62 ||
            charCode == 44 || charCode == 46 || charCode == 63 || charCode == 126 || charCode ==
            92 ||
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

    var suggestionsContainer = $("#suggestions-container");

    $(document).on("click", function(event) {
        var searchInput = $("#search");
        if (!suggestionsContainer.is(event.target) && suggestionsContainer.has(event.target)
            .length ===
            0 && !searchInput.is(event.target)) {
            // Nếu click bên ngoài #suggestions-container, ẩn phần gợi ý
            suggestionsContainer.hide();
        }
    });

    // Bắt sự kiện khi input #search được focus để hiển thị phần gợi ý
    $("#search").on("focus", function() {
        search('');
        // suggestionsContainer.show();
    });

    function search(keyword) {

            $.ajax({
                url: "/searchProd",
                method: "POST",
                data: {
                    keyword: keyword
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {

                $("#suggestions-list").empty();

                if (data.length === 0) {

                    // console.log('Không có kết quả tìm kiếm');
                    $("#suggestions-list").append(
                        "<li class='py-2 px-2 text-gray-400'>Không có kết quả tìm kiếm</li>"
                    );

                    suggestionsContainer.show();
                } else {
                    // console.log('Có kết quả tìm kiếm');
                    $("#suggestions-list").append("<li class='py-2 px-4 text-blue-400'>Có " +
                        data.length + " sản phẩm hiển thị trên màn hình!</li>");
                    // suggestionsContainer.show();
                    displayResults(data);
                }
                // displayResults(data);
            },

        });
    }

    function displayResults(data) {

        // $("#suggestions-list").empty();

        data.forEach(function(result) {
            var price = convertToVNDFormatWithoutDecimal(result.min_price);

            $("#suggestions-list").append(
                "<li class='py-2 px-2 hover:scale-105 duration-500 hover:text-blue-500 flex'><div class='pl-2'><figure class='items-center h-16 w-16'><img class='h-16 w-16' src=" +
                result.image +
                "></figure></div class='py-2'><div class='p-2'><a href='/detail/" + result.id +
                "'>" + result.name + "</a><p class='text-red-600 text-sm'>Giá: " + price +
                "</p></div></li><hr>");
        });
        suggestionsContainer.show();
    }
});
</script>
<script>
function toggleDropdown() {
    var dropdown = document.getElementById("languageDropdown");
    dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
}
</script>