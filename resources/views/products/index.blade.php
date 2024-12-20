@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Product Management</h4>
                    </div>

                    <div class="card-body">
                        <form id="productForm" class="mb-4">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Quantity in stock</label>
                                        <input type="number" name="quantity" class="form-control" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Unit price</label>
                                        <input type="number" name="price" class="form-control" min="0" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary mt-4">Add</button>
                                </div>
                            </div>
                        </form>

                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product name</th>
                                    <th>Quantity</th>
                                    <th>Unit price</th>
                                    <th>Add date</th>
                                    <th>Total value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productsTable">
                                @foreach($products as $product)
                                    <tr data-id="{{ $product['id'] }}">
                                        <td class="name">{{ $product['name'] }}</td>
                                        <td class="quantity">{{ $product['quantity'] }}</td>
                                        <td class="price">{{ number_format($product['price'], 2) }}</td>
                                        <td>{{ $product['submitted_at'] }}</td>
                                        <td class="total-value">{{ number_format($product['total_value'], 2) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                                            <button class="btn btn-sm btn-success save-btn d-none">Save</button>
                                            <button class="btn btn-sm btn-danger cancel-btn d-none">Cancel</button>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="table-info">
                                    <td colspan="4" class="text-end fw-bold">Grand total:</td>
                                    <td colspan="2" class="fw-bold">{{ number_format($total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection