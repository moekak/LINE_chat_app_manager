<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <link rel="stylesheet" href="{{ secure_asset('css/auth/signup.css') }}">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
      
      <title>Document</title>
</head>
<body>
      <section class="signup__wrapper">
            @if ($errors->any())
            <div class="alert alert-danger alert_danger_container js_alert_danger" role="alert">
                  <ul style="margin: 0;">
                        @foreach ($errors->all() as $error)
                        <li class="alert_danger">{{$error}}</li>
                        @endforeach  
                  </ul>   
            </div> 
            @endif
            <div class="signup__wrapper-top">
                  <img src="{{asset("img/icons8-user-48.png")}}" alt="">
                  @yield('title')
            </div>
            
            <div class="signup__wrapper-input">
                  @yield('form')
                  
            </div>
            
      </section>
      <script>
            const btn = document.querySelector(".signup__wrapper-btn");
            btn.addEventListener("click", function() {  
                  btn.style.pointerEvents = "none";     
                  btn.style.opacity = "50%";       
            });
      </script>
      <script src="{{mix("js/app.js")}}"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>