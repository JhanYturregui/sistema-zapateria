<div class="alertas">
    @if ( $mensaje = session('mensaje') )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong></strong> {{ $mensaje }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
    @endif
</div>