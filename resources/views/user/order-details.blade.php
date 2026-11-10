@extends('layouts.app')
@section('content')
    <style>
        .pt-90 {
            padding-top: 90px !important;
        }

        .my-account .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 40px;
            border-bottom: 1px solid;
            padding-bottom: 13px;
        }

        .my-account .wg-box {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 24px;
            margin-bottom: 30px;
            border-radius: 12px;
            background: var(--White, #fff);
            box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
        }

        .my-account .wg-box h5 {
            margin: 0;
        }

        /* Badges */
        .bg-success {
            background-color: #40c710 !important;
        }

        .bg-danger {
            background-color: #f44032 !important;
        }

        .bg-warning {
            background-color: #f5d700 !important;
            color: #000;
        }

        /* Shared table look */
        .order-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }

        .order-table th,
        .order-table td {
            padding: 12px 16px !important;
            font-size: 14px;
            line-height: 1.4;
            vertical-align: middle;
            border: 1px solid #e1e1e1 !important;
            background-image: none;
            box-shadow: none !important;
        }

        /* Label cells + header cells */
        .order-table th {
            background-color: #6a6e51 !important;
            color: #fff !important;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Value cells */
        .order-table td {
            background-color: #fff !important;
            color: #000 !important;
        }

        /* Items table */
        .items-table thead th {
            text-align: center;
        }

        .items-table thead th:first-child {
            text-align: left;
        }

        .items-table td.name-cell {
            min-width: 250px;
        }

        .pname {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .pname .thumb {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            border-radius: 10px;
            overflow: hidden;
        }

        .pname .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .pname .name a {
            color: #000;
            text-decoration: none;
        }

        .pname .name a:hover {
            text-decoration: underline;
        }

        /* Shipping address */
        .my-account__address-item__detail p {
            margin-bottom: 4px;
        }
    </style>

    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Order Details</h2>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>

                <div class="col-lg-10">

                    {{-- Order details --}}
                    <div class="wg-box">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <h5>Ordered Details</h5>
                            <a class="btn btn-sm btn-danger" href="{{ route('user.orders') }}">Back</a>
                        </div>

                        <div class="table-responsive">
                            @if (Session::has('status'))
                                <p class="alert alert-success">{{ Session::get('status') }}</p>
                            @endif
                            <table class="table order-table">
                                <tbody>
                                    <tr>
                                        <th>Order No</th>
                                        <td>{{ $order->id }}</td>
                                        <th>Mobile</th>
                                        <td>{{ $order->phone }}</td>
                                        <th>Zip Code</th>
                                        <td>{{ $order->zip }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order Date</th>
                                        <td>{{ $order->created_at }}</td>
                                        <th>Deliver Date</th>
                                        <td>{{ $order->deliver_date }}</td>
                                        <th>Canceled Date</th>
                                        <td>{{ $order->canceled_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order Status</th>
                                        <td colspan="5">
                                            @if ($order->status == 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @elseif($order->status == 'canceled')
                                                <span class="badge bg-danger">Canceled</span>
                                            @else
                                                <span class="badge bg-warning">Ordered</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Ordered items --}}
                    <div class="wg-box">
                        <h5>Ordered Items</h5>

                        <div class="table-responsive">
                            <table class="table order-table items-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Options</th>
                                        <th>Return Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $item)
                                        <tr>
                                            <td class="name-cell">
                                                <div class="pname">
                                                    <div class="thumb">
                                                        <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}"
                                                            alt="{{ $item->product->name }}">
                                                    </div>
                                                    <div class="name">
                                                        <a href="{{ route('shop.product.details', ['product_slug' => $item->product->slug]) }}"
                                                            target="_blank">{{ $item->product->name }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">${{ $item->price }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-center">{{ $item->product->SKU }}</td>
                                            <td class="text-center">{{ $item->product->category->name }}</td>
                                            <td class="text-center">{{ $item->product->brand->name }}</td>
                                            <td class="text-center">{{ $item->options }}</td>
                                            <td class="text-center">{{ $item->rstatus == 0 ? 'No' : 'Yes' }}</td>
                                            <td class="text-center">
                                                <div class="list-icon-function view-icon">
                                                    <div class="item eye">
                                                        <i class="icon-eye"></i>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="divider"></div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap wgp-pagination">
                            {{ $orderItems->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                    {{-- Shipping address --}}
                    <div class="wg-box">
                        <h5>Shipping Address</h5>
                        <div class="my-account__address-item">
                            <div class="my-account__address-item__detail">
                                <p>{{ $order->name }}</p>
                                <p>{{ $order->address }}</p>
                                <p>{{ $order->locality }}</p>
                                <p>{{ $order->city }}, {{ $order->country }}</p>
                                <p>{{ $order->landmark }}</p>
                                <p>{{ $order->zip }}</p>
                                <br>
                                <p>Mobile : {{ $order->phone }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Transactions --}}
                    <div class="wg-box">
                        <h5>Transactions</h5>
                        <div class="table-responsive">
                            <table class="table order-table">
                                <tbody>
                                    <tr>
                                        <th>Subtotal</th>
                                        <td>${{ $order->subtotal }}</td>
                                        <th>Tax</th>
                                        <td>${{ $order->tax }}</td>
                                        <th>Discount</th>
                                        <td>${{ $order->discount }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td>${{ $order->total }}</td>
                                        <th>Payment Mode</th>
                                        <td>{{ $transaction->mode }}</td>
                                        <th>Status</th>
                                        <td>
                                            @if ($transaction->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($transaction->status == 'declined')
                                                <span class="badge bg-danger">Declined</span>
                                            @elseif($transaction->status == 'refunded')
                                                <span class="badge bg-secondary">Refunded</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if ($order->status == 'ordered')
                            <div class="wg-box mt-5 text-right">
                                <form action="{{ route('user.order.cancel') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="order_id" value="{{ $order->id }}" />
                                    <button type="button" class="btn btn-danger cancel-order">Cancel Order</button>
                                </form>
                            </div>
                        @endif

                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.cancel-order').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Are you sure?",
                    text: "You want to cancel this order?",
                    type: "warning",
                    buttons: ["No", "Yes"],
                    confirmButtonColor: '#dc3545'
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
