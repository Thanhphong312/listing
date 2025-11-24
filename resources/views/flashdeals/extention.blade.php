<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center my-5">
        <h3 class="text-primary">--------------FLASHDEAL--------------</h3>
        <div class="mt-4 row " style="width:500px">
            @foreach ($stores as $store)
                <div class="input-group mb-3 col-4">
                    <input type="text" class="form-control" value="{{$store->id}}" aria-label="Store Name">
                    <button class="btn btn-primary" type="button" onclick="createfld('{{$store->id}}','{{$store->name_flashdeal}}')">Create FLD</button>
                    <input type="text" class="form-control" id="name_{{$store->id}}" value="{{$store->name_flashdeal}}">
                    <input type="text" class="form-control" id="message_{{$store->id}}">
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function createfld(id){
        var formData = new FormData()
        formData.append('csrf', '{{ csrf_token() }}');
        formData.append('name_add', $("#name_"+id).val())
        formData.append('datefrom','{{now()}}')
        formData.append('dateto','{{now()->addDays(3)}}')
        formData.append('store_add',id)
        formData.append('level_add','VARIATION')
        formData.append('activity_add', "FLASHDEAL")

        $.ajax({
            url: './flashdeals/add',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Handle the response, e.g., reload the page
                if (JSON.parse(response).message) {
                    location.reload();
                }
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                const res = response;
                alert(JSON.parse(res.responseText).message);

                // alert(JSON.parse(response).message);
            }
        });
    }
</script>