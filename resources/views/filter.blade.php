<x-app-user-layout>
    <x-categories></x-categories>
    <div class="text-sm breadcrumbs mb-none px-40 bg-white pt-4">
        <ul>
            <li><a href="/">{{ __('messages.home') }}</a></li>
            <li><a href="{{ route('user.product.filter') }}">{{ __('messages.filter') }}</a></li>
        </ul>
    </div>
    <x-filter-body :data="$datas" :count="$count">

    </x-filter-body>
</x-app-user-layout>
