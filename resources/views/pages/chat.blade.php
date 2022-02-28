@extends('dashboard')
@section('content')
    <div class="container-fluid">
        <div class="col-md-9 mx-auto">
            <!-- DIRECT CHAT PRIMARY -->
            <div class="card card-primary card-outline direct-chat direct-chat-primary">
                <div class="card-header">
                    <h3 class="card-title">Direct Chat</h3>

                    <div class="card-tools">
                        <span title="3 New Messages" class="badge bg-primary">3</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                            <i class="fas fa-comments"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Conversations are loaded here -->
                    <div class="direct-chat-messages" id="messageBox">
                        @foreach($chat as $message)
                            @if($message->from == $currentUser->id)
                                <!-- Наши сообщения -->
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-right">{{$currentUser->name}} {{$currentUser->lastname}}</span>
                                            <span class="direct-chat-timestamp float-left">{{$message->crated_at}}</span>
                                        </div>
                                        <!-- /.direct-chat-infos -->
                                        <img class="direct-chat-img" src="{{$currentUser->avatar}}" alt="Message User Image">
                                        <!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            {{$message->message}}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->
                            @else
                                <!-- Сообщения собеседника -->
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">{{$user->name}} {{$user->lastname}}</span>
                                            <span class="direct-chat-timestamp float-right">{{$message->crated_at}}</span>
                                        </div>
                                        <!-- /.direct-chat-infos -->
                                        <img class="direct-chat-img" src="{{$user->avatar}}" alt="Message User Image">
                                        <!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            {{$message->message}}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->
                                @endif
                        @endforeach
                    </div>
                    <!--/.direct-chat-messages-->

                    <!-- Contacts are loaded here -->
                    <div class="direct-chat-contacts">
                        <ul class="contacts-list">
                            <li>
                                <a href="#">
                                    <img class="contacts-list-img" src="../dist/img/user1-128x128.jpg" alt="User Avatar">

                                    <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Count Dracula
                            <small class="contacts-list-date float-right">2/28/2015</small>
                          </span>
                                        <span class="contacts-list-msg">How have you been? I was...</span>
                                    </div>
                                    <!-- /.contacts-list-info -->
                                </a>
                            </li>
                            <!-- End Contact Item -->
                        </ul>
                        <!-- /.contatcts-list -->
                    </div>
                    <!-- /.direct-chat-pane -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <form onsubmit="sendMessage(this); return false;">
                        @csrf
                        <input name="to" type="hidden" value="{{$user->id}}">
                        <div class="input-group">
                            <input type="text" name="message" placeholder="Напишите сообщение" class="form-control">
                            <span class="input-group-append">
                      <button type="submit" class="btn btn-primary">Отправить</button>
                    </span>
                        </div>
                    </form>
                </div>
                <!-- /.card-footer-->
            </div>
            <!--/.direct-chat -->
        </div>
    </div>

    <script>
        // Можно сделать
        function sendMessage(form){
            let formData = new FormData(form);
            fetch('/receivingMessage',{
                method: "POST",
                body: formData
            }).then(response=>response.json())
            .then(result=>{
                if(result.result == 'success'){
                    messageBox.innerHTML += `<div class="direct-chat-msg right">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-right">{{$currentUser->name}} {{$currentUser->lastname}}</span>
                            <span class="direct-chat-timestamp float-left">${$date = new Date()}</span>
                        </div>
                        <img class="direct-chat-img" src="{{$currentUser->avatar}}" alt="Message User Image">
                        <div class="direct-chat-text">${formData.get('message')}</div>
                    </div>`;
                }
            });

        }

        // Нельзя
        function getMessage(){
            setInterval(()=>{
                let token = document.getElementsByName('_token')[0].value;
                let formData = new FormData();
                formData.append('chat_id', {{$user->id}})
                formData.append('_token', token);
                fetch('/getMessage', {
                    method: 'post',
                    body: formData
                }).then(response=>response.json())
                .then(result=> {
                    messageBox.innerHTML = '';
                    for (let i = 0; i < result.length; i++) {
                        message = result[i];
                        if (message.from == {{$currentUser->id}})
                            messageBox.innerHTML += `
                            <div class="direct-chat-msg right">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-right">{{$currentUser->name}} {{$currentUser->lastname}}</span>
                                    <span class="direct-chat-timestamp float-left">${message.crated_at}</span>
                                </div>
                                <img class="direct-chat-img" src="{{$currentUser->avatar}}" alt="Message User Image">
                                    <div class="direct-chat-text">${message.message}</div>
                            </div>`;
                        else
                            messageBox.innerHTML += ` <div class="direct-chat-msg">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">{{$user->name}} {{$user->lastname}}</span>
                                    <span class="direct-chat-timestamp float-right">${message.crated_at}</span>
                                </div>
                                <img class="direct-chat-img" src="{{$user->avatar}}" alt="Message User Image">
                                    <div class="direct-chat-text">${message.message}</div>
                            </div>`;
                    }

                });
            }, 3000);
        }
        getMessage();
    </script>
@endsection
