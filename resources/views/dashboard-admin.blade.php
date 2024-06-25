<x-app-layout>

    <div class="pl-72 mr-5 mt-2">
        <div class="w-full py-2 border rounded-xl bg-base-300 mb-4">
            <h1 class="text-3xl my-3 px-4">Dashboard</h1>
        </div>
        <div class="flex flex-wrap -ml-3 mb-5">

            <div class="w-1/2 xl:w-1/4 px-2 ">
                <a href="{{ route('admin.view.list') }}" >
                    <div class="w-full bg-white border hover:bg-gray-100 rounded-lg flex items-center p-6 mb-6 xl:mb-0">
                        <svg width="113px" height="113px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 fill-current mr-4 hidden lg:block">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M4 21C4 17.4735 6.60771 14.5561 10 14.0709M19.8726 15.2038C19.8044 15.2079 19.7357 15.21 19.6667 15.21C18.6422 15.21 17.7077 14.7524 17 14C16.2923 14.7524 15.3578 15.2099 14.3333 15.2099C14.2643 15.2099 14.1956 15.2078 14.1274 15.2037C14.0442 15.5853 14 15.9855 14 16.3979C14 18.6121 15.2748 20.4725 17 21C18.7252 20.4725 20 18.6121 20 16.3979C20 15.9855 19.9558 15.5853 19.8726 15.2038ZM15 7C15 9.20914 13.2091 11 11 11C8.79086 11 7 9.20914 7 7C7 4.79086 8.79086 3 11 3C13.2091 3 15 4.79086 15 7Z"
                                    stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </g>
                        </svg>
                        <div class="text-gray-700">
                            <p class="font-semibold text-3xl">{{ $counts['user'] + $counts['admin'] }}</p>
                            <p>Total Users</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="w-1/2 xl:w-1/4 px-2">
                <a href="{{ route('admin.view.product')}}">
                    <div class="w-full bg-white border hover:bg-gray-100 text-black rounded-lg flex items-center p-6"><svg fill="#000000"
                            viewBox="-4 0 19 19" xmlns="http://www.w3.org/2000/svg"
                            class="cf-icon-svg w-16 h-16 fill-current mr-4 hidden lg:block">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M10.114 2.69v13.76a1.123 1.123 0 0 1-1.12 1.12H2.102a1.123 1.123 0 0 1-1.12-1.12V2.69a1.123 1.123 0 0 1 1.12-1.12h6.892a1.123 1.123 0 0 1 1.12 1.12zm-1.12 1.844H2.102V14.78h6.892zm-5.31-1.418a.56.56 0 0 0 .56.56h2.61a.56.56 0 0 0 0-1.12h-2.61a.56.56 0 0 0-.56.56zm2.423 13.059a.558.558 0 1 0-.559.558.558.558 0 0 0 .559-.558z">
                                </path>
                            </g>
                        </svg>
                        <div class="text-gray-700">
                            <p class="font-semibold text-3xl">{{ $counts['product'] }}</p>
                            <p>Total Products</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="w-1/2 xl:w-1/4 px-2">
                <a href="{{ route('admin.view.site', 1)}}">
                    <div class="w-full bg-white border hover:bg-gray-100 text-blue-400 rounded-lg flex items-center p-6 mb-6 xl:mb-0">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="w-16 h-16 fill-current mr-4 hidden lg:block">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.63605 5.63605C7.19815 4.07395 9.73081 4.07395 11.2929 5.63605L14.1213 8.46448C15.6834 10.0266 15.6834 12.5592 14.1213 14.1213C13.7308 14.5119 13.0976 14.5119 12.7071 14.1213C12.3166 13.7308 12.3166 13.0976 12.7071 12.7071C13.4882 11.9261 13.4882 10.6597 12.7071 9.87869L9.87869 7.05026C9.09764 6.26922 7.83131 6.26922 7.05026 7.05026C6.26922 7.83131 6.26922 9.09764 7.05026 9.87869L7.75737 10.5858C8.1479 10.9763 8.14789 11.6095 7.75737 12C7.36685 12.3905 6.73368 12.3905 6.34316 12L5.63605 11.2929C4.07395 9.73081 4.07395 7.19815 5.63605 5.63605ZM11.2929 9.8787C11.6834 10.2692 11.6834 10.9024 11.2929 11.2929C10.5119 12.074 10.5119 13.3403 11.2929 14.1213L14.1213 16.9498C14.9024 17.7308 16.1687 17.7308 16.9498 16.9498C17.7308 16.1687 17.7308 14.9024 16.9498 14.1213L16.2427 13.4142C15.8521 13.0237 15.8521 12.3905 16.2427 12C16.6332 11.6095 17.2663 11.6095 17.6569 12L18.364 12.7071C19.9261 14.2692 19.9261 16.8019 18.364 18.364C16.8019 19.9261 14.2692 19.9261 12.7071 18.364L9.8787 15.5356C8.3166 13.9735 8.3166 11.4408 9.8787 9.8787C10.2692 9.48817 10.9024 9.48817 11.2929 9.8787Z"
                                    fill="#000000"></path>
                            </g>
                        </svg>
                        <div class="text-gray-700">
                            <p class="font-semibold text-3xl">{{ $counts['site'] }}</p>
                            <p>Total Sites</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="w-1/2 xl:w-1/4 px-2">
                <a href="{{ route('admin.view.product')}}">
                    <div class="w-full bg-white border hover:bg-gray-100 rounded-lg flex items-center p-6"><svg viewBox="0 0 24 24"
                            fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="w-16 h-16 fill-current mr-4 hidden lg:block">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M17 10H19C21 10 22 9 22 7V5C22 3 21 2 19 2H17C15 2 14 3 14 5V7C14 9 15 10 17 10Z"
                                    stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M5 22H7C9 22 10 21 10 19V17C10 15 9 14 7 14H5C3 14 2 15 2 17V19C2 21 3 22 5 22Z"
                                    stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path opacity="0.34"
                                    d="M6 10C8.20914 10 10 8.20914 10 6C10 3.79086 8.20914 2 6 2C3.79086 2 2 3.79086 2 6C2 8.20914 3.79086 10 6 10Z"
                                    stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path opacity="0.34"
                                    d="M18 22C20.2091 22 22 20.2091 22 18C22 15.7909 20.2091 14 18 14C15.7909 14 14 15.7909 14 18C14 20.2091 15.7909 22 18 22Z"
                                    stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </g>
                        </svg>
                        <div class="text-gray-700">
                            <p class="font-semibold text-3xl">{{ $counts['category'] == 5 ? 4 : $counts['category'] }}</p>
                            <p>Total Categories</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="container mx-auto mt-0">
            <canvas id="circleChart" width="600" height="600"></canvas>
        </div>

        <div>
            <p class="py-5 text-2xl flex justify-center">A chart depicting the number of products on various websites</p>
        </div>
    </div>

</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        var ctx = document.getElementById('circleChart').getContext('2d');
        var data = {
            labels: {!! json_encode(array_keys($counts['countProductSite'])) !!},
            datasets: [{
                data: {!! json_encode(array_values($counts['countProductSite'])) !!},
                backgroundColor: ['#ffff33', 'red', '#1a1aff', '#b30000', '#99ccff', '#ffd152'],
            }]
        };

        var options = {
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 0,
            tooltips: {
                enabled: true,
                mode: 'nearest',
                titleFontSize: 40, // Đặt kích thước chữ cho tiêu đề trong tooltip khi hover
            },
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    fontSize: 40, // Đặt kích thước chữ cho các label
                }
            },
        };

        var circleChart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options
        });
    });
</script>
