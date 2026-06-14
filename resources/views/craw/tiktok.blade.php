@extends('layouts.app')

@section('page-title', __('TikTok Crawl'))
@section('page-heading', __('TikTok Crawl'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('TikTok Crawl')
</li>
@stop

<style>
    .thumbnail {
        width: 90%;
        height: auto;
        object-fit: cover;
        border: 2px solid transparent;
        cursor: pointer;
        transition: border 0.2s;
    }
    .thumbnail.selected {
        border-color: #fe2c55;
    }
    .description-scroll {
        max-height: 40%;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        white-space: pre-wrap;
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
                <h2>TikTok Shop Product Crawler</h2>

                <div class="col-12 mt-2">
                    <div class="form-group col-10">
                        <label for="name">Template</label>
                        <select class="form-control select2" id="template" name="template">
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
                    <label for="urlInput" class="form-label">Product URL (e.g., https://www.tiktok.com/view/product/1732418854910595114)</label>
                    <input type="text" class="form-control" id="urlInput" placeholder="https://www.tiktok.com/view/product/...">
                </div>

                <div class="mb-3">
                    <label for="proxyInput" class="form-label">Proxy (khuyến nghị dùng US proxy, e.g., http://user:pass@host:port)</label>
                    <input type="text" class="form-control" id="proxyInput" placeholder="http://user:pass@host:port">
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="debugMode">
                    <label class="form-check-label" for="debugMode">Debug mode</label>
                </div>

                <button id="crawlBtn" class="btn btn-danger mb-3" disabled>Crawl (Server)</button>
                <button id="browserImportBtn" class="btn btn-warning mb-3">
                    <i class="fas fa-globe"></i> Import từ Browser
                </button>
                <button id="resetBtn" class="btn btn-secondary mb-3">Reset</button>

                <!-- Browser Import Modal -->
                <div class="modal fade" id="browserImportModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Import từ Browser</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-info"><i class="fas fa-info-circle"></i> Cách này <strong>100% work</strong> vì chạy trong browser thật của bạn, TikTok không detect được.</p>
                                <ol>
                                    <li>Mở trang TikTok sản phẩm trong <strong>tab mới</strong></li>
                                    <li>Nhấn <strong>F12</strong> → tab <strong>Console</strong></li>
                                    <li>Copy đoạn script bên dưới, paste vào Console và nhấn Enter</li>
                                </ol>
                                <div class="position-relative">
                                    <pre id="consoleScript" class="bg-dark text-light p-3 rounded" style="font-size:11px;max-height:200px;overflow-y:auto;white-space:pre-wrap;"></pre>
                                    <button class="btn btn-sm btn-light position-absolute" style="top:8px;right:8px;" onclick="copyScript()">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </div>
                                <div class="mt-3">
                                    <label>Hoặc paste JSON data tại đây (nếu script trả về lỗi CORS):</label>
                                    <textarea id="pasteJsonArea" class="form-control" rows="4" placeholder='{"title":"...","price":...,"images":[]}'></textarea>
                                    <button class="btn btn-primary mt-2" onclick="importFromPastedJson()">Import JSON</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="loading" style="display:none;" class="text-center mb-3">
                    <div class="spinner-border text-danger" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Loading...</p>
                </div>

                <div id="result" style="display:none;">
                    <h4>Product Info</h4>
                    <div class="mb-2"><strong>Title:</strong> <input type="text" id="title" class="form-control mt-1"></div>
                    <p><strong>Product ID:</strong> <span id="product_id"></span></p>
                    <p><strong>Price:</strong> <span id="price"></span></p>
                    <p><strong>Description:</strong></p>
                    <p id="description" class="form-control description-scroll"></p>

                    <h5>Images</h5>
                    <div id="images" class="d-flex flex-wrap"></div>

                    <div class="col-12 mt-2">
                        <div class="card-header row col-12" style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <div class="form-group col-6">
                                <label for="designs">Upload Designs</label>
                                <input type="file" class="form-control" name="designs[]" id="designs" multiple>
                            </div>
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

        urlInput.addEventListener("input", () => {
            crawlBtn.disabled = urlInput.value.trim() === "";
        });
    });

    document.getElementById('crawlBtn').addEventListener('click', async (e) => {
        const btn = e.target;
        btn.disabled = true;

        const url = document.getElementById('urlInput').value;
        const proxy = document.getElementById('proxyInput').value;

        if (!url) { alert('Please enter URL'); btn.disabled = false; return; }

        const resultDiv = document.getElementById('result');
        const loadingDiv = document.getElementById('loading');

        resultDiv.style.display = 'none';
        loadingDiv.style.display = 'block';

        try {
            const debug = document.getElementById('debugMode').checked;
            const res = await fetch('/api/tiktok-crawl', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ url, proxy, debug })
            });
            const data = await res.json();
            console.log('TikTok raw response:', data);

            if (debug) {
                alert(JSON.stringify(data, null, 2));
                return;
            }

            if (data.error) {
                alert(data.error);
                return;
            }

            renderProduct(data);
        } catch (err) {
            alert('Failed to fetch data: ' + err.message);
        } finally {
            loadingDiv.style.display = 'none';
            btn.disabled = false;
        }
    });

    document.getElementById('designs').addEventListener('change', function(event) {
        Array.from(event.target.files).forEach(file => selectedFiles.push(file));
        renderFilePreview();
    });

    function renderFilePreview() {
        const filePreview = document.getElementById('file-preview');
        filePreview.innerHTML = '';

        if (selectedFiles.length === 0) {
            filePreview.innerHTML = '<p>No files selected yet.</p>';
            return;
        }

        const row = document.createElement('div');
        row.classList.add('row');

        selectedFiles.forEach((file, index) => {
            const col = document.createElement('div');
            col.classList.add('col-md-3', 'mb-3', 'position-relative');

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const image = new Image();
                    image.src = e.target.result;
                    image.classList.add('img-fluid');
                    image.style.objectFit = 'contain';
                    image.style.backgroundColor = '#bdbdbd';
                    image.style.width = '200px';
                    image.style.height = '200px';

                    const deleteBtn = document.createElement('button');
                    deleteBtn.innerHTML = '<i class="fa fa-trash"></i>';
                    deleteBtn.classList.add('btn', 'btn-danger', 'position-absolute', 'top-0', 'end-0', 'm-1', 'p-1', 'btn-sm');
                    deleteBtn.style.borderRadius = '50%';
                    deleteBtn.addEventListener('click', function() {
                        selectedFiles.splice(index, 1);
                        renderFilePreview();
                    });

                    const filename = document.createElement('p');
                    filename.classList.add('text-center', 'mt-2');
                    filename.innerText = file.name;

                    col.appendChild(image);
                    col.appendChild(deleteBtn);
                    col.appendChild(filename);
                };
                reader.readAsDataURL(file);
            }

            row.appendChild(col);
        });

        filePreview.appendChild(row);
    }

    $('#saveBtn').click(function() {
        const formData = new FormData();

        const images = [];
        $('.image-checkbox:checked').each(function() {
            images.push({
                url: $(this).val(),
                type: $(this).parent().find('.image-type').val()
            });
        });

        formData.append('images', JSON.stringify(images));

        selectedFiles.forEach(file => formData.append('image_files[]', file));

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('product_id', $('#product_id').text().trim());
        formData.append('title', $('#title').val().trim());
        formData.append('description', $('#description').text().trim());
        formData.append('price', $('#price').text().trim().split(' ')[0] || '0');
        formData.append('selectedtemplete', $('#template').val());

        $.ajax({
            url: '/tiktok-crawl/save-product',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                alert(res.message);
            },
            error: function(xhr) {
                alert('Save error: ' + xhr.statusText);
                console.error(xhr.responseText);
            }
        });
    });

    // Browser Import
    const serverOrigin = '{{ url('/') }}';
    const browserScript = `(function(){
  const pid = location.pathname.match(/\\d{10,}/)?.[0] ?? '';

  // Helper: extract product từ object bất kỳ
  function findProduct(obj, depth=0){
    if(!obj || typeof obj!=='object' || depth>6) return null;
    if(obj.title && (obj.images||obj.skus||obj.price)) return obj;
    for(const k of Object.keys(obj)){
      const r = findProduct(obj[k], depth+1);
      if(r) return r;
    }
    return null;
  }

  function extractImages(p){
    const src = p.images ?? p.main_images ?? p.imageList ?? [];
    return src.map(i=>i.url??i.src??i.uri??(typeof i==='string'?i:null)).filter(Boolean);
  }

  function extractPrice(p){
    if(p.price && typeof p.price==='number') return p.price;
    if(p.price?.value) return p.price.value;
    if(p.priceInfo?.price) return p.priceInfo.price;
    if(p.skus?.[0]?.price?.salePrice) return p.skus[0].price.salePrice/100;
    if(p.skus?.[0]?.price?.originalPrice) return p.skus[0].price.originalPrice/100;
    if(p.minPrice) return p.minPrice;
    return null;
  }

  function buildOut(p){
    return {
      product_id: String(p.id ?? p.productId ?? p.product_id ?? pid),
      title: p.title ?? p.name ?? p.productName ?? '',
      price: extractPrice(p),
      description: p.description ?? p.desc ?? p.detail ?? '',
      images: extractImages(p)
    };
  }

  function send(out){
    fetch('${serverOrigin}/api/tiktok-crawl-import',{
      method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(out)
    }).then(r=>r.json()).then(function(d){
      if(d.redirect){ window.location.href=d.redirect; }
      else{ alert('✅ Done! Quay lại app để xem.'); }
    }).catch(function(){
      prompt('Copy JSON này rồi paste vào ô Import JSON:',JSON.stringify(out));
    });
  }

  // 1. __NEXT_DATA__
  const nd = document.getElementById('__NEXT_DATA__');
  if(nd){
    const d = JSON.parse(nd.textContent);
    const pp = d?.props?.pageProps ?? {};
    const p = pp.productDetail ?? pp.product ?? findProduct(pp);
    if(p){ send(buildOut(p)); return; }
  }

  // 2. Tìm trong window object
  const winKeys = ['__INITIAL_STATE__','__STORE__','__data__','__APP_DATA__','TIKTOK_SHOP_INITIAL_DATA','appContext'];
  for(const k of winKeys){
    if(window[k]){
      const p = findProduct(window[k]);
      if(p){ send(buildOut(p)); return; }
    }
  }

  // 3. Tìm trong tất cả script tags chứa productId
  const scripts = document.querySelectorAll('script:not([src])');
  for(const s of scripts){
    if(!s.textContent.includes(pid) && !s.textContent.includes('"title"')) continue;
    const m = s.textContent.match(/\\{[^{}]{200,}\\}/g);
    if(m){
      for(const chunk of m){
        try{
          const obj = JSON.parse(chunk);
          const p = findProduct(obj);
          if(p){ send(buildOut(p)); return; }
        }catch(e){}
      }
    }
  }

  // 4. TikTok Shop PDP DOM (shop.tiktok.com/us/pdp/...)
  {
    // Title
    const ogTitle = (document.querySelector('meta[property="og:title"]') || {content:''}).content;
    const h1Span = document.querySelector('h1 span[data-fmp="true"]') || document.querySelector('h1');
    const title = (h1Span ? h1Span.innerText.trim() : '') || ogTitle;

    // Price: TikTok splits price across 3 spans ($, integer, decimal)
    // Walk up from .H2-Semibold to find full price text
    let price = null;
    const h2el = document.querySelector('.H2-Semibold');
    if(h2el) {
      const container = h2el.parentElement && h2el.parentElement.parentElement;
      const txt = container ? (container.innerText || '').replace(/\\s+/g,'') : '';
      const m = txt.match(/([0-9]+(?:[.,][0-9]+)?)/);
      if(m) price = parseFloat(m[1].replace(',','.'));
    }
    if(!price) {
      const ogPrice = (document.querySelector('meta[property="product:price:amount"]') || {content:''}).content;
      if(ogPrice) price = parseFloat(ogPrice);
    }

    // Images: from slick slider
    const seen = {};
    const imgs = [];
    document.querySelectorAll('.slick-slide img').forEach(function(i){
      const s = i.src; if(s && !s.includes('data:') && !seen[s]){ seen[s]=1; imgs.push(s); }
    });
    if(!imgs.length){
      const og = document.querySelector('meta[property="og:image"]');
      if(og) imgs.push(og.content);
    }

    // Description
    const descEls = document.querySelectorAll('.SmallText1-Regular.text-color-UIText1.mb-8');
    const desc = Array.from(descEls).map(function(e){return e.innerText.trim();}).filter(Boolean).join('\\n');

    if(title){
      send({ product_id: pid, title: title, price: price, description: desc, images: imgs });
      return;
    }
  }

  // Debug: log tất cả window keys để tìm data
  const wKeys = Object.keys(window).filter(k=>typeof window[k]==='object'&&window[k]!==null);
  console.log('Window keys:', wKeys);
  alert('Không tìm thấy data. Mở Console và xem log "Window keys" để tìm key chứa product data, rồi báo lại.');
})();`;

    document.getElementById('consoleScript').textContent = browserScript;

    document.getElementById('browserImportBtn').addEventListener('click', () => {
        const urlInput = document.getElementById('urlInput').value.trim();
        if (urlInput) {
            window.open(urlInput, '_blank');
        }
        $('#browserImportModal').modal('show');
    });

    window.copyScript = function() {
        navigator.clipboard.writeText(browserScript).then(() => alert('Đã copy script!'));
    };

    window.importFromPastedJson = function() {
        try {
            const data = JSON.parse(document.getElementById('pasteJsonArea').value);
            renderProduct(data);
            $('#browserImportModal').modal('hide');
        } catch(e) {
            alert('JSON không hợp lệ: ' + e.message);
        }
    };

    // Route nhận data từ browser script
    document.addEventListener('tiktok-import', (e) => renderProduct(e.detail));

    function renderProduct(data) {
        document.getElementById('title').value = data.title ?? '';
        document.getElementById('product_id').innerText = data.product_id ?? '';
        document.getElementById('price').innerText = data.price ?? '';
        document.getElementById('description').innerText = data.description ?? '';

        const imagesDiv = document.getElementById('images');
        imagesDiv.innerHTML = '';
        if (data.images && data.images.length) {
            const row = document.createElement('div');
            row.className = 'row';
            data.images.forEach(src => {
                const col = document.createElement('div');
                col.className = 'col-4 text-center mb-3 image-block';
                const img = document.createElement('img');
                img.src = src; img.className = 'thumbnail mb-1';
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox'; checkbox.className = 'image-checkbox'; checkbox.value = src;
                const typeWrapper = document.createElement('div');
                typeWrapper.className = 'd-flex align-items-center gap-2 mt-1';
                const typeLabel = document.createElement('label');
                typeLabel.innerText = 'Type:'; typeLabel.className = 'd-block mt-1 fw-bold';
                const select = document.createElement('select');
                select.className = 'form-select form-select-sm mt-1 image-type';
                select.innerHTML = '<option value="1">Design</option><option value="2">Size Chart</option>';
                typeWrapper.appendChild(typeLabel); typeWrapper.appendChild(select);
                col.addEventListener('click', (e) => {
                    if (e.target.tagName.toLowerCase() === 'select') return;
                    checkbox.checked = !checkbox.checked;
                    img.classList.toggle('selected', checkbox.checked);
                });
                col.appendChild(img); col.appendChild(document.createElement('br'));
                col.appendChild(checkbox); col.appendChild(typeWrapper);
                row.appendChild(col);
            });
            imagesDiv.appendChild(row);
        }
        document.getElementById('result').style.display = 'block';
    }

    @if(!empty($importedProduct))
    renderProduct(@json($importedProduct));
    @endif

    document.getElementById('resetBtn').addEventListener('click', () => {
        document.getElementById('result').style.display = 'none';
        document.getElementById('title').value = '';
        document.getElementById('product_id').innerText = '';
        document.getElementById('price').innerText = '';
        document.getElementById('description').innerText = '';
        document.getElementById('images').innerHTML = '';
        document.getElementById('urlInput').value = '';
        document.getElementById('proxyInput').value = '';
        selectedFiles = [];
        renderFilePreview();
    });
</script>
@stop

@endsection
