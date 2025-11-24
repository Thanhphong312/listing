<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Title</label>
                        <input class="form-control" type="text" name="title_add" id="title_add" value="">
                    </div>      
                    <div class="col-12 m-2 border-light">
                        <label for="list_image">Image</label>
                        <div class="input-group">
                            <input class="form-control" type="file" name="list_image[]" id="list_image">
                            <button class="btn btn-outline-secondary" type="button" id="addImageButton">+</button>
                        </div>
                        <div id="additionalImages"></div> <!-- Container for additional input files -->
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="des_add">Description</label>
                        <textarea class="form-control" type="text" name="des_add" id="des_add" cols="10" rows="5"></textarea>
                    </div>                    
                    <div class="col-12 m-2 border-light">
                        <input class="btn btn-primary"  type="button" name="submit" id="submit" onclick="add()" value="Submit"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('addImageButton').addEventListener('click', function() {
        const additionalImages = document.getElementById('additionalImages');
        
        const newInputGroup = document.createElement('div');
        newInputGroup.className = 'input-group mt-2';
        
        const newInput = document.createElement('input');
        newInput.className = 'form-control';
        newInput.type = 'file';
        newInput.name = 'list_image[]'; // Ensure this matches the name used in the JavaScript

        
        const removeButton = document.createElement('button');
        removeButton.className = 'btn btn-outline-secondary';
        removeButton.type = 'button';
        removeButton.innerText = '-';
        
        removeButton.addEventListener('click', function() {
            additionalImages.removeChild(newInputGroup);
        });
        
        newInputGroup.appendChild(newInput);
        newInputGroup.appendChild(removeButton);
        additionalImages.appendChild(newInputGroup);
    });

</script>