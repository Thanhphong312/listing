<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="#" onclick="createOrder()">Create order</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=&paymentStatus=pending_payment"
            id="ajaxPendingPaymentOrder">Pending Payment ({{$pendingPayment}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=new_order"
            id="ajaxNewOrder">New ({{$data['news']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=wrongsize"
            id="ajaxWrongsizeOrder">Wrongsize({{$data['wrongsize']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=fixed"
            id="ajaxFixedOrder">Fixed({{$data['fixed']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=printed"
            id="ajaxPrintedOrder">Printed({{$data['printed']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=label_printed"
            id="ajaxLabelPrintedOrder">Label Printed({{$data['labelprinted']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=reprint"
            id="ajaxReprintOrder">Reprint({{$data['reprint']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=onhold"
            id="ajaxOnholdOrder">Onhold({{$data['onhold']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=pressed"
            id="ajaxPressedOrder">Pressed({{$data['pressed']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=overdue"
            id="ajaxOverdieOrder">Overdue({{$data['overdue']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=priority"
            id="ajaxPriorityOrder">Priority({{$data['priority']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=oversize"
            id="ajaxOversizeOrder">Oversize({{$data['oversize']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=shipped"
            id="ajaxShippedOrder">Shipped({{$data['shipped']}})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=test_order"
            id="ajaxTestOrder">Test Order({{$data['testOrder']}})</a>
    </li>
</ul>