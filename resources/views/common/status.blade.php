@if(session('status'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true"><i class="fas fa-times"></i></span>
    </button>
    <span>{{ session('status')}}</span>
  </div>
@endif