@if($role != 'Seller')
<div class="row mb-xl-2 mb-xxl-3">
    
        <div class="col-sm-4">
            <a class="element-box el-tablo centered trend-in-corner padded bold-label"
                href="/orders?nameSearch=&filterStock=&filterLabel=Label+Tracking&filterFulfill=new_order">
                <div class="values">
                    {{Setting('new_order') ?? 0}}
                </div>
                <div class="labels">
                    New orders
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="#">
                <div class="values">
                    {{Setting('production') ?? 0}}
                </div>
                <div class="labels">
                    Production
                </div>
            </a>
        </div>

        <div class="col-sm-4">
            <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="#">
                <div class="values">
                    <!-- {{Setting('order_shipped_today') ?? 0}}/{{Setting('model_shipped_today') ?? 0}} -->
                    {{Setting('order_shipped_today') ?? 0}}
                </div>
                <div class="labels">
                    Shipped today
                </div>

            </a>
        </div>
        <div class="col-sm-4">
            <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="#">
                <div class="values">
                    {{--Setting('avg_delivery_time') ?? 0--}}
                    {{$orderOverTwoDays}}
                </div>
                <div class="labels">
                    Order over 2 days
                </div>

            </a>
        </div>
        <div class="col-sm-4">
            <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="#">
                <div class="values">
                    {{Setting('avg_production_time') ?? 0}}
                </div>
                <div class="labels">
                    Avg production time
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="#">
                <div class="values">
                    <!-- {{Setting('order_shipped_yesterday') ?? 0}}/{{Setting('model_shipped_yesterday') ?? 0}} -->
                    {{Setting('order_shipped_yesterday') ?? 0}}
                </div>
                <div class="labels">
                    Shipped yesterday
                </div>
            </a>
        </div>
   
</div>
@endif
<div class="row mb-xl-2 mb-xxl-3">
    @if($role == 'Seller')
        <div class="col-sm-6">
            <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="#">
                <div class="values">
                    {{$production}}
                </div>
                <div class="labels">
                    Production
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="#">
                <div class="values">
                    {{$new_order}}
                </div>
                <div class="labels">
                    New orders
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="#">
                <div class="values">
                    {{$on_hold}}
                </div>
                <div class="labels">
                    On Hold
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="#">
                <div class="values">
                    {{$pending_payment}}
                </div>
                <div class="labels">
                    Pending Payment
                </div>
            </a>
        </div>
    @endif
</div>
<style>
    .values {
        font-size: 2.43rem;
        font-weight: 500;
        font-family: "Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        letter-spacing: 1px;
        line-height: 1.2;
        display: inline-block;
        vertical-align: middle;
    }
    .labels {
        text-transform: none;
        font-size: 0.99rem;
        letter-spacing: 0px;
        transition: all 0.25s ease;
        color: rgba(0, 0, 0, 0.4);
    }
    
    @media screen and (max-width: 1200px) {
        .values {
            font-size: 2.43rem;
            font-weight: 500;
            font-family: "Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            letter-spacing: 1px;
            line-height: 1.2;
            display: inline-block;
            vertical-align: middle;
        }
        .labels {
            text-transform: none;
            font-size: 0.99rem;
            letter-spacing: 0px;
            transition: all 0.25s ease;
            color: rgba(0, 0, 0, 0.4);
        }
    }

    @media screen and (max-width: 992px) {
        .values {
            font-size: 2.03rem;
            font-weight: 500;
            font-family: "Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            letter-spacing: 1px;
            line-height: 1.2;
            display: inline-block;
            vertical-align: middle;
        }
        .labels {
            text-transform: none;
            font-size: 0.77rem;
            letter-spacing: 0px;
            transition: all 0.25s ease;
            color: rgba(0, 0, 0, 0.4);
        }
    }

    @media screen and (max-width: 768px) {
        .values {
            font-size: 1.63rem;
            font-weight: 500;
            font-family: "Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            letter-spacing: 1px;
            line-height: 1.2;
            display: inline-block;
            vertical-align: middle;
        }
        .labels {
            text-transform: none;
            font-size: 0.55rem;
            letter-spacing: 0px;
            transition: all 0.25s ease;
            color: rgba(0, 0, 0, 0.4);
        }
    }

    @media screen and (max-width: 575px) {
        .values {
            font-size: 2.43rem;
            font-weight: 500;
            font-family: "Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            letter-spacing: 1px;
            line-height: 1.2;
            display: inline-block;
            vertical-align: middle;
        }
        .labels {
            text-transform: none;
            font-size: 0.99rem;
            letter-spacing: 0px;
            transition: all 0.25s ease;
            color: rgba(0, 0, 0, 0.4);
        }
    }
    
    
</style>