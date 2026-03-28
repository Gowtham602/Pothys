<!DOCTYPE html>
<html>

<head>
    <title>Image Merger</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

    <div class="container py-5">

        <h3 class="text-center mb-4">Image Merger</h3>

        <div class="card">

            <form id="uploadForm" enctype="multipart/form-data">
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
                <div class="mb-3">
                        <label>Image Name</label>
                        <input type="text" name="image_name" class="form-control" required>
                    </div>

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
        <div class="text-center mt-5">
            <h5>Final Output 1</h5>
            <p id="shortUrl"></p>
            <img id="finalImage" class="preview-img">
        </div>

        <div class="text-center mt-3">
            <button id="downloadBtn" class="btn btn-success" style="display:none;">
                Download
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

                    <h5>Final Output 3 </h5>

                    <img id="modalImage" style="max-width:100%; border-radius:10px;">

                    <button id="modalDownload" class="btn btn-success mt-3">
                        Download
                    </button>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- YOUR JS -->
    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>