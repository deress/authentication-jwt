@extends('layouts.main')
@section('container')
    <div class="text-center mt-5">
        <h1>Login Berhasil</h1>
            <p>ini halaman login</p>

            <form action="/api/auth/logout" method="post">
                @csrf
                <button type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
    </div>
    
    

@endsection