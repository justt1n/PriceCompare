<x-app-user-layout>
    <x-categories></x-categories>
    <div class="xl:px-40 lg:px-20 md:px-20 sm:px-20 pt-4">
        <div class="text-sm breadcrumbs mb-2">
            <ul>
                <li><a href="/">{{ __('messages.home') }}</a></li>
                <li><a href="{{ route('user.view.detail', $data[0]->id) }}">{{ __('messages.detail') }}: {{$data[0]->name}}</a></li>
            </ul>
        </div>
        <x-product-detail :data="$data" :dataImages="$dataImages" :maxPriceProductSite="$maxPriceProductSite" :productSite="$dataProductSite"></x-product-detail>
        <x-list-page-crawler :dataProductSite="$dataProductSite" :numberSite="$numberSite" :dataProduct="$data" :totalFilterProductSite="$totalFilterProductSite"></x-list-page-crawler>
        <x-info-product :dataAttribute="$attributes"></x-info-product>
        <x-similar-product :data="$datas"></x-similar-product>
    </div>
</x-app-user-layout>
