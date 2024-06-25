<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.4/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="icon" href="{{asset('images/logo.png')}}" type="image/x-icon">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <style>
        html {
            scroll-behavior: smooth;
        }
     </style>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigationUser')
            <!-- Page Content -->
            <main id="main_user">
                {{ $slot }}
            </main>
            <a href="#" id="toTopBtn" class="fixed bottom-40 right-8 bg-gray-200 z-10 text-black py-2 px-4 rounded cursor-pointer hidden">
                <svg width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M17 15L12 10L7 15" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
            </a>
            {{-- @include('layouts.footerUser') --}}
            <footer id="footer_user"
                class="justify-between bg-white footer p-10 bg-base-200 text-base-content 2xl:px-40 xl:px-40 lg:px-30 md:px-20 sm:px-10 px-10 mt-auto w-full">

                <aside>
                    <x-application-logo></x-application-logo>
                    <p class="font-bold"><span class="text-lg text-blue-700">Công ty công nghệ HKT</span> <br />Hỗ trợ thiết kế xây dựng website uy tín, chất lượng, bắt đầu từ tháng 11/2023</p>
                </aside>
                {{-- <nav>
                    <header class="footer-title">Dịch vụ</header>
                    <a class="link link-hover" href="#">Thương hiệu</a>
                    <a class="link link-hover" href="#">Thiết kế</a>
                    <a class="link link-hover" href="#">Marketing</a>
                    <a class="link link-hover" href="#">Quảng cáo</a>
                </nav>
                <nav>
                    <header class="footer-title">Công ty</header>
                    <a class="link link-hover" href="#">Về chúng tôi</a>
                    <a class="link link-hover" href="#">Liên hệ</a>
                    <a class="link link-hover" href="#">Tuyển dụng</a>
                    <a class="link link-hover" href="#">Truyền thông</a>
                </nav>
                <nav>
                    <header class="footer-title">Pháp lý</header>
                    <a class="link link-hover" href="#">Điều khoản sử dụng</a>
                    <a class="link link-hover" href="#">Chính sách quyền riêng tư</a>
                    <a class="link link-hover" href="#">Bảo mật thông tin</a>
                    <a class="link link-hover" href="#">Cookie</a>
                </nav> --}}
                <nav>
                    <header class="footer-title">Chủ sở hữu</header>
                    <a class="link link-hover font-bold" href="#">Huỳnh Nhật Khánh</a>
                    <a class="link link-hover font-bold" href="#">Nguyễn Trung Tín</a>
                    <a class="link link-hover font-bold" href="#">Nguyễn Trọng Hiếu</a>
                </nav>
            </footer>
        </div>
        <script src="https://cdn.tailwindcss.com"></script>
    </body>
</html>
<script>
    // jQuery để xử lý sự kiện cuộn và cuộn lên đầu trang
    $(document).ready(function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 20) {
                $('#toTopBtn').fadeIn();
            } else {
                $('#toTopBtn').fadeOut();
            }
        });

        $('#toTopBtn').click(function() {
            $('html, body').animate({scrollTop : 0}, 800);
            return false;
        });
    });
    // Bắt sự kiện khi input #search được focus để hiển thị phần gợi ý
    $("#search").on("focus", function() {
        // suggestionsContainer.show();
        $("#main_user, #footer_user").css({
            'filter': 'blur(3px)',
            '-webkit-filter': 'blur(3px)',
            'pointer-events': 'none',
        });
    });
    $("#search").on("blur", function() {
        $("#main_user, #footer_user").css({
            'filter': 'blur(0px)',
            '-webkit-filter': 'blur(0px)',
            'pointer-events': 'auto',
        });
    });

</script>
