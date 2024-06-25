<div class="pb-6">
    <h1 class="mb-4 text-2xl font-medium">Sản phẩm gợi ý hôm nay</h1>
    <div class="flex flex-wrap border-l border-solid border-gray-400">
        @foreach($data as $item)
            <x-product></x-product>
        @endforeach
    </div>
    @if (count($data) > 0)
        <div>
            <x-button-view-more></x-button-view-more>
        </div>
    @endif

</div>
