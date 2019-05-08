<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Server-list.cz API</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

    </div>

    <div class="body-content">

    <!-- NAVBAR -->
    <!-- END NAVBAR -->
    <!-- LEFT SIDEBAR -->
    <div id="sidebar-nav" class="sidebar">
        <div class="sidebar-scroll">
            <nav>
                <ul class="nav">
                    <li><a href="#" class=""><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>
                    <li>
                        <a href="#subPages" data-toggle="collapse" class="collapsed"><i class="lnr lnr-file-empty"></i> <span>User</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                        <div id="subPages" class="collapse ">
                            <ul class="nav">
                                <li><a href="#" class="">Register &nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Login&nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Relogin&nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Logout&nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Servers&nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Server/<id>&nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Notifications &nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Refresh Token &nbsp; <span class="label label-warning">PUT</span></a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#subPages2" data-toggle="collapse" class="collapsed"><i class="lnr lnr-file-empty"></i> <span>Services</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                        <div id="subPages2" class="collapse ">
                            <ul class="nav">
                                <li><a href="#" class="">Register &nbsp; <span class="label label-success">POST</span></a></li>
                                <li><a href="#" class="">Login&nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Relogin&nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Logout&nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Servers&nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Server/<id>&nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Notifications &nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Refresh Token &nbsp; <span class="label label-warning">PUT</span></a></li>
                                <li><a href="#" class="">Delete Token &nbsp; <span class="label label-danger">DELETE</span></a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#subPages3" data-toggle="collapse" class="collapsed"><i class="lnr lnr-file-empty"></i> <span>Servers</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                        <div id="subPages3" class="collapse ">
                            <ul class="nav">
                                <li><a href="#" class="">Check Token &nbsp; <span class="label label-success">GET</span></a></li>
                                <li><a href="#" class="">Generate Token &nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Refresh Token &nbsp; <span class="label label-warning">PUT</span></a></li>
                                <li><a href="#" class="">Delete Token &nbsp; <span class="label label-danger">DELETE</span></a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#subPages4" data-toggle="collapse" class="collapsed"><i class="lnr lnr-file-empty"></i> <span>Stats</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                        <div id="subPages4" class="collapse ">
                            <ul class="nav">
                                <li><a href="#" class="">Check Token &nbsp; <span class="label label-success">GET</span></a></li>
                                <li><a href="#" class="">Generate Token &nbsp; <span class="label label-primary">POST</span></a></li>
                                <li><a href="#" class="">Refresh Token &nbsp; <span class="label label-warning">PUT</span></a></li>
                                <li><a href="#" class="">Delete Token &nbsp; <span class="label label-danger">DELETE</span></a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- END LEFT SIDEBAR -->
    <!-- MAIN -->
    <div class="main">
        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="container-fluid" id="#">
                <h3 class="page-title">Generate Token</h3>
                <h4 class="page-title">Lorem ipsum dolor sit amet, <code>consectetur</code> adipiscing elit. Quisque nec venenatis est. Aliquam scelerisque bibendum volutpat. Donec vehicula tincidunt arcu, nec pellentesque neque dignissim eu. Duis a pretium sapien. Suspendisse efficitur eu metus ultrices suscipit. Mauris eget nulla a urna fermentum vulputate. Fusce ac leo rhoncus, convallis sem vel, blandit velit. Vestibulum pharetra dapibus nisi fermentum pretium. </h4>
                <div class="row">
                    <div class="col-md-7">
                        <!-- TABLE HOVER -->
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Request</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Position</th>
                                        <th>#</th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Authorization</td>
                                        <td><code>string</code></td>
                                        <td><code>Body</code></td>
                                        <td><code>Required</code></td>
                                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </td>
                                    </tr>
                                    <tr>
                                        <td>username</td>
                                        <td><code>string</code></td>
                                        <td><code>Body</code></td>
                                        <td><code>Required</code></td>
                                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque nec venenatis est. Aliquam scelerisque bibendum volutpat. Donec vehicula tincidunt arcu, nec pellentesque neque dignissim eu. </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END TABLE HOVER -->
                    </div>
                    <div class="col-md-5">
                        <!-- TABLE HOVER -->
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Response</h3>
                            </div>
                            <div class="panel-body">
								<pre>{
                                    "status": true,
                                    "result_code": 200,
                                    "message": "Success!",
                                    "values": {
                                        "name": "Kiddy",
                                        "email": "kiddydhana@gmail.com",
                                        "token": "9WUzKE7kCI1vSuQAbrmOwc2m2dk1NbPR",
                                        "account_status": "1"
                                    }
                                }</pre>
                            </div>
                        </div>
                        <!-- END TABLE HOVER -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END MAIN CONTENT -->
    </div>
    <!-- END MAIN -->
    <div class="clearfix"></div>
    </div>
</div>
<!-- END WRAPPER -->
<!-- Javascript -->
<script>
    $(document).ready(function() {
        $('pre code').each(function(i, block) {
            hljs.highlightBlock(block);
        });
    });
</script>
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/scripts/klorofil-common.js"></script>

