<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="panel-body clearfix mt-3" id="shortlink">
                <table class="table table-striped table-borderless" id="data-table-default">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="checkboxorders" onclick="checkboxorders()">
                            </th>
                            <th style="min-width: 250px">
                                REMOTE ID
                            </th>
                            <th>NAME</th>
                            <th style="min-width: 150px">NUMBER SKU</th>
                        </tr>
                    </thead>
                    <tbody class="order-list">
                        @foreach($producttiktoks as $producttiktok)
                            <tr data-id="{{$producttiktok->id}}" id="ajaxorder_{{$producttiktok->id}}">
                                <td><input type="checkbox" id="checkboxorders" onclick="checkproduct()"></td>
                                <td>{{$producttiktok->remote_id}}</td>
                                <td>{{$producttiktok->title}}</td>
                                <td>{{($producttiktok->total_sku)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

