@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Invoice Encoder')

{{-- 2. DEFINE CONTENT --}}
@section('content')

    <link rel="stylesheet" href="{{ asset('css/supplierList.css') }}">

    <div class="col-sm-6">
        <h1></h1>
    </div>

    <div class="col-12">

        {{-- Blue Hero Header --}}
        <div class="inv-hero">
            <div class="inv-hero-text">
                <h2><i class="fas fa-file-invoice mr-2"></i>Supplier Management</h2>
                <p>List of the supplier and information</p>
            </div>
            <div class="inv-hero-icon">
                <i class="bi bi-person-fill"></i>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Supplier List</h3>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-add-supplier shadow" data-toggle="modal"
                    data-target="#registerSupplierModal">
                    <i class="fas fa-plus"></i> Add Supplier
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example2" class="table table-bordered table-hover text-center">
                    <thead style="text-align: center; background-color: #f8fafc;">
                        <tr>

                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplier as $sup)
                            <tr>

                                <td>{{ $sup->supplier_name }}</td>
                                <td>{{ $sup->contact_number }}</td>
                                <td>{{ $sup->address }}</td>
                                <td></td>
                                <td><button type="button" class="btn btn-success"><i
                                            class="bi bi-pencil-square"></i></button>
                                    <button type="button" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- end .col-12 --}}


    {{-- REGETRATION SUPPLIER MODAL  --}}
    <div class="modal fade" id="registerSupplierModal" tabindex="-1" role="dialog"
        aria-labelledby="registerSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">

                @include('layout.partials.alerts')

                {{-- Modal Header --}}
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title font-weight-bold" id="registerSupplierModalLabel">
                        <i class="fas fa-box-open mr-2"></i> Register New Supplier
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-4">
                    <form id="registerSupplierForm" action="{{ route('save_supplier') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        {{-- Basic Information --}}
                        <div class="mb-4">
                            <p class="text-muted small font-weight-bold text-uppercase mb-3 border-bottom">
                                Basic Information
                            </p>
                            <div class="form-row">



                                {{-- Supplier Name --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Supplier Name</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="supplierName" id="supplierNameInput"
                                            class="form-control bg-light shadow-none" placeholder="Enter supplier name..."
                                            style="border-radius: 0 10px 10px 0; height: 45px;" required>
                                    </div>
                                </div>

                                {{-- Supplier Address --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Address</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="supplierAddress" id="supplierAddressInput"
                                            class="form-control bg-light shadow-none" placeholder="Enter Address"
                                            style="border-radius: 0 10px 10px 0; height: 45px;" required>
                                    </div>
                                </div>

                                {{-- Supplier Phone No. --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Phone Number</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="number" name="supplierPhone" class="form-control bg-light shadow-none"
                                            placeholder="Phone Number"
                                            oninput="if(this.value.length > 11) this.value = this.value.slice(0, 11);"
                                            style="border-radius: 0 10px 10px 0; height: 45px;" required>
                                    </div>
                                </div>


                            </div>
                        </div>


                        {{-- Footer Buttons --}}
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-light px-4 mr-2" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm">
                                Save
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection


@section('JS src')

@endsection
