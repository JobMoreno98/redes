<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Registro IPs')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

@stack('base-styles')
@stack('styles')
@stack('scripts')


<style>
body {
  background: linear-gradient(135deg, #0a1224, #071e5d);
  color: white;
  font-family: 'DM Sans', sans-serif;
  margin: 0;
}

header {
  position: fixed;
  top: 0;
  width: 100%;
  padding: 12px 15px;
  z-index: 1000;
  background: linear-gradient(135deg, rgba(22,33,62,0.95), rgba(10,18,36,0.9));
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
}
.header-container {
  display: flex;
  flex-direction: row; 
  justify-content: space-between; 
  align-items: center; 
  width: 100%; 
  gap: 10px;
}
.header-container img {
  height: 70px;
  object-fit: contain;
  transition: all 0.3s ease;
}
header.shrink { padding: 5px 15px; }
header.shrink .header-container img { height: 50px; } 

.header-auth-card {
  padding: 10px;
  background-color: rgba(255, 255, 255, 0.05);
  border-radius: 10px;
  border: 1px solid rgba(255,255,255,0.1);
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 0.9rem;
  font-weight: 500;
  color: #fff;
}
.header-auth-card form button {
  color: white;
  border-color: rgba(255,255,255,0.3);
  transition: all 0.3s ease;
}
.header-auth-card form button:hover {
  background-color: #dc3545;
  color: #fff;
  border-color: #dc3545;
}
/* --- FIN --- */


.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 220px;
  height: 100%;
  background-color: rgba(15,27,51,0.85);
  backdrop-filter: blur(10px);
  padding: 15px;
  color: white;
  overflow-y: scroll;
  scrollbar-width: none; 
  transition: left 0.3s ease;
  z-index: 2000;
}
.sidebar::-webkit-scrollbar { display: none; } 
.sidebar-logo {
  display: flex;
  justify-content: center;
}
.sidebar-logo img {
  max-width: 100%;
  height: auto;
  object-fit: contain;
}

.sidebar-user-card {
  padding: 10px;
  margin-top: 10px;
  margin-bottom: 10px;
  background-color: rgba(255, 255, 255, 0.05);
  border-radius: 10px;
  text-align: left; 
  border: 1px solid rgba(255,255,255,0.1);
}
.sidebar-user-card p {
  line-height: 1.3;
  font-size: 0.9rem;
  margin-bottom: 0.25rem;
}
.sidebar-logout-btn {
  color: white;
  border-color: rgba(255,255,255,0.3);
  transition: all 0.3s ease;
  font-size: 0.9rem;
  width: 100%;
}
.sidebar-logout-btn:hover {
  background-color: #dc3545;
  color: #fff;
  border-color: #dc3545;
}

.sidebar .nav-link {
  color: #ffffff !important;
  display: flex;
  align-items: center;
  padding: .5rem 0;
  border-radius: 5px;
}
.sidebar .nav-link.active {
  background-color: rgba(255,255,255,0.1);
  font-weight: 600;
  color: #79bd55 !important;
}
.sidebar .nav-link:hover, .sidebar .nav-link:hover i {
  color: #79bd55 !important;
}

main.with-sidebar {
  margin-left: 220px;
  padding: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}
main.index-main {
  padding-top: 110px; 
  padding-bottom: 50px; 
  display: flex;
  justify-content: center;
}

.card {
  background-color: #192853;
  border: none;
  border-radius: 15px;
  color: white;
  transition: transform 0.2s;
}
.index-main .card:hover {
  transform: scale(1.05);
}
.index-main .card i {
  font-size: 2.5rem;
  color: #f8f9fa;
}
.index-main .card:hover i,
.index-main .card:hover h5 {
  color: #79bd55 !important;
}

.table.rounded-4 {
  border-radius: 12px;
  overflow: hidden;
  border-collapse: separate !important;
}
.table.rounded-4 thead,
.table.rounded-4 tbody,
.table.rounded-4 th,
.table.rounded-4 td {
  border-radius: 0 !important;
}
.pagination { margin-bottom: 0 !important; }
.pagination .page-item .page-link {
  color: #198754;
  border-color: #19875433;
  transition: all 0.2s ease;
}
.pagination .page-item.active .page-link {
  background-color: #198754 !important;
  border-color: #198754 !important;
  color: #fff !important;
}
.pagination .page-link:hover {
  background-color: #198754 !important;
  color: white !important;
  border-color: #198754 !important;
}
.pagination .page-item:first-child .page-link {
  border-top-left-radius: 50px !important;
  border-bottom-left-radius: 50px !important;
}
.pagination .page-item:last-child .page-link {
  border-top-right-radius: 50px !important;
  border-bottom-right-radius: 50px !important;
}
.pagination-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.back-button, .menu-toggle {
  position: fixed;
  z-index: 2100;
  width: 45px;
  height: 45px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(22, 33, 62, 0.585);
  color: white;
  border: 1px solid rgba(255,255,255,0.3);
  transition: all 0.3s ease;
}
.back-button:hover {
  background-color: #79bd55;
  color: #0a1224;
}

@media (max-width: 991px) {
  header { padding: 8px 15px; }
  .header-container img { height: 50px; }
  header.shrink .header-container img { height: 40px; }

  .header-auth-card {
    gap: 8px;
    font-size: 0.85rem;
    padding: 8px;
  }
  .header-auth-card form button {
    font-size: 0.85rem;
    padding: .25rem .5rem;
  }

  .header-container {
    flex-direction: column;
    gap: 5px;
  }
  main.index-main {
    padding-top: 150px; 
  }
  
  .sidebar { left: -220px; }
  .sidebar.active { left: 0; }

  .menu-toggle { top: 15px; left: 15px; transition: opacity 0.3s ease, transform 0.3s ease; }
  .back-button { top: 15px; left: 70px; transition: transform 0.3s ease; }

  .sidebar.active ~ .menu-toggle { opacity: 0; pointer-events: none; }
  .sidebar.active ~ .back-button { transform: translateX(220px); }

  main.with-sidebar {
    margin-left: 0 !important;
    padding: 100px 15px;
  }

}


@media (max-width: 480px) {
  .header-container img { height: 45px; }
  header.shrink .header-container img { height: 35px; }
  
  .header-auth-card {
     flex-direction: column;
     gap: 5px;
     font-size: 0.8rem;
      align-items: center;
}

  .back-button { left: 60px; }
}

@media (min-width: 992px) {
  .back-button { top: 20px; left: 240px; }
  .menu-toggle { display: none; }
}
</style>

<style>
.modal-content {
  background-color: #192853;
  color: white;
  border: none;
  border-radius: 15px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.6);
}
.modal-header {
  border-bottom: 1px solid rgba(255,255,255,0.1);
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
}
.modal-body {
  background-color: #192853;
  color: #fff;
  font-size: 0.95rem;
}
.modal-footer {
  border-top: 1px solid rgba(255,255,255,0.1);
  background-color: #192853;
  border-bottom-left-radius: 15px;
  border-bottom-right-radius: 15px;
}
.modal-body .list-group-item {
  background-color: rgba(255,255,255,0.05);
  color: #fff;
  border: 1px solid rgba(255,255,255,0.1);
}
.modal-footer .btn {
  font-weight: 500;
  transition: all 0.2s ease-in-out;
}
.modal-footer .btn-danger:hover {
  background-color: #c82333;
  transform: scale(1.03);
}
.modal-footer .btn-success:hover {
  background-color: #5cb85c;
  transform: scale(1.03);
}
.modal-header.bg-danger {
  background: linear-gradient(135deg, #d9534f, #b52b27);
}
.modal-header.bg-success {
  background: linear-gradient(135deg, #28a745, #1e7e34);
}
.btn-close-white {
  filter: invert(1);
  opacity: 0.9;
}
.btn-close-white:hover {
  opacity: 1;
}
</style>

</head>
<body>

@if (Request::is(['/', 'login']))
<header>
  <div class="header-container">
    <img src="{{ asset('images/CTAlogo23.png') }}" alt="Logo CTA">
    
    @auth
    <div class="header-auth-card">
      <span><strong>Hola, {{ Auth::user()->nombre ?? 'Usuario' }}</strong> <span class="text-white-50">({{ Auth::user()->roles->first()?->nombre_rol ?? 'Sin rol' }})</span></span>
      <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-light rounded-pill">
          <i class="bi bi-power"></i> <span>Cerrar sesión</span>
        </button>
      </form>
    </div>
    @endauth
    </div>
</header>

<main class="container index-main">
  <div class="w-100">@yield('content')</div>
</main>

@else
<aside class="sidebar">
  <div class="sidebar-logo">
    <a href="{{ route('inicio') }}">
      <img src="{{ asset('images/CTAlogo21.png') }}" alt="Logo CTA">
    </a>
  </div>

  @auth
  <div class="sidebar-user-card">
      <p class="mb-0"><strong>Hola, {{ Auth::user()->nombre ?? 'Usuario' }}</strong></p>
      <p class="small text-white-50 mb-2">({{ Auth::user()->roles->first()?->nombre_rol ?? 'Sin rol' }})</p>
      
      <form action="{{ route('logout') }}" method="POST" class="d-grid">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-light rounded-pill sidebar-logout-btn">
              <i class="bi bi-power"></i> Cerrar sesión
          </button>
      </form>
  </div>
  @endauth
  
  <ul class="nav flex-column mt-2">
    <li class="nav-item"><a class="nav-link {{ Request::is('index') ? 'active': '' }}" href="{{ route('inicio') }}"><i class="bi bi-house me-2"></i> Inicio</a></li>
  </ul>


  @php
    $permisos_registros = [ 'crear_registros', 'ver_registros', 'buscar_registros', 'editar_registros', 'eliminar_registros', 'ver_registros_eliminados'];
  @endphp

  @canany($permisos_registros)
  <ul class="nav flex-column mt-3">
    
    @can('crear_registros')
    <li class="nav-item"><a class="nav-link {{ Request::is('registros/create') ? 'active' : '' }}" href="{{ route('registros.create') }}"><i class="bi bi-plus-circle me-2"></i> Insertar IP</a></li>
    @endcan

    @can('ver_registros')
    <li class="nav-item"><a class="nav-link {{ Request::is('registros/ocupadas') ? 'active' : '' }}" href="{{ route('registros.ocupadas') }}"><i class="bi bi-router me-2"></i> Ocupadas</a></li>
    <li class="nav-item"><a class="nav-link {{ Request::is('registros/disponibles') ? 'active' : '' }}" href="{{ route('registros.disponibles') }}"><i class="bi bi-wifi me-2"></i> Disponibles</a></li>
    @endcan

    @can('buscar_registros')
    <li class="nav-item"><a class="nav-link {{ Request::is('registros/buscar') ? 'active' : '' }}" href="{{ route('registros.buscar') }}"><i class="bi bi-search me-2"></i> Buscar</a></li>
    @endcan

    @can('editar_registros')
    <li class="nav-item"><a class="nav-link {{ Request::is('registros/modificar') ? 'active' : '' }}" href="{{ route('registros.modificar') }}"><i class="bi bi-pencil-square me-2"></i> Modificar</a></li>
    @endcan

    @can('eliminar_registros')
    <li class="nav-item"><a class="nav-link {{ Request::is('registros/eliminar') ? 'active' : '' }}" href="{{ route('registros.eliminar') }}"><i class="bi bi-trash me-2"></i> Eliminar</a></li>
    @endcan

    @can('ver_registros_eliminados')
    <li class="nav-item"><a class="nav-link {{ Request::is('registros/eliminadas') ? 'active' : '' }}" href="{{ route('registros.eliminadas') }}"><i class="bi bi-recycle me-2"></i> Eliminadas</a></li>
    @endcan
  </ul>
  @endcanany

 @php
    $permisos_avanzados = ['ver_usuarios', 'ver_roles', 'ver_permisos', 'ver_dependencias', 'ver_redes', 'ver_segmentos', 'ver_dispositivos'];
  @endphp
  @canany($permisos_avanzados)
  <ul class="nav flex-column mt-4">
    @can ('ver_usuarios')
    <li class="nav-item"><a class="nav-link {{ Request::is('usuarios*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}"><i class="bi bi-people-fill me-2"></i> Usuarios</a></li> 
    @endcan

    @can ('ver_roles')
    <li class="nav-item"><a class="nav-link {{ Request::is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}"><i class="bi bi-person-lock me-2"></i> Roles</a></li>
    @endcan

    @can ('ver_permisos')
    <li class="nav-item"><a class="nav-link {{ Request::is('permisos*') ? 'active' : '' }}" href="{{ url('/permisos') }}"><i class="bi bi-shield-lock me-2"></i> Permisos</a></li>
    @endcan

    @can ('ver_dependencias')
    <li class="nav-item"><a class="nav-link {{ Request::is('dependencias*') ? 'active' : '' }}" href="{{ route('dependencias.index') }}"><i class="bi bi-building me-2"></i> Dependencias</a></li>
    @endcan

    @can ('ver_redes')
    <li class="nav-item"><a class="nav-link {{ Request::is('redes*') ? 'active' : '' }}" href="{{ route('redes.index') }}"><i class="bi bi-diagram-3 me-2"></i> Redes</a></li>
    @endcan

    @can ('ver_segmentos')
    <li class="nav-item"><a class="nav-link {{ Request::is('segmentos*') ? 'active' : '' }}" href="{{ route('segmentos.index') }}"><i class="bi bi-diagram-3-fill me-2"></i> Segmentos</a></li>
    @endcan

    @can ('ver_dispositivos')
    <li class="nav-item"><a class="nav-link {{ Request::is('dispositivos*') ? 'active' : '' }}" href="{{ route('dispositivos.index') }}"><i class="bi bi-pc-display me-2"></i> Tipos de Dispositivos</a></li>
   @endcan
  </ul>
  @endcanany
</aside>

<button class="menu-toggle d-lg-none" id="menuToggle"><i class="bi bi-list"></i></button>
<button class="back-button" onclick="window.history.back()"><i class="bi bi-arrow-left"></i></button>

<main class="with-sidebar">
  <div class="container">@yield('content')</div>
</main>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('scroll', () => {
  const header = document.querySelector('header');
  if (header) window.scrollY > 50 ? header.classList.add('shrink') : header.classList.remove('shrink');
});

setTimeout(() => {
  document.querySelectorAll('.alert-auto-hide').forEach(alert => {
    alert.classList.remove('show');
    setTimeout(() => alert.remove(), 500);
  });
}, 4000);

const menu = document.getElementById('menuToggle');
const sidebar = document.querySelector('.sidebar');

menu?.addEventListener('click', () => {
  sidebar.classList.toggle('active');
  document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : 'auto';
});

document.addEventListener('click', (e) => {
   if (sidebar && sidebar.classList.contains('active') && 
   !sidebar.contains(e.target) && 
      menu && !menu.contains(e.target)) {
    sidebar.classList.remove('active');
    document.body.style.overflow = 'auto';
  }
});
</script>

@stack('js')
</body>
</html>