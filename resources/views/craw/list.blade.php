@extends('layouts.app')

@section('page-title', __('Saved Crawls'))
@section('page-heading', __('Saved Crawls'))


@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Saved Crawls')
    </li>
@stop

<style>
    .desc-scroll {
        max-height: 150px;        /* chiều cao tối đa của box */
        overflow-y: auto;         /* bật scroll dọc khi nội dung vượt */
        white-space: pre-wrap;    /* giữ xuống dòng */
        overflow-wrap: break-word;/* xuống dòng khi từ quá dài */
    }

</style>

@section('content')
<div class="container">
    <h2>Saved Products</h2>

    <div class="col-md p-3 d-flex">
        <a type="button" class="btn btn-primary" href="{{ route('etsy-crawler') }}">
            <i class="fas fa-arrow-left"></i> Back to Crawl
        </a>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <form method="GET" action="{{ route('crawls.view') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="listing_id" class="form-control"
                           placeholder="Search by Listing ID"
                           value="{{ request('listing_id') }}">
                </div>
                <div class="col-md-4">
                    <input type="text" name="title" class="form-control"
                           placeholder="Search by Title"
                           value="{{ request('title') }}">
                </div>
                <div class="col-md-4 d-flex">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    @if(request('listing_id') || request('title'))
                        <a href="{{ route('crawls.view') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Listing ID</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->listing_id }}</td>
                    <td>{{ $product->title }}</td>
                    <td>${{ $product->price }}</td>
                    <td style="max-width: 250px; width:250px;">
                        <div class="desc-scroll">
                        {{ $product->description }}
                        </div>
                    </td>
                    
                    <td>
                        @if($product->designs->count() > 0)
                            <img src="{{ $product->designs->first()->url }}" alt="thumbnail" width="80" class="border">
                            <button class="btn btn-sm btn-secondary view-images-btn" 
                                data-id="{{ $product->id }}">
                                View All Images
                            </button>
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>
                    <td>{{ $product->created_at }}</td>

                    <td>
                        <button class="btn btn-sm btn-primary" onclick="CreateProduc">
                            Create Product
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{ $products->links() }}
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="imagesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Product Images</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="imagesContainer" style="display: flex; flex-wrap: wrap; gap: 10px;">
        <!-- Images will be injected here -->
      </div>
    </div>
  </div>
</div>


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('imagesModal');
    const container = document.getElementById('imagesContainer');
    const modal = new bootstrap.Modal(modalEl);

    document.querySelectorAll('.view-images-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const productId = this.dataset.id;
            if (!productId) {
                console.error("No productId found for this button!");
                return;
            }

            container.innerHTML = '<p>Loading images...</p>';
            modal.show();

            try {
                let res = await fetch(`/api/crawls/${productId}/images`);
                if (!res.ok) throw new Error("Failed to fetch images");
                let data = await res.json();

                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = '<p>No images available</p>';
                } else {
                    data.forEach(img => {
                        let wrapper = document.createElement('div');
                        wrapper.style.flex = "0 0 30%";
                        wrapper.innerHTML = `
                            <img src="${img.url}" alt="design" class="img-fluid border rounded">
                            <div class="mt-1">
                                <span class="badge ${img.type == 1 ? 'bg-primary' : 'bg-success'}">
                                    ${img.type == 1 ? 'Design' : 'Size Chart'}
                                </span>
                            </div>
                        `;
                        container.appendChild(wrapper);
                    });
                }
            } catch (err) {
                container.innerHTML = `<p class="text-danger">${err.message}</p>`;
            }
        });
    });
});

</script>
@stop

@endsection

