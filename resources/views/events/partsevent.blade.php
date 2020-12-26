@extends ('layout')

@section ('content')
    <div id="wrapper">
        <div id="page" class="container">
            <div id="content">
                <div id="sidebar2">
                    <ul class="style1">
                        <h1>
                            {{$nameEvent}}
                        </h1>
                        @forelse($partsEvent as $i => $partEvent)
                            <li class="first">
                                <h3>
                                    <a href="/events/{{$partEvent['showId']}}/parts/{{$partEvent['id']}}">Номер события: {{$partEvent['id']}}<br>
                                    Дата события: {{$partEvent['date']}}</a>
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