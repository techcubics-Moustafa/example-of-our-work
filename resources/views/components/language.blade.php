<li class="profile-nav onhover-dropdown">
    <div class="media profile-media">
        @foreach($languages as $data)
            @if($data['code'] == locale())
                <a>
                    <img src="{{ asset('flags/'.$data['flag'].'.png') }}" class="p-l-5">
                    <span class="f-16 f-w-600 text-dark">{{ $data['name'] }}</span>
                </a>
            @endif
        @endforeach
    </div>
    <ul class="profile-dropdown onhover-show-div">
        @foreach($languages as $data)
            <li>
                <a href="{{ route('lang',[$data['code'],$guard]) }}">
                    <img src="{{ asset('flags/'.$data['flag'].'.png') }}" class="p-l-5">
                    <span>{{ $data['name'] }} </span>
                </a>
            </li>
        @endforeach
    </ul>
</li>
