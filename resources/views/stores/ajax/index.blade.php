<td>
    <input type="checkbox" id="cron_order_{{ $store->id }}"
        value="{{ $store->name }}" onclick="cronOrder({{ $store->id }}, this)"
        {{ $store->cron ? 'checked' : '' }}>
</td>
<td>
    <a href="{{ route('storeproducts.show', $store->id) }}" target="_blank">
        {{ $store->id }}
    </a>
</td>
<td>
    {{ $store->sup_store_id }}
    {!! $store->meta && $store->meta->where('key', 'access_token')->first()?->value
    ? '<button class="btn btn-sm"><i class="fa fa-check" style="color: green;"></i></button>'
    : '<button class="btn btn-primary btn-sm" onclick="syncStoreSupover(' .
    $store->id .
    ')"><i class="fa fa-cloud-download"></i></button>' !!}
</td>
<td id="name_store_{{ $store->id }}">
    {{ $store->name }}
</td>
<td>
    {{ $store->keyword }}
</td>
<td>
    {{ $store->watermark }}
</td>
<td>
    <a href="{{ route('flashdeals.store', $store->id) }}" target="_blank"
        style="{{ ceil(getAllSkusTiktok($store->id) / 10000) ? 'color:red' : '' }}">
        show
        ({{ ceil(getAllSkusTiktok($store->id) / 10000) }})
    </a>
</td>
<td>
    {{ $store->name_flashdeal }}
</td>
<td>
    {{ getUsernameById($store->user_id) }}
</td>
<td>
    {{ getUsernameById($store->staff_id) }}
</td>
<td>
    <select class="form-control btn {{ $store->status ? 'btn-success' : 'btn-danger' }}" id="status_edit" name="status_edit" onchange="changeStatus({{ $store->id }}, this)">
        <option value="0" {{ $store->status == 0 ? 'selected' : '' }} class="btn-danger">Inactive</option>
        <option value="1" {{ $store->status == 1 ? 'selected' : '' }}>Active</option>
    </select>
</td>
<td>
    <div onclick="edit_store('{{ $store->id }}')">
        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
            <i class="fa fa-edit"></i>
        </button>
    </div>

    <button onclick="syncName('{{ $store->id }}')" id="btn_syncName_store_{{ $store->id }}"
        class="btn btn-success-jade btn-rounded btn-sm mt-3 border-0" style="color: black">
        <i class="fas fa-sync-alt"></i>
    </button><br>
</td>