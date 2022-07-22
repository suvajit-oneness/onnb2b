@extends('admin.layouts.app')

@section('page', 'Secondary Order')

@section('content')
<section>
    @if (request()->input('store'))
    <p class="text-muted">{{request()->input('store_name')}}</p>
    @endif
    <div class="search__filter">
        <div class="row align-items-center justify-content-between">
        <div class="col">
        </div>
        <div class="col-auto">
            <form action="{{ route('admin.order.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <input type="search" name="term" class="form-control" placeholder="Search here.." id="term" value="{{app('request')->input('term')}}" autocomplete="off">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-danger btn-sm">Search Order</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.order.index',['export_all'=>'true']) }}"  class="btn btn-outline-danger btn-sm float-right"><i class="fa fa-cloud-download"></i> CSV Export</a></div>
        </div>
    </div>

    <div class="filter">
        <div class="row align-items-center justify-content-between">
        <div class="col">
        </div>
        <div class="col-auto">
            <p>{{$data->count()}} Items</p>
        </div>
        </div>
    </div>

    <table class="table" id="example5">
        <thead>
        <tr>
            <th>SL No</th>
            <th>Order No</th>
            <th>Store Name</th>
			<th>ASE</th>
			<th>ASE Contact</th>
            <th>Order Type</th>
            <th>Order time</th>
            <th>Amount</th>


        </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)

            <tr>
                <td>
                   {{ $index+1 }}
                </td>
                <td>
                    <p class="small text-dark mb-1">{{$item->order_no}}</p>
                    <p class="small text-dark mb-1">{{$item->fname.' '.$item->lname}}</p>
                    <p class="small text-muted mb-0">{{$item->email.'  '.$item->mobile}}</p>
                    <div class="row__action">
                        <a href="{{ route('admin.order.view', $item->id) }}">View</a>
                    </div>
                </td>
                <td>
                    <p class="small text-muted mb-1"> {{$item->stores ? $item->stores->store_name : ''}}</p>
                </td>
				<td>
                    <p class="small text-muted mb-1"> {{$item->users ? $item->users->name : ''}}</p>
                </td>
				<td>
                    <p class="small text-muted mb-1"> {{$item->users ? $item->users->mobile : ''}}</p>
                </td>
                <td>
                    <p class="small text-muted mb-1"> {{$item->order_type}}</p>
                </td>
                <td>
                    <p class="small">{{date('j M Y g:i A', strtotime($item->created_at))}}</p>
                </td>

                <td>
                    <p class="small text-muted mb-1">Rs {{$item->final_amount}}</p>
                </td>



            </tr>
            @empty
            <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        {{ $data->appends($_GET)->links() }}
    </div>
</section>
@endsection
@section('script')
<script>
    function htmlToCSV() {
        var data = [];
        var rows = document.querySelectorAll("#example5 tbody tr");
        @php
            if (!request()->input('page')) {
                $page = '1';
            } else {
                $page = request()->input('page');
            }
        @endphp

        var page = "{{ $page }}";

        data.push("SRNO,OrderNo,StoreName,ASE,ASEContact,OrderType,OrderTime,Amount");

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td");

            for (var j = 0; j < cols.length ; j++) {

                var text = cols[j].innerText.split(' ');
                var new_text = text.join('-');
                if (j == 3||j==5)
                    var comtext = new_text.replace(/\n/g, "-");
                else
                    var comtext = new_text.replace(/\n/g, " ");
                row.push(comtext);

            }
            data.push(row.join(","));

        }

        downloadCSVFile(data.join("\n"), 'Secondarysales.csv');
    }

    function downloadCSVFile(csv, filename) {
        var csv_file, download_link;

        csv_file = new Blob([csv], {
            type: "text/csv"
        });

        download_link = document.createElement("a");

        download_link.download = filename;

        download_link.href = window.URL.createObjectURL(csv_file);

        download_link.style.display = "none";

        document.body.appendChild(download_link);

        download_link.click();
    }


</script>
 @if (request()->input('export_all') == true)
                <script>
                    htmlToCSV();
                    window.location.href = "{{ route('admin.order.index') }}";
                </script>
            @endif
@endsection
