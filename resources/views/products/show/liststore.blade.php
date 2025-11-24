    <table class="table table-striped table-borderless">
        <head>
            <tr>
                <th ><input type="checkbox" onclick="check_all_store(this)"></th>
                <th >ID</th>
                <th >NAME</th>
                <th >STAFF</th>
                <th >SELLER</th>
            </tr>
        </head>

        <body>
            
                @foreach ($stores as $store)
                <tr>
                    <td>
                        <input type="checkbox" class="checkboxallstore" id="checkboxstore_{{$store->id}}" data-id="{{$store->id}}"><br>
                    </td>
                    <td>{{$store->id}}</td>
                    <td>{{$store->name}}</td>
                    <td>{{getUsernameById($store->staff_id)}}</td>
                    <td>{{getUsernameById($store->user_id)}}</td>
                </tr>
                @endforeach
                
            
        </body>
        <div class="btn btn-primary m-2" onclick="posttoStore()">Post to Store</div>
        <div class="btn btn-primary m-2" onclick="posttoStoreTiktok()">Post to Store & Tiktok</div>

    </table>