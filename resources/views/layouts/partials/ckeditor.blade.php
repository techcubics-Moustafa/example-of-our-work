@push('scripts')
    <script src="{{asset('assets')}}/ckeditor/ckeditor.js"></script>
    <script src="{{asset('assets')}}/ckeditor/adapters/jquery.js"></script>
    <script src="{{asset('assets')}}/ckeditor/lang/{{ session('local') }}.js"></script>
    <script>
        @if (!empty($inputClass))
        $('.{{$inputClass}}').ckeditor({
            contentsLangDirection: '{{ session('direction') }}',
            language: '{{ session('local') }}'
        });
        @endif
        @if (!empty($inputId))
        $('#{{ $inputId }}').ckeditor({
            contentsLangDirection: '{{ session('direction') }}',
            language: '{{ session('local') }}'
        });
        @endif

    </script>
@endpush
