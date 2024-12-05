@extends('layout.master')

@section('title', 'Login')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<style>
   .Register {
    margin-bottom: 200px;
    margin-top: 70px;
   }
</style>
@stop

@section('content')
<div class="Register">
    <section class="vh-100" style="background-color: #ffff;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    <form action="{{ route('authentication.postRegister') }}" method="POST" class="mx-1 mx-md-4">
                                        @csrf
                                        <div class="d-flex flex-row align-items-center mb-4">
                        
                                            <div class="form-outline flex-fill mb-0">
                                                <input name="name" type="text" id="form3Example1c" class="form-control" placeholder="Your name"/>
                                                
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            
                                            <div class="form-outline flex-fill mb-0">
                                                <input name="email" type="email" required id="form3Example1c" class="form-control" placeholder="Email"/>
                                            
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                           
                                            <div class="form-outline flex-fill mb-0">
                                                <input name="phone" type="tel" required id="form3Example2c" class="form-control" placeholder="Phone"/>
                                                
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                           
                                            <div class="form-outline flex-fill mb-0">
                                                <input name="address" type="text" required id="form3Example3c" class="form-control" placeholder="Address"/>
                                                
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                          
                                            <div class="form-outline flex-fill mb-0">
                                                <input name="pass" type="password" id="form3Example4c" class="form-control" placeholder="Password"/>
                                               
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            
                                            <div class="form-outline flex-fill mb-0">
                                                <input name="re-pass" type="password" id="form3Example5c" class="form-control" placeholder="Confirm password"/>

                                            </div>
                                        </div>

                                        

                                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                            <button type="submit" class="btn btn-primary btn-lg">Register</button>
                                        </div>

                                    </form>

                                </div>
                                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp" class="img-fluid" alt="Sample image">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/color-modes.js"></script>
@stop