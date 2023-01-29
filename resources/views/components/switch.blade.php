@props([
    'model' => $model,
    'name' => $name,
    'scripts' => $scripts,
    'col' => $col,
])
<div class="col-md-{{ $col }}">
    <label class="form-label" for="switch-{{ $name }}">{{ _trans('Status') }}</label>
    <div class="media-body icon-state">
        <label class="switch">
            <input id="switch-{{ $name }}"
                   type="checkbox"
                   class="switch-{{ $name }}"
                @checked(old($name,$model)  == 1)
            >
            <span class="switch-state"></span>
        </label>
    </div>
    @error($name)
    <span class="text-danger">{!! $message !!} </span>
    @enderror
    <input id="{{ $name }}"
           type="hidden"
           name="{{ $name }}"
           class="form-control"
           value="{{ old($name,$model) }}"
    >
</div>


@push($scripts)
    <script>
        $(document).ready(function () {
            $('#switch-{{ $name }}').on('change', function () {
                if ($(this).prop("checked") === true) {
                    $("#{{ $name }}").val(1);
                } else {
                    $("#{{ $name }}").val(0);
                }
            })
            @if(old($name,$model) == 1)
            $("#{{ $name }}").val(1);
            @else
            $("#{{ $name }}").val(0);
            @endif
        })
    </script>

@endpush
