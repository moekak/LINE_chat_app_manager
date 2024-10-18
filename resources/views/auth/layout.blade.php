<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <link rel="stylesheet" href="{{ asset('css/auth/signup.css') }}">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
      
      <title>Document</title>
</head>
<body>
      <section class="signup__wrapper">
            
            @if ($errors->any())
            @foreach ($errors->all() as $error)
                  <p>{{$error}}</p>  
            @endforeach
                
            @endif
            <div class="signup__wrapper-top">
                 <img src="{{asset("img/icons8-user-48.png")}}" alt="">
                 @yield('title')
            </div>
            
            <div class="signup__wrapper-input">
                  @yield('form')
                  
            </div>
            
      </section>
      <script src="{{mix("js/app.js")}}"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>