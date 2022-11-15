@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>
                
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control name="email" required autocomplete="email" autofocus>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary" onclick="login()">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function login() {
    var email = document.getElementById('email');
    var password = document.getElementById('password');
    var token = $("meta[name='csrf-token']").attr("content");
    
    $.ajax({
        url: '/api/auth/login',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': `${token}`,
            'Accept':'application/json'
        },
        dataType: "json",
        data: {
            email: email.value,
            password: password.value
        },
        success: function (response) {
            if (response.status == 'success') {
                location.replace("../events");
                document.cookie = `bearerToken=${response.data.token}; path=/`;
                document.cookie = 'userName = ' + response.data.user.name;
            }
        },
        error:function (xhr, ajaxOptions, thrownError){
            if(xhr.status==401) {
                alert('Wrong Email or Password');
    
                email.value = '';
                password.value = '';
            }

            if(xhr.status==400) {
                alert('Please Login');
    
                email.value = '';
                password.value = '';
            }
        }
    });
}
</script>
@endsection
