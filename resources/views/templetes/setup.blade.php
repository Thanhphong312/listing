<div class="element-box">
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-7">
                        <h5 class="mb-3">Templates</h5>
                        <div class="border rounded p-3" style="height: 400px; overflow-y: auto;">
                            <ul class="list-group list-group-flush" id="templateList">
                                @foreach ($templates as $template)
                                    <li class="list-group-item template-item"
                                        onclick="selectTemplate({{ $template->id }}, this)"
                                        data-template-id="{{ $template->id }}" style="cursor: pointer;">
                                        {{ $template->id }}: <span class="fw-semibold">{{ $template->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-5">
                        <h5 class="mb-3">Users</h5>
                        <div class="border rounded p-3" id="body_showuser" style="height: 400px; overflow-y: auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let selectedTemplateId = 0;

    $(document).ready(function() {
        if (selectedTemplateId == 0) {
            $("#body_showuser").html('<div class="text-center p-4 text-muted">Please choose template</div>');
        } else {
            showListUser(selectedTemplateId);
        }
    });

    function showListUser(id) {
        $.ajax({
            url: './templates/choose-user/' + id,
            method: 'GET',
            success: function(response) {
                $("#body_showuser").html(response);
            },
            error: function(response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }

    function selectTemplate(templateId, element) {
        // console.log("Template ID selected:", templateId);
        showListUser(templateId);

        // Remove selected class from all templates
        document.querySelectorAll('.template-item').forEach(item => {
            item.classList.remove('template-selected');
        });

        // Add selected class to clicked template
        element.classList.add('template-selected');

        selectedTemplateId = templateId;

        // Update all checkboxes based on the selected template
        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            const userId = checkbox.dataset.userId;
            // Call your checkaccept function and update checkbox state
            checkbox.checked = checkaccept_setup(userId, templateId);
        });
    }
</script>
<style>
    .template-selected {
        background-color: #e9ecef !important;
    }
</style>
