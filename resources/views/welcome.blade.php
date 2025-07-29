<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Course</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2, h4 {
            color: #333;
        }

        input[type="text"], textarea, select {
            width: 100%;
            padding: 8px 12px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 5px;
            margin-right: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .remove-btn {
            background-color: #dc3545;
        }

        .remove-btn:hover {
            background-color: #ed1e33ff;
        }

        .module {
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            border-radius: 6px;
            background-color: #f1f1f1;
        }

        .content {
            margin-left: 15px;
            margin-bottom: 10px;
            background-color: #fff;
            padding: 10px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Course</h2>
        <form id="courseForm">
            <input type="text" name="title" placeholder="Course Title" required><br>
            <textarea name="description" placeholder="Course Description"></textarea><br>
            <input type="text" name="category" placeholder="Category"><br>

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
                    <input type="text" name="modules[${moduleCount}][title]" placeholder="Module Title" required><br>
                    <div class="contentsContainer"></div>
                    <button type="button" class="addContent">Add Content</button>
                    <button type="button" class="removeModule remove-btn">Remove Module</button>
                </div>
            `;
            $('#modulesContainer').append(moduleHTML);
            moduleCount++;
        });

        // Add content inside a module
        $(document).on('click', '.addContent', function () {
            let moduleDiv = $(this).closest('.module');
            let index = moduleDiv.data('index');
            let contentCount = moduleDiv.find('.content').length;

            let contentHTML = `
                <div class="content">
                    <select name="modules[${index}][contents][${contentCount}][type]">
                        <option value="text">Text</option>
                        <option value="image">Image URL</option>
                        <option value="video">Video URL</option>
                        <option value="link">Link</option>
                    </select>
                    <input type="text" name="modules[${index}][contents][${contentCount}][value]" placeholder="Content">
                    <input type="text" name="modules[${index}][vedio_url][${contentCount}][value]" placeholder="Vedio URL">
                    <input type="text" name="modules[${index}][vedio_length][${contentCount}][value]" placeholder="Vedio Length">
                    <button type="button" class="removeContent remove-btn">Remove Content</button>
                </div>
            `;
            moduleDiv.find('.contentsContainer').append(contentHTML);
        });

        // Remove module
        $(document).on('click', '.removeModule', function () {
            $(this).closest('.module').remove();
        });

        // Remove content
        $(document).on('click', '.removeContent', function () {
            $(this).closest('.content').remove();
        });

        // Submit form
        $('#courseForm').submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "/courses", 
                method: "POST",
                data: formData,
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
