@extends('layouts.app')

@section('title', 'Eliminar IP')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card p-4 shadow-lg rounded-4 border-0">
            <h3 class="text-center mb-4 text-danger">
                <i class="bi bi-trash3 me-1"></i> Eliminar IP
            </h3>

            <div id="errorAlert" class="alert alert-danger d-none rounded-pill text-center"></div>

            <form id="buscarForm" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Dirección IP</label>
                    <input type="text" id="ipInput" name="ip"
                           class="form-control rounded-pill"
                           placeholder="Ej: 192.168.1.10" required
                           value="{{ old('ip', request()->get('ip')) }}">
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-danger rounded-pill px-4" id="btnBuscar">
                        <i class="bi bi-trash3 me-1"></i> Eliminar
                    </button>
                    <a href="{{ route('registros.ocupadas') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-card-list"></i> Ver Listado
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmarEliminacion" tabindex="-1" aria-labelledby="modalConfirmarEliminacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title" id="modalConfirmarEliminacionLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="fw-bold text">¿Deseas mover este registro a la papelera (Eliminadas)?</p>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>IP:</strong> <span id="detalleIp"></span></li>
                    <li class="list-group-item"><strong>MAC:</strong> <span id="detalleMac"></span></li>
                    <li class="list-group-item"><strong>Descripción:</strong> <span id="detalleDesc"></span></li>
                    <li class="list-group-item"><strong>Tipo de Dispositivo:</strong> <span id="detalleTipo"></span></li>
                    <li class="list-group-item"><strong>Responsable:</strong> <span id="detalleResp"></span></li>
                    <li class="list-group-item"><strong>Dependencia:</strong> <span id="detalleDep"></span></li>
                </ul>
            </div>

            <div class="modal-footer">
                <form id="formEliminar" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill">
                        <i class="bi bi-trash3"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const buscarForm = document.getElementById('buscarForm');
    const errorAlert = document.getElementById('errorAlert');
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminacion'));
    const formEliminar = document.getElementById('formEliminar');

    buscarForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorAlert.classList.add('d-none');

        const ip = document.getElementById('ipInput').value.trim();
        if (!ip) return;

        const response = await fetch("{{ route('registros.eliminar.post') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ ip })
        });

        const data = await response.json();

        if (response.ok) {
            document.getElementById('detalleIp').textContent = data.ip;
            document.getElementById('detalleMac').textContent = data.mac; 
            document.getElementById('detalleDesc').textContent = data.descripcion ?? '—';
            document.getElementById('detalleTipo').textContent = data.tipo;
            document.getElementById('detalleResp').textContent = data.responsable;
            document.getElementById('detalleDep').textContent = data.dependencia;
            formEliminar.action = `/registros/${data.id}/destroy`;
            modal.show();
        } else {
            errorAlert.textContent = data.error ?? 'Ocurrió un error.';
            errorAlert.classList.remove('d-none');
        }
    });
});
</script>
@endpush
