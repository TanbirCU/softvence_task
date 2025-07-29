<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Course</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Dropzone -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
    @include('layouts.css')
</head>
<body>
<div class="container">
    <h2>Create Course</h2>
    <form id="courseForm" enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" placeholder="Course Title" required>
        <input type="text" name="category" placeholder="Category" required>
        <textarea name="description" id="courseDescription" placeholder="Course Description"></textarea>
        <script>CKEDITOR.replace('courseDescription');</script>

        <div id="modulesContainer"></div>
        <button type="button" id="addModule">Add Module</button><br><br>

        <button type="submit">Submit Course</button>
    </form>
</div>

<script>
    let moduleCount = 0;

    $('#addModule').click(function () {
        let moduleHTML = `
            <div class="module" data-index="${moduleCount}">
                <h4>Module ${moduleCount + 1}</h4>
                <input type="text" name="modules[${moduleCount}][title]" placeholder="Module Title" required>
                <div class="contentsContainer"></div>
                <button type="button" class="addContent">Add Content</button>
                <button type="button" class="removeModule remove-btn">Remove Module</button>
            </div>
        `;
        $('#modulesContainer').append(moduleHTML);
        moduleCount++;
    });

    $(document).on('click', '.removeModule', function () {
        $(this).closest('.module').remove();
    });

    $(document).on('click', '.addContent', function () {
        let moduleDiv = $(this).closest('.module');
        let moduleIndex = moduleDiv.data('index');
        let contentCount = moduleDiv.find('.content').length;

        let contentHTML = `
            <div class="content" data-content-index="${contentCount}">
                <select name="modules[${moduleIndex}][contents][${contentCount}][type]" required>
                    <option value="">Select Type</option>
                    <option value="text">Text</option>
                    <option value="video">Video</option>
                    <option value="image">Image</option>
                    <option value="link">Link</option>
                </select>
                <input type="text" name="modules[${moduleIndex}][contents][${contentCount}][title]" placeholder="Content Title" required>
                <textarea name="modules[${moduleIndex}][contents][${contentCount}][description]" placeholder="Content Description"></textarea>
                <input type="url" name="modules[${moduleIndex}][contents][${contentCount}][video_url]" placeholder="Video URL">
                <input type="url" name="modules[${moduleIndex}][contents][${contentCount}][external_link]" placeholder="External Link">
                <div class="dropzone dz-${moduleIndex}-${contentCount}" data-input-name="modules[${moduleIndex}][contents][${contentCount}][image]"></div>
                <button type="button" class="removeContent remove-btn">Remove Content</button>
            </div>
        `;

        moduleDiv.find('.contentsContainer').append(contentHTML);

        CKEDITOR.replace(`modules[${moduleIndex}][contents][${contentCount}][description]`);

        // Initialize Dropzone
        let dzSelector = `.dz-${moduleIndex}-${contentCount}`;
        new Dropzone(dzSelector, {
            url: "/upload/image",
            paramName: "file",
            maxFiles: 1,
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            success: function (file, response) {
                $('<input>').attr({
                    type: 'hidden',
                    name: $(dzSelector).data('input-name'),
                    value: response.path || response.url
                }).appendTo($(dzSelector).closest('.content'));
            }
        });
    });

    $(document).on('click', '.removeContent', function () {
        $(this).closest('.content').remove();
    });

    $('#courseForm').submit(function (e) {
        e.preventDefault();
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }

        let formData = new FormData(this);

        $.ajax({
            url: "/courses",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                toastr.success("Course Created!");
                $('#courseForm')[0].reset();
                $('#modulesContainer').html('');
                moduleCount = 0;
            },
            error: function (err) {
                toastr.error("Something went wrong!");
                console.log(err);
            }
        });
    });
</script>
</body>
</html>
