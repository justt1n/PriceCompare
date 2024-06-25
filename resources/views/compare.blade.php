<x-app-user-layout>
    <x-categories></x-categories>
    <div class="text-sm breadcrumbs mb-2 px-40 pt-4">
        <ul>
            <li><a href="/">{{ __('messages.home') }}</a></li>
            <li><a href="{{ route('user.product.compare') }}">{{ __('messages.compare') }}</a></li>
        </ul>
    </div>
    <x-body-product-compare :productCompare="$data"></x-body-product-compare>
</x-app-user-layout>
