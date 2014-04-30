  <header>
    <div class="fix">
      <div class="box-center">
        <div class="head-user"><span></span>Hola, {{ Auth::user()->name .' '. Auth::user()->lastName; }}</div>
        <ul class="head-options">
          <li><a href="{{ route('editpassword') }}" title="Password"><span class="ichd-perfil"></span>Password</a></li>
          <li><a href="{{ action('App\Modules\Dashboard\Controllers\UserController@editPerfil') }}" title="Perfil"><span class="ichd-perfil"></span>Perfil</a></li>
          <li><a href="{{ action('App\Modules\Auth\Controllers\AuthController@getLogout') }}" title="Logout"><span class="ichd-logout"></span>Logout</a></li>
        </ul>
      </div>
    </div>
  </header>