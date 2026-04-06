<x-app-layout>

    <div class="container py-4">

        <script>
            window.flashMessages = {
                success: @json(session('success')),
                error: @json(session('error')),
                validationErrors: @json($errors -> all())
            };
        </script>
        <div class="container py-5">

            <h3 class="text-center mb-4">Image Merger</h3>

            <div class="card">

                <form id="mergeForm" enctype="multipart/form-data">
                    @csrf
                    <!-- MODE SELECT -->
                    <div class="mb-3">
                        <label>Merge Direction</label>
                        <select name="mode" class="form-select">
                            <option value="vertical">Vertical (Top → Bottom)</option>
                            <option value="horizontal">Horizontal (Left → Right)</option>
                        </select>
                        <div id="dynamicOptions" class="mt-3"></div>
                    </div>

                    <!-- IMAGES -->
                    <!-- <div id="imageContainer">
                    <div class="upload-box d-flex gap-2"> -->
                    <!-- <input type="file" name="images[]" class="form-control" required> -->
                    <!-- <input type="text" name="image_name" placeholder="Enter Image Name" required>

                        <button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button>
                    </div>
                </div> -->
                    <!-- <div class="mb-3">
                        <label>Image Name</label>
                        <input type="text" name="image_name" class="form-control" required>
                    </div> -->

                    <div id="imageContainer">
                        <div class="upload-box d-flex gap-2">
                            <input type="file" name="images[]" class="form-control" required>
                            <button type="button" class="btn btn-danger remove-btn">Remove</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary mt-2" onclick="addRow()">+ Add Image</button>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </div>
                </form>

            </div>

            <!-- OUTPUT -->
            <!-- <div class="text-center mt-5">
                <h5>Final Output 5</h5>
                    </div>
                <p id="shortUrl"></p>
                <img id="finalImage" class="preview-img">
            </div> -->

            <div class="text-center mt-3">
                <button id="downloadBtn" class="btn btn-success" style="display:none;">
                    Download 44
                </button>
            </div>
            <div id="loader" style="
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.6);
    z-index:9999;
    color:white;
    font-size:24px;
    justify-content:center;
    align-items:center;
">
                Processing...
            </div>
            <div class="modal fade" id="resultModal">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content text-center p-3">

                        <h5>Final Output 4</h5>

                        <img id="modalImage" style="max-width:100%; border-radius:10px;">

                        <!-- <button id="modalDownload" class="btn btn-success mt-3">
                            Download 33
                        </button> -->
                        <!-- <input type="text" id="renameInput" class="form-control mb-2" placeholder="Enter Image Name"> -->
                        <input type="text" id="renameInput" name="image_name" class="form-control mb-2" placeholder="Enter Image Name">
                        
                        <button id="modalDownload" class="btn btn-success mt-2">
                            Download
                        </button>

                        <button id="saveBtn" class="btn btn-primary mt-2">
                            Create Short URL
                        </button>

                    </div>
                </div>
            </div>
        </div>


        <!-- YOUR JS -->
        <!-- <script src="{{ asset('js/app.js') }}"></script> -->

        {{-- Upload Card --}}
        <!-- <div class="card border-0 shadow-sm mb-5 rounded-4">
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
        </div> -->

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
                                <th>Count</th>
                                <th>Date</th>
                                <!-- <th class="text-center">Action</th> -->
                            </tr>
                        </thead>

                        <!-- <tbody>
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

                                <td>{{ $image->click_count }}</td>

                                {{-- Date --}}
                                <td class="text-muted small">
                                    {{ $image->created_at->format('d M Y') }}
                                    <br>
                                    {{ $image->created_at->format('h:i A') }}
                                </td> -->

                                {{-- Action --}}
                                <!-- <td class="text-center">
                                    <a href="{{ url('/s/'.$image->short_code) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-dark rounded-3">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </td> -->

                            <!-- </tr>
                            @endforeach
                        </tbody> -->
                    </table>
                </div>

            </div>
        </div>

    </div>

<script>
    window.appUrl = "{{ url('/') }}";
</script>


</x-app-layout>