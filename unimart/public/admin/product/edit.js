$(document).ready(function() {
    $(".tags_select2").select2({
        tags: true,
        tokenSeparators: [',']
    })
});


//-------
Dropzone.options.dropzoneForm = {
    autoProcessQueue: false,
    acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",

    init: function() {
        var submitButton = document.querySelector("#submit-all");
        myDropzone = this;

        submitButton.addEventListener('click', function() {
            myDropzone.processQueue();
        });

        this.on("complete", function() {
            if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                var _this = this;
                _this.removeAllFiles();
            }
            load_images();
        });

    }

};

load_images();

function load_images() {
    $.ajax({
        url: "{{ route('admin.product.dropzone_fetch') }}",
        success: function(data) {
            $('#uploaded_image').html(data);
        }
    })
}

$(document).on('click', '.remove_image', function() {
    var name = $(this).attr('id');
    $.ajax({
        url: "{{ route('admin.product.dropzone_delete') }}",
        data: {
            name: name
        },
        success: function(data) {
            load_images();
        }
    })
});
//-----
