<!-- Model forget password -->
<div class="modal fade" id="{{ $name }}" tabindex="-1" role="dialog" aria-labelledby="{{ $name }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _trans('Change Password') }} </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ $route }}" method="POST">
                @csrf
                {{ Form::hidden('id',"",['id'=>'user_id']) }}
                <div class="modal-body theme-form login-form p-0">
                    <div class="form-group">
                        <label for="password" class="mb-2">{{ _trans('New Password') }}</label>
                        <div class="input-group">
                                <span class="input-group-text">
                                    <i class="icon-lock"></i>
                                </span>
                            <input class="form-control @error('password') is-invalid @enderror"
                                   type="password"
                                   id="password"
                                   name="password" required=""
                                   placeholder="*********">
                            <div class="show-hide">
                                <span class="show show_password">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="mb-2">{{ _trans('Confirm New Password') }}</label>
                        <div class="input-group">
                                <span class="input-group-text"><i class="icon-lock"></i>
                                </span>
                            <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                   type="password"
                                   id="password_confirmation"
                                   required=""
                                   name="password_confirmation"
                                   placeholder="*********">
                            <div class="show-hide">
                                <span class="show show_password">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">{{ _trans('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).on('click', '.show_password', function () {
            if ($('#password').attr('type') == 'password') {
                $('#password,#password_confirmation').attr('type', 'text')
            } else {
                $('#password,#password_confirmation').attr('type', 'password')
            }
        })
        $('#{{ $name }}').on('show.bs.modal', function (e) {
            var id = e.relatedTarget.dataset.id;
            $("#user_id").val(id);
        });
    </script>
@endpush
