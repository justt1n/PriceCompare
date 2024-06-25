<style>
    .collapse-content h3{
        font-weight: 800;
        line-height:150%;
        margin-bottom:8px;
    }
    .collapse-content img{
        margin:16px auto;
    }
</style>
<div class="mb-10">
    <div id="info">
        <h1 class="mb-4 text-2xl font-medium">{{ __('messages.info_product') }}</h1>
        <details class="collapse bg-base-200 w-3/5">
            @foreach($dataAttribute as $key => $value)
            @if($key == 'titleDescription')
            <summary class="collapse-title text-xl font-medium w-full">{!! $value !!}
                <summary class="collapse-title btn btn-md my-1">
                    <div class="flex justify-center items-center h-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                        </svg>
                    </div>
                </summary>
            </summary>
            @endif
            @endforeach
            @if(empty($dataAttribute['titleDescription']) || $dataAttribute['titleDescription'] == "")
                <summary class="collapse-title text-xl font-medium w-full">Chưa cập nhật</summary>
            @endif
            <!-- ----------------------------------- -->
            @foreach($dataAttribute as $key => $value)
                @if($key == 'description' && !empty($value))
                <div class="collapse-content">
                    {!! $value !!}
                </div>
                @endif
            @endforeach
        </details>
    </div>
    <div class="my-8" id="attribute">
        <h1 class="mb-4 text-2xl font-medium">{{ __('messages.info_attribute') }}</h1>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <tbody class="w-full">
                    <!-- row 1 -->
                    @foreach($dataAttribute as $key => $value)
                    @if($key != ('description') && $key != ('titleDescription'))
                    <tr>
                        <th class="w-1/5">{{$key}}</th>
                        @if(!empty($value))
                        <td class="">{{$value}}</td>
                        @else
                        <td class="">Chưa cập nhật</td>
                        @endif
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
