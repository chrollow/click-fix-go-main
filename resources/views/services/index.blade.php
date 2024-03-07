@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">We Repair and Clean It All!</h1>
    <p class="text-center mb-5">Choose your service type to explore our services:</p>
    <div class="row">
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="purple card help-button rounded-4 p-2">
                <div class="card-body">
                    <h3 class="card-title">Add New Service</h3>
                    <p class="card-text">Expand your scope by adding a new service</p>
                    <a href="{{ route('services.create') }}" class="btn btn-primary">Add New Service</a>
                </div>
            </div>
        </div>
        @foreach ($services as $service)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="green card help-button rounded-4 p-2">
                <div class="card-body">
                    <h3 class="card-title">{{ $service->service_type }}</h3>
                    <p class="card-text">Expert repairs for all major brands and models.</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
