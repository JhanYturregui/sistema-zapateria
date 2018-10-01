@if ($errors->has('username'))
    <div class="">

		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{ $errors->first('username') }}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		
    </div>
    
@elseif($errors->has('password'))
    <div class="">

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first('password') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
    </div>

@endif
