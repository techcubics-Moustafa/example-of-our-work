@if(!empty($single))
    <script>
        $("#{{ $file_name }}").spartanMultiImagePicker({
            fieldName: '{{ $file_name }}',
            maxCount: 1,
            rowHeight: 'auto',
            groupClassName: 'col-12',
            maxFileSize: '',
            placeholderImage: {
                image: '{{ $image }}',
                width: '100%'
                //height: '100px',
            },
            dropFileLabel: "{{ _trans('Drop Here') }}",
            onAddRow: function (index, file) {

            },
            onRenderedPreview: function (index) {

            },
            onRemoveRow: function (index) {

            },
            onExtensionErr: function (index, file) {
                toastr.error('{{_trans('Please only input png or jpg type file')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            onSizeErr: function (index, file) {
                toastr.error('{{_trans('File size too big')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });

    </script>
@endif

@if(!empty($multi))

<script>
    $("#{{ $file_name }}").spartanMultiImagePicker({
        fieldName: '{{ $file_name }}[]',
        maxCount: {{$count}},
        rowHeight: 'auto',
        groupClassName: 'col-6',
        maxFileSize: '',
        placeholderImage: {
            image: '{{ asset('assets/images/img/400x400/img2.jpg') }}',
            width: '100%',
        },
        dropFileLabel: "Drop Here",
        onAddRow: function (index, file) {

        },
        onRenderedPreview: function (index) {

        },
        onRemoveRow: function (index) {

        },
        onExtensionErr: function (index, file) {
            toastr.error('{{_trans('Please only input png or jpg type file')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onSizeErr: function (index, file) {
            toastr.error('{{_trans('File size too big')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    });
</script>
@endif


