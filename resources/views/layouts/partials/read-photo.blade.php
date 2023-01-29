
@push('scripts')
    <script>
        $(".{{$inputName}}").change(function () {

            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.{{$inputName}}-preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            }

        });

        $("#{{$inputName}}").change(function () {

            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.{{$inputName}}-preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            }

        });

    </script>
@endpush
