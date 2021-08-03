<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                 <div class="card-header">Verify Your Email Address</div>
                   <div class="card-body">
                         <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>                   
                    <a href="http://127.0.0.1:8000/change-password/{{$token}}">Click Here</a>.
                </div>
            </div>
        </div>
    </div>
</div>
