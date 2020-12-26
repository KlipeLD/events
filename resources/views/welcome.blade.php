@extends ('layout')

@section ('content')
    <div id="wrapper">
        <div id="page" class="container">
            <div id="content">
                <div id="sidebar2">
                    <ul class="style1">
                        @forelse($events as $i => $event)
                            <li class="first">
                                <h3>
                                    <a href="/events/{{$event['id']}}">{{$event['id']}}-{{$event['name']}}</a>
                                </h3>
                            </li>
                        @empty
                            <p>No relevant articles yet</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection