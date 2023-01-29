<div class="repeater-default">
    @if (count($data) > 0)
        <div data-repeater-list="social_media">
            @foreach($data  as $key => $value)
                <div data-repeater-item="" style="">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label" for="name">{{ _trans('Social name') }}</label>
                            <input type="text"
                                   name="social_media[{{ $key }}][name]"
                                   id="name"
                                   value="{{ $value['name'] }}"
                                   placeholder="{{ _trans('Enter social name') }}"
                                   class="form-control @error('social_media.'.$key.'.name') is-invalid @enderror"
                            />
                            @error('social_media.'.$key.'.name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror

                        </div>

                        <div class="col-md-8">
                            <label class="form-label" for="link">{{ _trans('Link') }}</label>
                            <input type="url"
                                   name="social_media[{{ $key }}][link]"
                                   value="{{ $value['link'] }}"
                                   class="form-control @error('social_media.'.$key.'.link') is-invalid @enderror"
                                   placeholder="{{ _trans('Enter link') }}"
                                   id="link"
                            />
                            @error('social_media.'.$key.'.link')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>

                        <div class="col-md-1">
                            <div class="form-group col-sm-12 col-md-2 text-center mt-2">
                                <br>
                                <button type="button" id="deleteRepeater" class="btn btn-danger" data-repeater-delete="">
                                    {{--<i class="fas fa-trash-alt"></i>--}} {{ _trans('Delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            @endforeach
        </div>
    @else
        <div data-repeater-list="social_media">
            <div data-repeater-item="" style="">
                <div class="row">

                    <div class="col-md-3">
                        <label class="form-label" for="name">{{ _trans('Social name') }}</label>
                        <input type="text"
                               name="name"
                               id="name"
                               placeholder="{{ _trans('Enter social name') }}"
                               class="form-control"
                        >
                    </div>

                    <div class="col-md-8">
                        <label class="form-label" for="link">{{ _trans('Link') }}</label>
                        <input type="url"
                               name="link"
                               id="link"
                               placeholder="{{ _trans('Enter link') }}"
                               class="form-control"
                        >
                    </div>

                    <div class="col-md-1">
                        <div class="form-group col-sm-12 col-md-2 text-center mt-2">
                            <br>
                            <button type="button" id="deleteRepeater" class="btn btn-danger" data-repeater-delete="">
                                {{--<i class="fas fa-trash-alt"></i>--}} {{ _trans('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    @endif
    <div class="form-group overflow-hidden">
        <div class="col-12">
            <button type="button" id="addRepeater" data-repeater-create="" class="btn btn-primary">
                {{--<i class="far fa-plus-square"></i> --}}{{ _trans('Add') }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var limit = 1;
        $(document).ready(function () {
            limit = {{ count($data) == 0 ? 1 : count($data)  }};
            limitRepeater();
        })
        $(document).on('click', '#addRepeater', function () {
            limit++;
            if (limit > 6) {
                $('#addRepeater').attr('disabled', true)
            }
            setTimeout(function () {
                $('.js-example-basic-single').select2();
            }, 100);
        })
        $(document).on('click', '#deleteRepeater', function () {
            limit--;
            limitRepeater();
        })

        function limitRepeater() {
            if (limit > 6) {
                $('#addRepeater').attr('disabled', true)
            } else {
                $('#addRepeater').attr('disabled', false)
            }
        }

    </script>
@endpush
