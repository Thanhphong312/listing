<table class="table table-striped table-borderless" id="data-table-default">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="checkboxorders" onclick="checkboxOrdersPay()" disabled>
                            </th>
                            <th style="min-width: 200px">
                                Info
                            </th>
                            <th>Tracking ID / Label</th>
                            <th style="min-width: 120px">Fulfill Status</th>
                            <th>Pending Payment</th>
                            <!-- <th>Label Status</th>
                            <th>Design Status</th> -->
                            <th style="min-width: 600px">Product items</th>
                            <!-- <th>Weight</th> -->

                            <th style="min-width: 200px">Shipping address</th>
                            <th>Shipping Medthod</th>
                            <th>Shipping Service</th>


                            <!-- <th>Print Cost</th>
                            <th>Shipping Cost</th> -->
                            <th>Seller paid</th>
                            <th>Total Cost</th>
                            
                            <th style="min-width: 200px">Note</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="order-list">
                        @foreach($orders as $order)
                            <tr style="background-color: {{ $order->getBackgroupRecord() }};" data-id="{{$order->id}}" id="ajaxorder_{{$order->id}}">

                            </tr>
                        @endforeach
                    </tbody>
                </table>