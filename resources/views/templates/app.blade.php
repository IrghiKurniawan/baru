<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/30d9a6bac1.js" crossorigin="anonymous"></script>
</head>
<body>
  @if (Auth::check())
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            
            @if(Auth::user()->role ==('GUEST'))
              <!-- Menu untuk Guest -->
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{url('/')}}">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{route('report.index')}}">Daftar Laporan</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{route('report.monitor')}}">Monitoring</a>
              </li>
            @endif
            
            @if(Auth::user()->role ==('STAFF'))
              <!-- Menu untuk Staff -->
              <li class="nav-item">
                <a class="nav-link" href="{{route('response')}}">Response</a>
              </li>
            @endif
            
            @if(Auth::user()->role ==('HEAD_STAFF'))
              <!-- Menu untuk Head Staff -->
              <li class="nav-item">
                <a class="nav-link" href="{{route('home.akun')}}">Kelola Akun</a>
              </li>
            @endif
            
            <li class="nav-item">
              <a class="nav-link" href="{{route('logout')}}">Logout</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" aria-disabled="true">Disabled</a>
            </li>
          </ul>
          <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>
  @endif
  
  <div class="container mt-5">
    @yield('content')
  </div>

  @stack('style')
  @stack('script')
</body>
</html>
