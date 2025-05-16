@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Learning Materials') }}</div>

                <div class="card-body">
                    <p>Your learning materials will be displayed here.</p>
                    
                    <!-- Add your materials listing logic here -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample row, replace with actual data -->
                                <tr>
                                    <td>Sample Material</td>
                                    <td>This is a sample material description</td>
                                    <td>{{ date('Y-m-d') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
