<div>
    <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}">
            <img src="{{ getAvatar(Utility::getValByName('web_logo') ) }}">
        </a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
    </div>
    <div class="logo-icon-wrapper"><a href="{{ route('admin.dashboard') }}">
            <img src="{{ getAvatar(Utility::getValByName('web_logo') ) }}">
        </a>
    </div>
    <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
            <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn">
                    <a href="{{ route('admin.dashboard') }}">
                        <img class="img-fluid" src="{{ getAvatar(Utility::getValByName('web_logo') ) }}" alt=""></a>
                    <div class="mobile-back text-end">
                        <span>{{ _trans('Back') }}</span>
                        <i class="fa fa-angle-right ps-2" aria-hidden="true">
                        </i>
                    </div>
                </li>

                <li class="sidebar-list {{ menuRoute('admin.dashboard') }}">
                    <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.dashboard') }}">
                        <div class="curve1"></div>
                        <div class="curve2"></div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path d="M15.596 15.6963H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M15.596 11.9365H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M11.1312 8.17725H8.37622" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M3.61011 12C3.61011 18.937 5.70811 21.25 12.0011 21.25C18.2951 21.25 20.3921 18.937 20.3921 12C20.3921 5.063 18.2951 2.75 12.0011 2.75C5.70811 2.75 3.61011 5.063 3.61011 12Z" stroke="#130F26"
                                          stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </g>
                        </svg>
                        <span>{{ _trans('Dashboard') }}</span>
                    </a>
                </li>

                @canany(['User list','User add','User edit','User delete'])
                    <li class="sidebar-list {{ menuRoute('admin/user*','li') }}">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.user.index') }}">
                            <div class="curve1"></div>
                            <div class="curve2"></div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <g>
                                        <path d="M15.596 15.6963H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M15.596 11.9365H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M11.1312 8.17725H8.37622" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M3.61011 12C3.61011 18.937 5.70811 21.25 12.0011 21.25C18.2951 21.25 20.3921 18.937 20.3921 12C20.3921 5.063 18.2951 2.75 12.0011 2.75C5.70811 2.75 3.61011 5.063 3.61011 12Z" stroke="#130F26"
                                              stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </g>
                            </svg>
                            <span>{{ _trans('Users') }}</span>
                        </a>
                    </li>
                @endcanany

                @canany(['Company list','Company add','Company edit','Company delete'])
                    <li class="sidebar-list {{ menuRoute('admin/company*','li') }}">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.company.index') }}">
                            <div class="curve1"></div>
                            <div class="curve2"></div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <g>
                                        <path d="M15.596 15.6963H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M15.596 11.9365H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M11.1312 8.17725H8.37622" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M3.61011 12C3.61011 18.937 5.70811 21.25 12.0011 21.25C18.2951 21.25 20.3921 18.937 20.3921 12C20.3921 5.063 18.2951 2.75 12.0011 2.75C5.70811 2.75 3.61011 5.063 3.61011 12Z" stroke="#130F26"
                                              stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </g>
                            </svg>
                            <span>{{ _trans('Companies') }}</span>
                        </a>
                    </li>
                @endcanany


                @canany(['Category list','Category add','Category edit','Category delete',
                            'Feature list','Feature add','Feature edit','Feature delete',
                            'Service list','Service add','Service edit','Service delete',
                            'Special list','Special add','Special edit','Special delete',
                        ])
                    <li class="sidebar-list ">
                        <a class="sidebar-link sidebar-title" href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <g>
                                        <path d="M9.07861 16.1355H14.8936" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M2.3999 13.713C2.3999 8.082 3.0139 8.475 6.3189 5.41C7.7649 4.246 10.0149 2 11.9579 2C13.8999 2 16.1949 4.235 17.6539 5.41C20.9589 8.475 21.5719 8.082 21.5719 13.713C21.5719 22 19.6129 22 11.9859 22C4.3589 22 2.3999 22 2.3999 13.713Z"
                                              stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </g>
                            </svg>
                            <span>{{_trans('Menus')}}</span></a>
                        <ul class="sidebar-submenu">
                            @canany(['Special list','Special add','Special edit','Special delete'])
                                <li class="{{ menuRoute('admin/special*','li') }}">
                                    <a href="{{ route('admin.special.index') }}">{{ _trans('Specials') }}</a></li>
                            @endcanany

                            @canany(['Category list','Category add','Category edit','Category delete'])
                                <li class="{{ menuRoute('admin/category*','li') }}">
                                    <a href="{{ route('admin.category.index') }}">{{ _trans('Categories') }}</a></li>
                            @endcanany

                            @canany(['Feature list','Feature add','Feature edit','Feature delete'])
                                <li class="{{ menuRoute('admin/feature*','li') }}">
                                    <a href="{{ route('admin.feature.index') }}">{{ _trans('Features') }}</a></li>
                            @endcanany

                            @canany(['Service list','Service add','Service edit','Service delete'])
                                <li class="{{ menuRoute('admin/service*','li') }}">
                                    <a href="{{ route('admin.service.index') }}">{{ _trans('Services') }}</a></li>
                            @endcanany

                        </ul>
                    </li>
                @endcanany

                @canany(['Property list','Property add','Property edit','Property delete',
                          'Project list','Project add','Project edit','Project delete',
                        ])
                    <li class="sidebar-list ">
                        <a class="sidebar-link sidebar-title" href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <g>
                                        <path d="M9.07861 16.1355H14.8936" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M2.3999 13.713C2.3999 8.082 3.0139 8.475 6.3189 5.41C7.7649 4.246 10.0149 2 11.9579 2C13.8999 2 16.1949 4.235 17.6539 5.41C20.9589 8.475 21.5719 8.082 21.5719 13.713C21.5719 22 19.6129 22 11.9859 22C4.3589 22 2.3999 22 2.3999 13.713Z"
                                              stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </g>
                            </svg>
                            <span>{{_trans('Real Estates')}}</span></a>
                        <ul class="sidebar-submenu">
                            @canany(['Property list','Property add','Property edit','Property delete'])
                                <li class="{{ menuRoute('admin/property*','li') }}">
                                    <a href="{{ route('admin.property.index') }}">{{ _trans('Properties') }}</a></li>
                            @endcanany

                            @canany(['Project list','Project add','Project edit','Project delete'])
                                <li class="{{ menuRoute('admin/project*','li') }}">
                                    <a href="{{ route('admin.project.index') }}">{{ _trans('Projects') }}</a></li>
                            @endcanany

                        </ul>
                    </li>
                @endcanany

                @canany(['Role list','Role add','Role edit','Role delete',
                          'Employee list','Employee add','Employee edit','Employee delete',
                        ])
                    <li class="sidebar-list ">
                        <a class="sidebar-link sidebar-title" href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <g>
                                        <path d="M9.07861 16.1355H14.8936" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M2.3999 13.713C2.3999 8.082 3.0139 8.475 6.3189 5.41C7.7649 4.246 10.0149 2 11.9579 2C13.8999 2 16.1949 4.235 17.6539 5.41C20.9589 8.475 21.5719 8.082 21.5719 13.713C21.5719 22 19.6129 22 11.9859 22C4.3589 22 2.3999 22 2.3999 13.713Z"
                                              stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </g>
                            </svg>
                            <span>{{_trans('Employees')}}</span></a>
                        <ul class="sidebar-submenu">
                            @canany(['Role list','Role add','Role edit','Role delete'])
                                <li class="{{ menuRoute('admin/role*','li') }}">
                                    <a href="{{ route('admin.role.index') }}">{{ _trans('Roles') }}</a></li>
                            @endcanany
                            @canany(['Employee list','Employee add','Employee edit','Employee delete'])
                                <li class="{{ menuRoute('admin/employee*','li') }}">
                                    <a href="{{ route('admin.employee.index') }}">{{ _trans('Employees') }}</a></li>
                            @endcanany

                        </ul>
                    </li>
                @endcanany



                @canany([
                          'Country list','Country add','Country edit','Country delete',
                          'Governorate list','Governorate add','Governorate edit','Governorate delete',
                          'Region list','Region add','Region edit','Region delete',
                          'Language list','Language add','Language edit','Language delete',
                          'Currency list','Currency add','Currency edit','Currency delete',
                          'Social#Media list','Social#Media add','Social#Media edit','Social#Media delete',
                          'Setting list',
                          'Setting#Account list','Setting#Account edit',
                          'Report#Comment list','Report#Comment add','Report#Comment edit','Report#Comment delete',
                          ])
                    <li class="sidebar-list ">
                        <a class="sidebar-link sidebar-title" href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <g>
                                        <path d="M9.07861 16.1355H14.8936" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M2.3999 13.713C2.3999 8.082 3.0139 8.475 6.3189 5.41C7.7649 4.246 10.0149 2 11.9579 2C13.8999 2 16.1949 4.235 17.6539 5.41C20.9589 8.475 21.5719 8.082 21.5719 13.713C21.5719 22 19.6129 22 11.9859 22C4.3589 22 2.3999 22 2.3999 13.713Z"
                                              stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </g>
                            </svg>
                            <span>{{_trans('Settings')}}</span></a>
                        <ul class="sidebar-submenu">

                            @canany(['Country list','Country add','Country edit','Country delete'])
                                <li class="{{ menuRoute('admin/country*','li') }}">
                                    <a href="{{ route('admin.country.index') }}">{{_trans('Countries')}}</a></li>
                            @endcanany

                            @canany(['Governorate list','Governorate add','Governorate edit','Governorate delete'])
                                <li class="{{ menuRoute('admin/governorate*','li') }}">
                                    <a href="{{ route('admin.governorate.index') }}">{{_trans('Governorates')}}</a></li>
                            @endcanany

                            @canany(['Region list','Region add','Region edit','Region delete'])
                                <li class="{{ menuRoute('admin/region*','li') }}">
                                    <a href="{{ route('admin.region.index') }}">{{_trans('Regions')}}</a></li>
                            @endcanany

                            @canany(['Good#Type list','Good#Type add','Good#Type edit','Good#Type delete'])
                                <li class="{{ menuRoute('admin/good-type*','li') }}">
                                    <a href="{{ route('admin.good-type.index') }}">{{_trans('Good Types')}}</a></li>
                            @endcanany

                            @canany(['Language list','Language add','Language edit','Language delete'])
                                <li class="{{ menuRoute('admin/language*','li') }}">
                                    <a href="{{ route('admin.language.index') }}">{{_trans('Languages')}}</a></li>
                            @endcanany

                            @canany(['Currency list','Currency add','Currency edit','Currency delete'])
                                <li class="{{ menuRoute('admin/currency*','li') }}">
                                    <a href="{{ route('admin.currency.index') }}">{{_trans('Currencies')}}</a></li>
                            @endcanany

                            @canany(['Report#Comment list','Report#Comment add','Report#Comment edit','Report#Comment delete'])
                                <li class="{{ menuRoute('admin/our-partner*','li') }}">
                                    <a href="{{ route('admin.report-comment.index') }}">{{ _trans('Report Comments') }}</a></li>
                            @endcanany

                            @canany(['Social#Media list','Social#Media add','Social#Media edit','Social#Media delete'])
                                <li class="{{ menuRoute('admin/social-media*','li') }}">
                                    <a href="{{ route('admin.social-media.index') }}">{{_trans('Social Media')}}</a></li>
                            @endcanany

                            @canany(['Setting list'])
                                <li class="{{ menuRoute('admin/setting*','li') }}">
                                    <a href="{{ route('admin.setting.index') }}">{{_trans('Setting')}}</a></li>
                            @endcanany
                            @canany(['Setting#Account list','Setting#Account edit'])
                                <li class="{{ menuRoute('profile*','li') }}">
                                    <a href="{{ route('admin.profile.account') }}">{{_trans('Account Setting')}}</a>
                                </li>
                            @endcanany

                        </ul>
                    </li>
                @endcanany

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.logout') }}">
                        <div class="curve1"></div>
                        <div class="curve2"></div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path d="M15.596 15.6963H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M15.596 11.9365H8.37598" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M11.1312 8.17725H8.37622" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M3.61011 12C3.61011 18.937 5.70811 21.25 12.0011 21.25C18.2951 21.25 20.3921 18.937 20.3921 12C20.3921 5.063 18.2951 2.75 12.0011 2.75C5.70811 2.75 3.61011 5.063 3.61011 12Z" stroke="#130F26"
                                          stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </g>
                        </svg>
                        <span>{{ _trans('Logout') }}</span>
                    </a>
                </li>
            </ul>

        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    </nav>
</div>
