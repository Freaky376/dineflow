@extends('layouts.admin_parentLO')
 
@section('content')
<main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                        <div class="row">
                        <div class="row">
                        <div class="row justify-content-center">
    <!-- Number of Tenants Card -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card" style="background-color: #f0f0f0; border-radius: 10px;">
            <div class="card-body text-center">
                <h5 class="card-title">Number of Tenants</h5>
                <h3 class="card-text"> <!-- Add dynamic data here for the number of tenants --> 120</h3>
                <!-- Icon representing tenants -->
                <i class="fas fa-users fa-3x mt-3" style="color: #007bff;"></i>
            </div>
            <div class="card-footer text-center">
                <a class="small text-muted stretched-link" href="#">View Details</a>
            </div>
        </div>
    </div>

    <!-- Most Availed Service Card -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card" style="background-color: #f0f0f0; border-radius: 10px;">
            <div class="card-body text-center">
                <h5 class="card-title">Most Availed Service</h5>
                <h3 class="card-text"> <!-- Add dynamic data here for most availed service --> Event Management</h3>
                <!-- Icon representing event management -->
                <i class="fas fa-calendar-check fa-3x mt-3" style="color: #28a745;"></i>
            </div>
            <div class="card-footer text-center">
                <a class="small text-muted stretched-link" href="#">View Details</a>
            </div>
        </div>
    </div>

    <!-- Least Availed Service Card -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card" style="background-color: #f0f0f0; border-radius: 10px;">
            <div class="card-body text-center">
                <h5 class="card-title">Least Availed Service</h5>
                <h3 class="card-text"> <!-- Add dynamic data here for least availed service --> Accessibility Info Hub</h3>
                <!-- Icon representing accessibility information hub -->
                <i class="fas fa-info-circle fa-3x mt-3" style="color: #17a2b8;"></i>
            </div>
            <div class="card-footer text-center">
                <a class="small text-muted stretched-link" href="#">View Details</a>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center mt-4">
    <!-- City Attraction Administration Card -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card" style="background-color: #f0f0f0; border-radius: 10px;">
            <div class="card-body text-center">
                <h5 class="card-title">City Attraction Administration</h5>
                <h3 class="card-text"> <!-- Add dynamic data here for the number of times availed --> 150</h3>
                <!-- Icon representing City Attraction Administration -->
                <i class="fas fa-city fa-3x mt-3" style="color: #6c757d;"></i>
            </div>
            <div class="card-footer text-center">
                <a class="small text-muted stretched-link" href="#">View Details</a>
            </div>
        </div>
    </div>

    <!-- Accessibility Information Hub Card -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card" style="background-color: #f0f0f0; border-radius: 10px;">
            <div class="card-body text-center">
                <h5 class="card-title">Accessibility Info Hub</h5>
                <h3 class="card-text"> <!-- Add dynamic data here for the number of times availed --> 80</h3>
                <!-- Icon representing Accessibility Info Hub -->
                <i class="fas fa-info-circle fa-3x mt-3" style="color: #17a2b8;"></i>
            </div>
            <div class="card-footer text-center">
                <a class="small text-muted stretched-link" href="#">View Details</a>
            </div>
        </div>
    </div>

    <!-- Event Management Card -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card" style="background-color: #f0f0f0; border-radius: 10px;">
            <div class="card-body text-center">
                <h5 class="card-title">Event Management</h5>
                <h3 class="card-text"> <!-- Add dynamic data here for the number of times availed --> 200</h3>
                <!-- Icon representing Event Management -->
                <i class="fas fa-calendar-check fa-3x mt-3" style="color: #28a745;"></i>
            </div>
            <div class="card-footer text-center">
                <a class="small text-muted stretched-link" href="#">View Details</a>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
</div>

</div>

                        </div>
                    </div>

                    </div>
                </main>
@endsection