<div {{ $attributes }}>
    <label class="form-label" for="owner_id">
        {{ _trans('Owner name') }}
        @can('Owner add')
            <a href="{{ route('admin.owner.create') }}" target="_blank" class="btn-sm btn-primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </a>
        @endcan
    </label>
    <select id="owner_id" name="owner_id"
            class="js-example-basic-single @error('owner_id') is-invalid @enderror">
        <option value="">{{ _trans('Select owner name') }}</option>
        @foreach($owners as $owner)
            <option value="{{ $owner->id }}" @selected($ownerId == $owner->id)>{{ $owner->user->name }}</option>
        @endforeach
    </select>
    @error('owner_id')
    <span class="text-danger">{!! $message !!} </span>
    @enderror
</div>
