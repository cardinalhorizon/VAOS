@extends('layouts.admin2')


@section('content')
    <div id="#updateCheck"></div>
    <div class="alert alert-danger" role="alert">
        <b>Admin Panel:</b> The Admin section of VAOS is currently in a overhaul. Some elements of the old admin panel are
        mixed in with the new. The overhaul will be addressed in Beta IV when the Material Crew interface is release
        candidate. If there are "game breaking" bugs, please don't hesitate to submit an issue on
        <a href="https://github.com/FSVAOS/VAOS/issues">GitHub</a>. Know however, anything that's not critical will not be
        addressed until the later releases. For more information regarding public beta releases, <a href="http://fsvaos.net/publicbeta">head to our public info page</a>. ~ Taylor Broad
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <h4 class="card-title mb-0">Flight Activity</h4>
                            <div class="small text-muted">November 2017</div>
                        </div>
                        <!--/.col-->
                        <div class="col-sm-7 d-none d-md-block">
                            <button type="button" class="btn btn-primary float-right">
                                <i class="icon-cloud-download"></i>
                            </button>
                            <div class="btn-group btn-group-toggle float-right mr-3" data-toggle="buttons">
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="options" id="option1" autocomplete="off"> Day
                                </label>
                                <label class="btn btn-outline-secondary active">
                                    <input type="radio" name="options" id="option2" autocomplete="off" checked=""> Month
                                </label>
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="options" id="option3" autocomplete="off"> Year
                                </label>
                            </div>
                        </div>
                        <!--/.col-->
                    </div>
                    <!--/.row-->
                    <div class="chart-wrapper" style="height:300px;margin-top:40px;"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas id="main-chart" class="chart chartjs-render-monitor" height="600" width="1894" style="display: block; height: 300px; width: 947px;"></canvas>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row text-center">
                        <div class="col-sm-12 col-md mb-sm-2 mb-0">
                            <div class="text-muted">Visits</div>
                            <strong>29.703 Users (40%)</strong>
                            <div class="progress progress-xs mt-2">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md mb-sm-2 mb-0">
                            <div class="text-muted">Unique</div>
                            <strong>24.093 Users (20%)</strong>
                            <div class="progress progress-xs mt-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md mb-sm-2 mb-0">
                            <div class="text-muted">Pageviews</div>
                            <strong>78.706 Views (60%)</strong>
                            <div class="progress progress-xs mt-2">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md mb-sm-2 mb-0">
                            <div class="text-muted">New Users</div>
                            <strong>22.123 Users (80%)</strong>
                            <div class="progress progress-xs mt-2">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md mb-sm-2 mb-0">
                            <div class="text-muted">Bounce Rate</div>
                            <strong>40.15%</strong>
                            <div class="progress progress-xs mt-2">
                                <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body p-3 d-flex align-items-center">
                    <i class="fas fa-cogs bg-primary p-3 font-2xl mr-3"></i>
                    <div>
                        <div class="text-value-sm text-primary">$1.999,50</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Income</div>
                    </div>
                </div>
                <div class="card-footer px-3 py-2">
                    <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="#">
                        <span class="small font-weight-bold">View More</span>
                        <i class="fas fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body p-3 d-flex align-items-center">
                    <i class="fa fa-cogs bg-primary p-3 font-2xl mr-3"></i>
                    <div>
                        <div class="text-value-sm text-primary">$1.999,50</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Income</div>
                    </div>
                </div>
                <div class="card-footer px-3 py-2">
                    <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="#">
                        <span class="small font-weight-bold">View More</span>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body p-3 d-flex align-items-center">
                    <i class="fa fa-cogs bg-primary p-3 font-2xl mr-3"></i>
                    <div>
                        <div class="text-value-sm text-primary">$1.999,50</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Income</div>
                    </div>
                </div>
                <div class="card-footer px-3 py-2">
                    <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="#">
                        <span class="small font-weight-bold">View More</span>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body p-3 d-flex align-items-center">
                    <i class="fa fa-cogs bg-primary p-3 font-2xl mr-3"></i>
                    <div>
                        <div class="text-value-sm text-primary">$1.999,50</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Income</div>
                    </div>
                </div>
                <div class="card-footer px-3 py-2">
                    <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="#">
                        <span class="small font-weight-bold">View More</span>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection