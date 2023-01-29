<div class="header-wrapper row m-0">
    <div class="header-logo-wrapper col-2 p-0">
        <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}">
                <img src="{{ Utility::getValByName('web_logo') }}"></a>
        </div>
        <div class="toggle-sidebar">
            <div class="status_toggle sidebar-toggle d-flex">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <g>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M21.0003 6.6738C21.0003 8.7024 19.3551 10.3476 17.3265 10.3476C15.2979 10.3476 13.6536 8.7024 13.6536 6.6738C13.6536 4.6452 15.2979 3 17.3265 3C19.3551 3 21.0003 4.6452 21.0003 6.6738Z"
                                  stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M10.3467 6.6738C10.3467 8.7024 8.7024 10.3476 6.6729 10.3476C4.6452 10.3476 3 8.7024 3 6.6738C3 4.6452 4.6452 3 6.6729 3C8.7024 3 10.3467 4.6452 10.3467 6.6738Z"
                                  stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M21.0003 17.2619C21.0003 19.2905 19.3551 20.9348 17.3265 20.9348C15.2979 20.9348 13.6536 19.2905 13.6536 17.2619C13.6536 15.2333 15.2979 13.5881 17.3265 13.5881C19.3551 13.5881 21.0003 15.2333 21.0003 17.2619Z"
                                  stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M10.3467 17.2619C10.3467 19.2905 8.7024 20.9348 6.6729 20.9348C4.6452 20.9348 3 19.2905 3 17.2619C3 15.2333 4.6452 13.5881 6.6729 13.5881C8.7024 13.5881 10.3467 15.2333 10.3467 17.2619Z"
                                  stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </g>
                </svg>
            </div>
        </div>
    </div>

    <div class="nav-right col-10 pull-right right-header p-0">
        <ul class="nav-menus">
            {{-- is components --}}
            <x-language guard="admin"/>
           {{-- @canany(['Notification list','Notification add'])

                @php
                    $notifications = getNotifications('App\Models\Admin',auth()->user()->id,['join_us','ticket'])
                @endphp
                <li class="onhover-dropdown">
                    <div class="notification-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M11.9961 2.51416C7.56185 2.51416 5.63519 6.5294 5.63519 9.18368C5.63519 11.1675 5.92281 10.5837 4.82471 13.0037C3.48376 16.4523 8.87614 17.8618 11.9961 17.8618C15.1152 17.8618 20.5076 16.4523 19.1676 13.0037C18.0695 10.5837 18.3571 11.1675 18.3571 9.18368C18.3571 6.5294 16.4295 2.51416 11.9961 2.51416Z"
                                          stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M14.306 20.5122C13.0117 21.9579 10.9927 21.9751 9.68604 20.5122" stroke="#130F26"
                                          stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </g>
                        </svg>
                        <span class="badge rounded-pill badge-warning" id="notification_admin">
                    {{ $notifications->count() }}
                    </span>
                    </div>
                    <div class="onhover-show-div notification-dropdown">
                        <div class="dropdown-title">
                            <h3>{{ _trans('Notifications') }}</h3><a class="f-right" href="#"> <i data-feather="bell"> </i></a>
                        </div>
                        <ul class="custom-scrollbar" id="notification_list_admin">
                            @foreach($notifications as $row)
                                <li id="notificationId_{{ $row->id }}">
                                    <div class="media">
                                        <div class="notification-img bg-light-success">
                                            @if ($row->type == 'join_us')
                                                <img src="{{ $row->data['owner_avatar'] }}" alt="" style="height: 20px;width: 20px;">
                                            @elseif ($row->type =='ticket')
                                                <img src="{{ getAvatar($row->customer->user->avatar) }}" alt="" style="height: 20px;width: 20px;">
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <h5>
                                                @if ($row->type == 'join_us')
                                                    <a class="f-14 m-0" href="{{ route('admin.join-us.show',$row->data['join_us_id']) }}">
                                                        {{ _trans('Request join us ').' '.$row->data['owner_name'] }}
                                                    </a>
                                                @elseif ($row->type =='ticket')
                                                    <a href="{{ route('admin.customer.show',$row->data['customer_id']) }}" class="f-14 m-0">
                                                        {{ _trans('Message ticket from').' '.$row->customer->user->name }}
                                                    </a>
                                                @endif
                                            </h5>
                                            @if ($row->type == 'join_us')
                                                <p>{{ _trans('request join us') }}</p><span>{{ $row->created_at->diffForHumans() }}</span>
                                            @elseif ($row->type =='ticket')
                                                <p>{{ _trans('new ticket') }}</p><span>{{ $row->created_at->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <a class="btn btn-primary btn-check-all" href="{{ route('admin.notification.all') }}">{{ _trans('Check all') }}</a>
                    </div>
                </li>
            @endcanany--}}

            <li class="maximize"><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <g>
                                <path d="M2.99609 8.71995C3.56609 5.23995 5.28609 3.51995 8.76609 2.94995" stroke="#130F26"
                                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M8.76616 20.99C5.28616 20.41 3.56616 18.7 2.99616 15.22L2.99516 15.224C2.87416 14.504 2.80516 13.694 2.78516 12.804"
                                    stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M21.2446 12.804C21.2246 13.694 21.1546 14.504 21.0346 15.224L21.0366 15.22C20.4656 18.7 18.7456 20.41 15.2656 20.99"
                                    stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M15.2661 2.94995C18.7461 3.51995 20.4661 5.23995 21.0361 8.71995" stroke="#130F26"
                                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </g>
                    </svg>
                </a></li>

            <li class="profile-nav onhover-dropdown">
                <div class="media profile-media">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <g>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M9.55851 21.4562C5.88651 21.4562 2.74951 20.9012 2.74951 18.6772C2.74951 16.4532 5.86651 14.4492 9.55851 14.4492C13.2305 14.4492 16.3665 16.4342 16.3665 18.6572C16.3665 20.8802 13.2505 21.4562 9.55851 21.4562Z"
                                      stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M9.55849 11.2776C11.9685 11.2776 13.9225 9.32356 13.9225 6.91356C13.9225 4.50356 11.9685 2.54956 9.55849 2.54956C7.14849 2.54956 5.19449 4.50356 5.19449 6.91356C5.18549 9.31556 7.12649 11.2696 9.52749 11.2776H9.55849Z"
                                      stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M16.8013 10.0789C18.2043 9.70388 19.2383 8.42488 19.2383 6.90288C19.2393 5.31488 18.1123 3.98888 16.6143 3.68188"
                                    stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M17.4608 13.6536C19.4488 13.6536 21.1468 15.0016 21.1468 16.2046C21.1468 16.9136 20.5618 17.6416 19.6718 17.8506"
                                    stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </g>
                    </svg>
                </div>
                <ul class="profile-dropdown onhover-show-div">
                    @canany(['Setting list'])
                        <li><a href="{{ route('admin.profile.account') }}"><i data-feather="user"></i><span>{{ _trans('My account') }} </span></a></li>
                    @endcanany
                    @canany(['Chat list','Chat add','Chat edit','Chat delete'])
                        <li><a href="{{ route('admin.chat.index') }}"><i data-feather="mail"></i><span>{{ _trans('Messages') }}</span></a></li>
                    @endcanany

                    @canany(['Setting#Account list','Setting#Account edit'])
                        <li><a href="{{ route('admin.setting.index') }}"><i data-feather="settings"></i><span>{{ _trans('Setting') }}</span></a></li>
                    @endcanany
                    <li><a href="{{ route('admin.logout') }}"><i data-feather="log-in"> </i><span>{{ _trans('Logout') }}</span></a></li>
                </ul>
            </li>
        </ul>
    </div>

</div>
