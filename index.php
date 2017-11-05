<!DOCTYPE html>
<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 05.11.2017
 * Time: 19:45
 */
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PAI projekt</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
<div class="container">

    <div class="row col-xs-12">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">WebSiteName</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#">Page 1</a></li>
                    <li><a href="#">Page 2</a></li>
                </ul>
                <form class="navbar-form navbar-left">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="row col-xs-12">
        <div class="col-md-7">
            <h1>Looking for a job? <br/>
                <small>Fuck that, be a nigga and do nothing</small>
            </h1>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>
                        <i class="fa fa-user"> Register now! </i>
                    </h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Remember me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">Sign in</button>
                                <br />
                            </div>
                        </div>
                        <caption>By clicking "Sign up", you agree to our terms of service and privacy policy. We’ll occasionally send you account related emails.</caption>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Find jobs around</h4>
            </div>
            <div class="panel-body">
                <table class="table table-hover">
                    <caption>Find some of the best job offers in our system</caption>
                    <thead>
                    <tr>
                        <th>Localization</th>
                        <th>Job offer</th>
                        <th>Prognozed payment</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Massetchusets</td>
                        <td>Housekeeping</td>
                        <td>$20.00</td>
                    </tr>
                    <tr>
                        <td>Myszyniec</td>
                        <td>Gangbang</td>
                        <td>$120.00</td>
                    </tr>
                    <tr>
                        <td>Ohio</td>
                        <td>Killing Trump</td>
                        <td>$152590.99</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
