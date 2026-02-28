<x-app-layout >
<div class="container py-4">

<script>
    window.flashMessages = {
        success: @json(session('success')),
        error: @json(session('error')),
        validationErrors: @json($errors->all())
    };
</script>

    {{-- Upload Card --}}
    <div class="card border-0 shadow-sm mb-5 rounded-4">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4 text-dark">
                <i class="bi bi-cloud-upload me-2"></i> Upload Image
            </h4>

            <form id="uploadForm" method="POST" action="{{ route('image.upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Image Name</label>
                        <input type="text"
                               name="image_name"
                               class="form-control form-control-lg rounded-3"
                               placeholder="Enter Image Name"
                               required>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Select File</label>
                        <input type="file"
                               name="image"
                               class="form-control form-control-lg rounded-3"
                               required>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit"
                                class="btn btn-dark btn-lg w-100 rounded-3">
                            Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <h4 class="fw-bold mb-4 text-dark">
                <i class="bi bi-images me-2"></i> Uploaded Images
            </h4>

            <div class="table-responsive">
                <table id="imageTable"
                       class="table align-middle table-hover">
                    <thead class="border-bottom">
                        <tr class="text-muted">
                            <th>#</th>
                            <th>Image</th>
                            <th>Short URL</th>
                            <th>Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($images as $key => $image)
                        <tr>

                            <td class="fw-semibold">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Image Name --}}
                            <td>
                                <a href="{{ asset('storage/'.$image->file_path) }}"
                                   target="_blank"
                                   class="text-dark text-decoration-none fw-semibold">
                                    {{ $image->image_name }}.jpeg
                                </a>
                            </td>

                            {{-- Short URL --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">

                                    <a href="{{ url('/s/'.$image->short_code) }}"
                                       target="_blank"
                                       class="text-primary small text-truncate"
                                       style="max-width:220px;">
                                        {{ url('/s/'.$image->short_code) }}
                                    </a>

                                    <button type="button"
                                            class="btn btn-sm btn-light border rounded-3"
                                            onclick="copyLink('{{ url('/s/'.$image->short_code) }}')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>

                                </div>
                            </td>

                            {{-- Date --}}
                            <td class="text-muted small">
                                {{ $image->created_at->format('d M Y') }}
                                <br>
                                {{ $image->created_at->format('h:i A') }}
                            </td>

                            {{-- Action --}}
                            <td class="text-center">
                                <a href="{{ url('/s/'.$image->short_code) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-dark rounded-3">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>



</x-app-layout>