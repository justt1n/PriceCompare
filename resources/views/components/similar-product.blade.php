<div class="mb-10">
<h1 class="mb-4 text-2xl font-medium">{{ __('messages.similar_product') }}</h1>
<div class="w-full flex border-l border-solid border-gray-400 lg:flex-row md:flex-col">
    @foreach($data as $item)
        <x-product :data="$item"></x-product>
    @endforeach
</div>