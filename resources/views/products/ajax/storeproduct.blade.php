<div class="row">
    <div class="col-2">
        ID
    </div>
    <div class="col-4">
       NAME
    </div>
    <div class="col-4">
        CREATED AT
    </div>
</div>
@foreach ($storeProducts as $storeProduct)
    <div class="row">
        <div class="col-2">
            {{$storeProduct->store_id}}
        </div>
        <div class="col-4">
            <a href="./storeproducts/show/{{$storeProduct->store_id}}" target="_blank" >
                {{getStoreNameById($storeProduct->store_id)}}
            </a>
        </div>
        <div class="col-4">
            {{$storeProduct->created_at}}
        </div>
    </div>
@endforeach