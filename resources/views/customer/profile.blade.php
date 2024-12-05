@extends('layout.master')

@section('title', 'My Account')

@section('styles')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
<style>
    .match-table {
        margin-top: 25px;
    }
</style>
@stop

@section('content')
<div class="container-xl px-4 mt-4">
    <!-- Account page navigation-->
    <nav class="nav nav-borders">
        <a class="nav-link active ms-0" href="{{ route('customer.profile') }}">Profile</a>
        <a class="nav-link" href="{{ route('customer.bill') }}">Billing</a>
        <a class="nav-link" href="https://www.bootdey.com/snippets/view/bs5-profile-security-page">Security</a>
        <a class="nav-link" href="https://www.bootdey.com/snippets/view/bs5-edit-notifications-page">Notifications</a>
    </nav>
    <hr class="mt-0 mb-4">
    <div class="row">

        <div class="col-xl-12">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                    <form action="{{ route('customer.changeProfile') }}" method="POST">
                        @csrf
                        <!-- Form Group (username)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputUsername">Username (how your name will appear to other users on the site)</label>
                            <input class="form-control" name="name" id="inputUsername" type="text" placeholder="Enter your username" value="{{ $customer->name }}">
                        </div>
                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (first name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputFirstName">Email</label>
                                <input class="form-control" name="email" id="inputFirstName" type="text" placeholder="Enter your first name" value="{{ $customer->email }}">
                            </div>
                            <!-- Form Group (last name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputLastName">Phone</label>
                                <input class="form-control" name="phone" id="inputLastName" type="text" placeholder="Enter your last name" value="{{ $customer->phone }}">
                            </div>
                        </div>
                        <!-- Form Row        -->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (organization name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputOrgName">Address</label>
                                <input class="form-control" name="address" id="inputOrgName" type="text" placeholder="Enter your organization name" value="{{ $customer->address   }}">
                            </div>
                            <!-- Form Group (location)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputLocation">Location</label>
                                <input class="form-control" id="inputLocation" type="text" placeholder="Enter your location" value="San Francisco, CA">
                            </div>
                        </div>
                        <!-- Form Group (email address)-->
                        <button class="btn btn-primary" type="submit">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop