@extends('layouts.app')

@section('page-title', __('Orders'))
@section('page-heading', __('Orders'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Orders')
</li>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>@lang('Order Details')</h4>
            </div>
            <div class="col-md-6 text-md-right">
                <!-- <a href="../../orders?order_id={{$order_id}}" class="btn btn-primary">
                    @lang('Back')
                </a> -->
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <th>@lang('Ref ID')</th>
                        <td>{{ $data['ref_id'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>@lang('Shipping label')</th>
                        <td><a href="{{$data['shipping_label'] ?? ''}}"
                                target="_blank">{{$data['shipping_label'] ?? ''}}</a></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center">@lang('Address')</th>
                    </tr>
                    <tr>
                        <th>@lang('Name')</th>
                        <td>{{ $data['address']['name'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>@lang('Phone')</th>
                        <td>{{ $data['address']['phone'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>@lang('Street 1')</th>
                        <td>{{ $data['address']['street1'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>@lang('Street 2')</th>
                        <td>{{ $data['address']['street2'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>@lang('City')</th>
                        <td>{{ $data['address']['city'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>@lang('State')</th>
                        <td>{{ $data['address']['state'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>@lang('Zip')</th>
                        <td>{{ $data['address']['zip'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>@lang('Country')</th>
                        <td>{{ $data['address']['country'] ?? '' }}</td>
                    </tr>
                    <!-- Tạo một nhóm dữ liệu mới cho phần "Item" -->

                    @if(count($data['line_items']) > 0)
                        @foreach ($data['line_items'] as $key => $item)
                            <tr>
                                <th colspan="2" class="text-center">{{'Item '.($key+1)}}</th>
                            </tr>
                            <tr>
                                <td>
                                    <!-- Chi tiết mặt hàng bên trái -->
                                    <ul>
                                        <li>@lang('Variant id'): {{$item['variant_id']}} - {{getVaritant($item['variant_id'])->style??""}} - {{getVaritant($item['variant_id'])->color??""}} - {{getVaritant($item['variant_id'])->size??""}}</li>
                                        <li>@lang('Product name'): {{$item['product_name']}}</li>
                                        <li>@lang('Quantity'): {{$item['quantity']}}</li>
                                        <li>@lang('Mockup front'): <a href="{{$item['mockup'] ?? ''}}"
                                                target="_blank">{{$item['mockup'] ?? ''}}</a></li>
                                        <li>@lang('Mockup back'): <a href="{{$item['mockup_back'] ?? ''}}"
                                                target="_blank">{{$item['mockup_back'] ?? ''}}</a></li>
                                    </ul>
                                </td>
                                <td>
                                    <!-- Các tệp in bên phải -->
                                    <ul>
                                        @foreach ($item['print_files'] as $meta)
                                            <li>{{$meta['key']}}: <a href="{{$meta['url']}}" target="_blank">{{$meta['url']}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection