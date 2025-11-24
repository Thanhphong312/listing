@extends('layouts.app')

@section('page-title', __('Crawl'))
@section('page-heading', __('Crawl'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Crawl')
</li>
@stop
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">

<style>
    .thumbnail {
        width: 90%;
        /* chiếm 90% width của cột */
        height: auto;
        /* tự động theo tỉ lệ */
        object-fit: cover;
        border: 2px solid transparent;
        cursor: pointer;
        transition: border 0.2s;
    }

    .thumbnail.selected {
        border-color: green;
    }

    .description-scroll {
        max-height: 40%;
        /* chiều cao tối đa */
        overflow-y: auto;
        /* scroll dọc khi quá cao */
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        white-space: pre-wrap;
        /* giữ xuống dòng nếu có */
    }

    .image-block {
        border: 1px solid #000;
        padding: 8px;
        border-radius: 5px;
    }
</style>





@section('content')
<div class="container">
    <div class="element-box">
        <div class="card">
            <div class="card-body">
                <h2>Etsy Product Crawler</h2>
                <div class="col-md p-3 d-flex justify-content-end">
                    <a type="button" class="btn btn-primary" href="{{ route('crawls.view') }}">
                        <i class="fas fa-eye"></i> View Saved Crawls
                    </a>
                </div>
                <div class="col-12 mt-2">
                    
                        <div class="form-group col-10">
                            <label for="name">Template</label>
                            <select class="form-control select2" id="template" name="template"
                                onchange="changetemplate()">
                                <option value="">template...</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">
                                        {{ $template->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="mb-3">
                    <label for="urlInput" class="form-label">Product URL (e.g.,
                        https://www.etsy.com/listing/4356657127/spooky-mama-ghost-face-hoodie-sweatshirt ) </label>
                    <input type="text" class="form-control" id="urlInput" placeholder="https://www.etsy.com/listing/..."
                        value="https://www.etsy.com/listing/4356657127/spooky-mama-ghost-face-hoodie-sweatshirt">
                </div>

                <div class="mb-3">
                    <label for="proxyInput" class="form-label">Proxy (optional, e.g.,
                        http://user:pass@host:port)</label>
                    <input type="text" class="form-control" id="proxyInput" placeholder="http://user:pass@host:port"
                        value="http://60ba176d09:LgQMPZcv@167.160.75.13:4444">
                </div>

                <button id="crawlBtn" class="btn btn-primary mb-3" disabled>Crawl</button>
                <button id="resetBtn" class="btn btn-secondary mb-3">Reset</button>

                <div id="loading" style="display:none;" class="text-center mb-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Loading...</p>
                </div>


                <div id="result" style="display:none;">
                    <h4>Product Info</h4>
                    <p><strong>Title:</strong> <span id="title"></span></p>
                    <p><strong>Listing ID:</strong> <span id="listing_id"></span></p>
                    <p><strong>Price:</strong> <span id="price"></span></p>
                    <p><strong>Description:</strong></p>
                    <p id="description" class="form-control description-scroll"></p>
                    <h5>Images</h5>
                    <div id="images" class="d-flex flex-wrap"></div>
                    <div class="col-12 mt-2">
                            <div class="card-header row col-12"
                                style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                                <!-- Form for uploading multiple designs -->
                                <div class="form-group col-6">
                                    <label for="designs">Upload Designs</label>
                                    <input type="file" class="form-control" name="designs[]" id="designs" multiple>
                                </div>

                                <!-- Preview selected files and images -->
                                <div class="m-3 col-12" id="file-preview">
                                    <p>No files selected yet.</p>
                                </div>
                            </div>
                        </div>

                    <button id="saveBtn" class="btn btn-success mt-3">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        let selectedFiles = [];

        document.addEventListener("DOMContentLoaded", () => {
            const crawlBtn = document.getElementById("crawlBtn");
            const urlInput = document.getElementById("urlInput");

            // Khi người dùng nhập URL thì enable/disable nút Crawl
            urlInput.addEventListener("input", () => {
                if (urlInput.value.trim() !== "") {
                    crawlBtn.disabled = false;
                } else {
                    crawlBtn.disabled = true;
                }
            });

            // Khi load xong trang, nếu có URL sẵn thì enable nút Crawl
            crawlBtn.disabled = urlInput.value.trim() === "";
        });
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("crawlBtn").disabled = false;
        });
        document.getElementById('crawlBtn').addEventListener('click', async (e) => {
            const btn = e.target;
            btn.disabled = true; // disable trong lúc crawl

            const url = document.getElementById('urlInput').value;
            const proxy = document.getElementById('proxyInput').value;

            if (!url) { alert('Please enter URL'); return; }

            const resultDiv = document.getElementById('result');
            const loadingDiv = document.getElementById('loading');

            resultDiv.style.display = 'none';
            loadingDiv.style.display = 'block';  // Hiện loading

            try {
                const res = await fetch('/api/etsy-crawler', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ url: url, proxy: proxy })
                });
                const data = await res.json();

                if (data.error) {
                    alert("Failed get product");
                    return;
                }

                // Hiển thị dữ liệu
                document.getElementById('title').innerText = data.title;
                document.getElementById('price').innerText = `${data.price} ${data.currency_code}`;
                document.getElementById('description').innerText = data.description ?? '';
                document.getElementById('listing_id').innerText = data.listing_id ?? '';



                // Xử lý hình ảnh
                const imagesDiv = document.getElementById('images');
                imagesDiv.innerHTML = '';

                if (data.images && data.images.length) {
                    const row = document.createElement('div');
                    row.className = 'row';


                    data.images.forEach(src => {
                        const col = document.createElement('div');
                        col.className = 'col-4 text-center mb-3 image-block';

                        const img = document.createElement('img');
                        img.src = src;
                        img.className = 'thumbnail mb-1';

                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.className = 'image-checkbox';
                        checkbox.value = src;

                        // Tạo container cho label + select
                        const typeWrapper = document.createElement('div');
                        typeWrapper.className = 'd-flex align-items-center gap-2 mt-1';

                        // Select box để chọn type
                        const typeLabel = document.createElement('label');
                        typeLabel.innerText = 'Type:';
                        typeLabel.className = 'd-block mt-1 fw-bold';
                        const select = document.createElement('select');
                        select.className = 'form-select form-select-sm mt-1 image-type';
                        select.innerHTML = `
                            <option value="1">Design</option>
                            <option value="2">Size Chart</option>
                        `;
                        typeWrapper.appendChild(typeLabel);
                        typeWrapper.appendChild(select);



                        col.addEventListener('click', (e) => {
                            // tránh khi click vào select box thì lại toggle checkbox
                            if (e.target.tagName.toLowerCase() === 'select') return;

                            checkbox.checked = !checkbox.checked;
                            img.classList.toggle('selected', checkbox.checked);
                        });

                        col.appendChild(img);
                        col.appendChild(document.createElement('br'));
                        col.appendChild(checkbox);
                        col.appendChild(typeWrapper);

                        row.appendChild(col);
                    });

                    imagesDiv.appendChild(row);
                }


                resultDiv.style.display = 'block';
            } catch (err) {
                alert('Failed to fetch data');
            } finally {
                loadingDiv.style.display = 'none';  // Ẩn loading sau khi xong
                btn.disabled = false; // enable lại sau khi xong

            }
        });
        document.getElementById('designs').addEventListener('change', function(event) {
            let filePreview = document.getElementById('file-preview');

            Array.from(event.target.files).forEach(function(file) {
                selectedFiles.push(file);
            });

            filePreview.innerHTML = '';

            if (selectedFiles.length > 0) {
                let row = document.createElement('div');
                row.classList.add('row'); // Create a Bootstrap row

                selectedFiles.forEach(function(file, index) {
                    let col = document.createElement('div');
                    col.classList.add('col-md-3', 'mb-3',
                        'position-relative'); // Column style with relative position for delete button

                    if (file.type.startsWith('image/')) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            let image = new Image();
                            image.src = e.target.result;
                            image.classList.add(
                                'img-fluid'); // Bootstrap class to make the image responsive
                            image.style.objectFit = 'contain'; // Cover the fixed size
                            image.style.backgroundColor = "#bdbdbd";
                            image.style.width = '200px';
                            image.style.height = '200px';

                            let deleteBtn = document.createElement('button');
                            deleteBtn.innerHTML = '<i class="fa fa-trash"></i>';
                            deleteBtn.classList.add('btn', 'btn-danger', 'position-absolute', 'top-0',
                                'end-0', 'm-1', 'p-1', 'btn-sm');
                            deleteBtn.style.borderRadius = '50%';
                            deleteBtn.style.padding = '5px';

                            deleteBtn.addEventListener('click', function() {
                                selectedFiles.splice(index,
                                    1); // Remove the file from the array
                                renderFilePreview(); // Re-render the preview
                            });

                            let filename = document.createElement('p');
                            filename.classList.add('text-center', 'mt-2'); // Center the filename text
                            filename.innerText = file.name;

                            col.appendChild(image);
                            col.appendChild(deleteBtn);
                            col.appendChild(filename);
                        };
                        reader.readAsDataURL(file);
                    }

                    row.appendChild(col); // Append the column to the row
                });

                filePreview.appendChild(row); // Append the row to the file-preview section
            } else {
                filePreview.innerHTML = '<p>No files selected yet.</p>';
            }
            console.log("selectedFiles");
            console.log(selectedFiles);
        });
        // Save button
        $('#saveBtn').click(function () {
            
            const formData = new FormData();

            const images = [];

            $('.image-checkbox:checked').each(function () {
                const type = $(this).parent().find('.image-type').val();
                images.push({
                    url: $(this).val(),
                    type: type
                });
            });

            // append một lần dưới dạng JSON mảng
            formData.append('images', JSON.stringify(images));

            const selectedtemplete = $("#template").val();
            const imageFiles = selectedFiles; // giả sử selectedFiles là mảng File

            // Tạo FormData

            // Append file ảnh
            imageFiles.forEach((file) => {
                formData.append('image_files[]', file);
            });

            // Append các field text
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('listing_id', $('#listing_id').text().trim());
            formData.append('title', $('#title').text().trim());
            formData.append('description', $('#description').text().trim());
            formData.append('price', $('#price').text().trim().split(" ")[0]);
            formData.append('selectedtemplete', selectedtemplete);

            // Vì images là mảng object => cần stringify

            // Gửi Ajax bằng FormData
            $.ajax({
                url: '/etsy-crawler/save-product',
                method: 'POST',
                data: formData,
                processData: false, // không xử lý FormData thành chuỗi
                contentType: false, // để trình duyệt tự set multipart/form-data
                success: function (res) {
                    alert(res.message);
                    console.log(res);
                },
                error: function (xhr) {
                    alert('Save error: ' + xhr.statusText);
                    console.error(xhr.responseText);
                }
            });

        });

        // Reset button
        document.getElementById('resetBtn').addEventListener('click', () => {
            // Ẩn product info
            document.getElementById('result').style.display = 'none';

            // Clear các field
            document.getElementById('title').innerText = '';
            document.getElementById('listing_id').innerText = '';
            document.getElementById('price').innerText = '';
            document.getElementById('description').innerText = '';
            document.getElementById('images').innerHTML = '';

            // Reset input URL (nếu muốn clear luôn)
            document.getElementById('urlInput').value = '';
            document.getElementById('proxyInput').value = '';

        });

    </script>

    @stop

@endsection