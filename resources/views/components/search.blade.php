<div class="col-md-8"> {{-- $attributes->merge(['class' => 'text-xl']) --}}
    <div class="d-flex justify-content-between align-items-center">
        <div class="input-group">
            <input class="form-control" type="text"
                   name="search"
                   placeholder="{{ _trans('Search') }}"
                   aria-label="search"
                   value="{{ request('search') }}"
                   aria-describedby="basic-addon1" data-bs-original-title="" title="">
            <span class="input-group-text" id="basic-addon1">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <g>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M11.2753 2.71436C16.0029 2.71436 19.8363 6.54674 19.8363 11.2753C19.8363 16.0039 16.0029 19.8363 11.2753 19.8363C6.54674 19.8363 2.71436 16.0039 2.71436 11.2753C2.71436 6.54674 6.54674 2.71436 11.2753 2.71436Z"
                                  stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M19.8987 18.4878C20.6778 18.4878 21.3092 19.1202 21.3092 19.8983C21.3092 20.6783 20.6778 21.3097 19.8987 21.3097C19.1197 21.3097 18.4873 20.6783 18.4873 19.8983C18.4873 19.1202 19.1197 18.4878 19.8987 18.4878Z"
                                  stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </g>
                    </g>
                </svg>
            </span>
        </div>
        <select name="column_name" class="js-example-basic-single">
            <optgroup label="">
                @foreach($columns as $key => $column)
                    <option @selected(request('column_name') == $key) value="{{ $key }}">{{ $column }}</option>
                @endforeach
            </optgroup>
        </select>

        <div class="form-check form-check-inline">
            <input
                class="form-check-input"
                type="radio"
                name="sort"
                id="DESC"
                value="DESC"
                @checked(request('sort') == 'DESC')
            />
            <label class="form-check-label" for="DESC">
                <i class="fa fa-arrow-up" aria-hidden="true"></i>

            </label>
        </div>

        <div class="form-check form-check-inline">
            <input
                class="form-check-input"
                type="radio"
                name="sort"
                id="ASC"
                value="ASC"
                @checked(request('sort') == 'ASC')
            />
            <label class="form-check-label" for="ASC">
                <i class="fa fa-arrow-down" aria-hidden="true"></i>
            </label>
        </div>



        <button type="submit" class="btn btn-primary">
            <i class="fa fa-search" aria-hidden="true"></i>
        </button>

        {{--{{ $slot }}--}} {{-- call default slot --}}

        {{--{{ $title }}--}} {{-- call slot by name  --}}
    </div>
</div>
