<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">今日成交</h3>
                （订单状态为<span class="label label-primary">正常</span>的所有订单）
                <div class="box-tools pull-right">
                    <span class="label label-info">平均消费金额￥{{$overview['today']['avg']}}</span>
                </div>
            </div>
            <div class="box-body" style="display: block;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$overview['today']['count']}}</h3>
                                <p>订单成交量</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{$overview['today']['money']}}</h3>
                                <p>消费总金额</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cny"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">昨日成交</h3>
                （订单状态为<span class="label label-primary">正常</span>的所有订单）
                <div class="box-tools pull-right">
                    <span class="label label-info">平均消费金额￥{{$overview['yesterday']['avg']}}</span>
                </div>
            </div>
            <div class="box-body" style="display: block;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$overview['yesterday']['count']}}</h3>
                                <p>订单成交量</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{$overview['yesterday']['money']}}</h3>
                                <p>消费总金额</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cny"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">近七日成交</h3>
                （订单状态为<span class="label label-primary">正常</span>的所有订单）
                <div class="box-tools pull-right">
                    <span class="label label-info">平均消费金额￥{{$overview['week']['avg']}}</span>
                </div>
            </div>
            <div class="box-body" style="display: block;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$overview['week']['count']}}</h3>
                                <p>订单成交量</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{$overview['week']['money']}}</h3>
                                <p>消费总金额</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cny"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">近一个月成交</h3>
                （订单状态为<span class="label label-primary">正常</span>的所有订单）
                <div class="box-tools pull-right">
                    <span class="label label-info">平均消费金额￥{{$overview['month']['avg']}}</span>
                </div>
            </div>
            <div class="box-body" style="display: block;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$overview['month']['count']}}</h3>
                                <p>订单成交量</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{$overview['month']['money']}}</h3>
                                <p>消费总金额</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cny"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">交易成功</h3>
                （订单状态为<span class="label label-primary">正常</span>的所有订单）
                <div class="box-tools pull-right">
                    <span class="label label-info">平均消费金额￥{{$overview['success']['avg']}}</span>
                </div>
            </div>
            <div class="box-body" style="display: block;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$overview['success']['count']}}</h3>
                                <p>订单成交量</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{$overview['success']['money']}}</h3>
                                <p>消费总金额</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cny"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">交易失败</h3>
                订单状态为<span class="label label-danger">关闭</span>的所有订单）
                <div class="box-tools pull-right">
                    <span class="label label-info">平均消费金额￥{{$overview['fail']['avg']}}</span>
                </div>
            </div>
            <div class="box-body" style="display: block;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$overview['fail']['count']}}</h3>
                                <p>订单成交量</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{$overview['fail']['money']}}</h3>
                                <p>消费总金额</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cny"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">全部成交</h3>
                （订单状态为<span class="label label-primary">正常</span>和<span class="label label-danger">关闭</span>的所有订单）
                <div class="box-tools pull-right">
                    <span class="label label-info">平均消费金额￥{{$overview['all']['avg']}}</span>
                </div>
            </div>
            <div class="box-body" style="display: block;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$overview['all']['count']}}</h3>
                                <p>订单成交量</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{$overview['all']['money']}}</h3>
                                <p>消费总金额</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cny"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>