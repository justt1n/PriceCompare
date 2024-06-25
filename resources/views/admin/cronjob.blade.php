<x-app-layout>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

    <section class="pl-80 pr-64 py-16">
        <div class="w-full py-2 border rounded-xl bg-base-300 mb-4">
            <h1 class="text-3xl my-4 px-4">Cronjob Page</h1>
        </div>
        <div class="overflow-x-auto">

            <div class="flex justify-center">
                <input type="text" name="" id="picker" class="timepicker btn rounded-lg mr-10" value="{{ Cache::get('time_cron') ?? '00:00' }}">
                <button type="submit" id="time_run" class="btn bg-blue-100 hover:bg-blue-300">Confirm set time run cron</button>
            </div>

            <form action="{{ route('admin.runCronjob') }}" id="running_cronjob" method="POST">
                @csrf


                <table class="table mb-8 ">
                    <!-- head -->
                    <thead>
                        <tr class="text-lg">

                            <th>Link</th>
                            <th>Status</th>
                            <th>New</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- body -->
                        @foreach ($datas as $data)
                        <tr class="hover">
                            <td>{{ $data->site_name }}</td>
                            <td>
                                @if ($data->status == 1)
                                New
                                @elseif ($data->status == 2)
                                Old
                                @endif
                                <input type="hidden" name="status[{{$data->site_id}}]" value="{{$data->status}}">
                            </td>
                            <td>
                                <div class="form-control">
                                    <label class="cursor-pointer label">
                                        <input type="checkbox" class="checkbox" name="new[{{$data->site_id}}]"
                                            {{ $data->new == 1 ? 'checked' : '' }}
                                            {{$data->status == 1 ? 'hidden' : false }}>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="form-control">
                                    <label class="cursor-pointer label">
                                        <input type="checkbox" class="checkbox" name="update[{{$data->site_id}}]"
                                            {{ ($data->update == 1 ? 'checked' : '')}}
                                            {{$data->status == 1 ? 'hidden' : false }}>
                                    </label>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
                <button type="submit" id="cronjob_run" class="btn bg-blue-300 hover:bg-red-500">Run Cronjob</button>

            </form>
            <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
        </div>

    </section>
</x-app-layout>
<script>
$(document).ready(function() {
    $("#cronjob_run").click(function(event) {
        event.preventDefault();
        Swal.fire({
            title: "Are you sure?",
            text: "Are you sure you want to update cronjob?",
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, update it!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    title: "Update Cronjob has been saved",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    position: "top-end",
                });

                setTimeout(function() {
                    $("#running_cronjob").submit();
                }, 2000);

                }
            });
        });

        $('.timepicker').timepicker({
            timeFormat: 'HH:mm',
            interval: 01,
            minTime: '00:00',
            maxTime: '23:59',
            startTime: '24:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true,
            minMinutes : 0,
            maxMinutes : 59,
        });

        $('#time_run').click(function(event) {
            var time = $('.timepicker').val();
            event.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to setup time cron?",
                icon: "success",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, set it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('admin.setTimeRun') }}",
                        type: "POST",
                        data: {
                            "time": time
                        },
                        headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        success: function(response) {
                            Swal.fire({
                                icon: "success",
                                title: 'Change time cron successfully!',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                            });
                            timeOut = setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: "error",
                                title: 'Change time cron failed!',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                            });
                        }
                    });
                }
            });
        });
    });

</script>
