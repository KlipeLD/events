@extends ('layout')

@section ('content')
    <div id="wrapper">
        <div id="page" class="container">
            <div id="content">
                <div id="sidebar2">
                    <ul class="style1">
                        <li class="first">
                            <h3>
                                {{$reservationData['name']}}, спасибо за пользование нашим сервисом.
                                <br>
                                @if($error)
                                К сожалению при бронировании проихошли ошибки. Возможно вы не указали места или данные места уже заняты. Попробуйте забронировать места снова.
                                @else
                                    @if(!empty($reservationData['freePlaces']))
                                        Ваша бронь подтверждена. Идентификатор брони - {{$reservationData['id']}}.
                                        <br>
                                        Забронированы места: 
                                        @forelse($reservationData['freePlaces'] as $reserv)
                                        {{$reserv." "}}
                                        @empty
                                        @endforelse
                                    @else
                                    К сожалению при бронировании проихошли ошибки. Возможно вы не указали места или данные места уже заняты. Попробуйте забронировать места снова.
                                    @endif
                                    <br>
                                    @if(!empty($reservationData['notFreePlaces']))
                                        Не удалось забронировать следующие места: 
                                        @forelse($reservationData['notFreePlaces'] as $free)
                                        {{$free." "}}
                                        @empty
                                        @endforelse
                                        <br>
                                        Данные места уже заняты.
                                    @else    
                                    @endif
                                @endif
                            </h3>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection