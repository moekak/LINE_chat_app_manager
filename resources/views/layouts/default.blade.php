<!DOCTYPE html>
<html lang="en">

<head>
      <title>@yield('title', 'チャット管理システム')</title>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
      <link rel="stylesheet" href="{{ asset('css/common.css') }}">
      <link rel="shortcut icon" href="{{asset("img/icons8-chat-32.png")}}">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css"/>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <script src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>
      <noscript>
            <style>
            /**
            * Reinstate scrolling for non-JS clients
            */
            .simplebar-content-wrapper {
                  scrollbar-width: auto;
                  -ms-overflow-style: auto;
            }
      
            .simplebar-content-wrapper::-webkit-scrollbar,
            .simplebar-hide-scrollbar::-webkit-scrollbar {
                  display: initial;
                  width: initial;
                  height: initial;
            }
            </style>
      </noscript>
      @yield('style')

</head>

<body>
      <div class="bg hidden"></div>
      <div class="loader hidden"></div>
      <div class="fixed_bg hidden"></div>
      <main class="dashboard">
            <nav class="nav__area">
                  <div class="nav__item-ttl bg-blue txt-white font-25 txt-center p-20">管理システム</div>
                  <div class="nav__item-container">
                        <div class="nav__item-option">
                              <a href="{{route("dashboard")}}" class="nav__item-option__txt txt-gray">アカウント一覧</a>
                        </div>
                        @if (Route::currentRouteName() == "account.show")
                              <div class="nav__item-option">
                                    <a href="{{route("account.block.user", ["id" => Route::current()->parameter('id')])}}" class="nav__item-option__txt txt-gray">ブロックアカウント一覧</a>
                              </div>
                              <div class="nav__item-option">
                                    <p class="nav__item-option__txt txt-gray" id="js_create_message_btn">初回メッセージ設定</p>
                              </div>
                              <div class="nav__item-option">
                                    <p class="nav__item-option__txt txt-gray" id="js_create_title_btn">タイトル表示変更</p>
                              </div>
                              <div class="nav__item-option">
                                    <p class="nav__item-option__txt txt-gray" id="js_create_text_btn">固定テキスト設定</p>
                              </div>
                              <div class="nav__item-option">
                                    <p class="nav__item-option__txt txt-gray" id="js_create_template_btn">メッセージテンプレート作成</p>
                              </div>
                        @endif
                        @if (Route::currentRouteName() == "dashboard")
                              <div class="nav__item-option">
                                    <p class="nav__item-option__txt txt-gray" id="js_create_account_btn">アカウント追加</p>
                              </div>
                              <div class="nav__item-option">
                                    <p class="nav__item-option__txt txt-gray" id="js_update_line_btn" data-id="{{$user->id}}">LINE送信文言変更</p>
                              </div>
                        @endif
                        @if (Route::currentRouteName() == "account.block.user")
                              <div class="nav__item-option">
                                    <a href="{{route("account.show", ["id" => Route::current()->parameter('id')])}}"  class="nav__item-option__txt txt-gray">ユーザー一覧</a>
                              </div>
                        @endif
                        <form action="{{route("logout")}}" method="post">
                              @csrf
                              <button class="logout_btn" type="submit">
                                    <p class="nav__item-option__txt txt-gray">ログアウト</p> 
                              </button>
                        </form>

                  </div>
            </nav>
            <section class="dashboard__wrapper">
                  @if (Route::currentRouteName() == "account.show" || Route::currentRouteName() == "account.block.user")
                        <div class="dashboard__wrapper-top_show p-20 shadow-bottom ">
                              <h4 style="font-weight: 700;">{{$account_name}}</h4>
                              <div class="dashboard__wrapper-top--box">
                                    <img src="{{asset("img/icons8-user-icon-48.png")}}" alt="" class="dashboard__wrapper-top--box-img">  
                                    <p class="dashboard__wrapper-top-txt">{{$user->name}}</p>
                              </div>
                        </div>
                  @else
                        <div class="dashboard__wrapper-top p-20 shadow-bottom ">
                              <div class="dashboard__wrapper-top--box">
                                    <img src="{{asset("img/icons8-user-icon-48.png")}}" alt="" class="dashboard__wrapper-top--box-img">  
                                    <p class="dashboard__wrapper-top-txt">{{$user->name}}</p>
                              </div>
                        </div>
                  @endif
                  @yield('main')
            </section>
      </main>

      @yield('script')
      <script src="{{mix("js/app.js")}}"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>