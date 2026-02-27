<x-app-layout>
<div class="container mt-4">

    {{-- SweetAlert Success --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif

    {{-- SweetAlert Errors --}}
    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`
        });
    </script>
    @endif

    {{-- Upload Card --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-gradient bg-primary text-white fw-bold">
            Upload Image
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('image.upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="image_name" class="form-control form-control-lg"
                            placeholder="Enter Image Name" required>
                    </div>
                    <div class="col-md-5">
                        <input type="file" name="image" class="form-control form-control-lg" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Image Table --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-dark text-white fw-bold">
            Your Uploaded Images
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table id="imageTable" class="table table-hover table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Short URL</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($images as $key => $image)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $image->image_name }}</td>
                            <td>
                                <a href="{{ url('/s/'.$image->short_code) }}" target="_blank" class="text-decoration-none">
                                    {{ url('/s/'.$image->short_code) }}
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ url('/s/'.$image->short_code) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-primary">
                                   Open
                                </a>
                                <button class="btn btn-sm btn-secondary"
                                    onclick="copyLink('{{ url('/s/'.$image->short_code) }}')">
                                    Copy
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<script>
window.addEventListener('load', function() {
    if (typeof $ !== 'undefined') {
        $('#imageTable').DataTable({
            pageLength: 10
        });
    } else {
        console.error('jQuery not loaded');
    }
});

function copyLink(link) {
    navigator.clipboard.writeText(link);
    Swal.fire({
        icon: 'success',
        title: 'Copied!',
        text: 'Short URL copied to clipboard.',
        timer: 1500,
        showConfirmButton: false
    });
}
</script>

</x-app-layout>