@extends('dashboard')
@section('content')
    <div class="container-fluid">
        <div class="col-sm-6">
            <h3>Друзья</h3>
            @foreach($newFriends as $friend)
                <div class="mb-3">
                    <div class="row g-0">
                        <div class="col-md-2">
                            <img src="{{$friend->avatar}}" class="rounded-circle" width="100%">
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <h5 class="card-title"><a href="/user/{{$friend->id}}">{{$friend->name}} {{$friend->lastname}}</a></h5>
                                <p class="card-text">{{$friend->bdate}}</p>
                                @if(!$friend->agree) (заявка отправлена)@endif
                                <form action="/addFriend" method="post">
                                    @csrf
                                    <input type="hidden" name="friend_id" value="{{$friend->id}}">
                                    <input type="submit" class="btn btn-secondary btn-sm" value="Удалить из друзей">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <hr>
            @foreach($friends as $friend)
                <div class="mb-3">
                    <div class="row g-0">
                        <div class="col-md-2">
                            <img src="{{$friend->avatar}}" class="rounded-circle" width="100%">
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <h5 class="card-title"><a href="/user/{{$friend->id}}">{{$friend->name}} {{$friend->lastname}}</a></h5>
                                <p class="card-text">{{$friend->bdate}}</p>
                                @if(!$friend->agree) (заявка отправлена)@endif
                                <form action="/addFriend" method="post">
                                    @csrf
                                    <input type="hidden" name="friend_id" value="{{$friend->id}}">
                                    <input type="submit" class="btn btn-secondary btn-sm" value="Удалить из друзей">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <hr/>
            <h3>Другие пользователи</h3>
            @foreach($users as $user)
                @if($user == auth()->user()) @continue @endif
                <div class="mb-3">
                    <div class="row g-0">
                        <div class="col-md-2">
                            <img src="{{$user->avatar}}" class="rounded-circle" width="100%">
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <h5 class="card-title">{{$user->name}} {{$user->lastname}}</h5>
                                <p class="card-text">{{$user->bdate}}</p>
                                <form action="/addFriend" method="post">
                                    @csrf
                                    <input type="hidden" name="friend_id" value="{{$user->id}}">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Добавить в друзья">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
