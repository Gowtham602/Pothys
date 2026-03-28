<x-app-layout>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<div class="container py-4">

<table id="clickTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image Name</th>
            <th>IP</th>
            <th>Browser</th>
            <th>Device</th>
            <th>Country</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach($imageClicks as $click)
        <tr>
            <td>{{ $click->id }}</td>
            <td>{{ $click->image_name }}</td>
            <td>{{ $click->ip_address }}</td>
            <td>{{ $click->browser }}</td>
            <td>{{ $click->device_type }}</td>
            <td>{{ $click->country ?? 'Unknown' }}</td>
            <td>{{ $click->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>

<script>
$(document).ready(function () {
    $('#clickTable').DataTable({
        pageLength: 10,
        order: [[6, "asc"]],
        responsive: true
    });

    $('.dataTables_filter input').attr("placeholder", "Search...");
});
</script>

</x-app-layout>