<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h1>Lista de Usuarios</h1>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user['id'] }}</td>
                    <td>{{ $user['code'] }}</td>
                    <td>{{ number_format($user['amount'], 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($user['date'])->format('d-m-Y') }}</td>
                    <td>
                        <button class="btn btn-primary btn-edit" data-toggle="modal" data-target="#editModal"
                                data-id="{{ $user['id'] }}" data-code="{{ $user['code'] }}"
                                data-amount="{{ $user['amount'] }}" data-date="{{ $user['date'] }}">Editar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" action="{{ route('updateUser') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="form-group">
                        <label for="editCode">Código</label>
                        <select class="form-control" id="editCode" name="code">
                            @foreach($codeOptions as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editAmount">Monto</label>
                        <input type="text" class="form-control" id="editAmount" name="amount" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editDate">Fecha</label>
                        <input type="text" class="form-control" id="editDate" name="date" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Evento al abrir el modal de editar
        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var code = button.data('code');
            var amount = button.data('amount');
            var date = button.data('date');

            // Formatear la fecha a dd-mm-yyyy y reemplazar / con -
            var formattedDate = new Date(date).toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).replace(/\//g, '-');

            // Llenar los campos del formulario en el modal
            $('#editId').val(id);
            $('#editCode').val(code);
            $('#editAmount').val(amount);
            $('#editDate').val(formattedDate);
        });
    });
</script>



</body>
</html>
