@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create New Word</div>

                    <div class="panel-body">
                        <form method="POST" role="form" class="form-horizontal" action="/word">
                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label>Name :</label>
                                </div>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" name="name" required="">
                                </div>
                            </div>
                            <hr>

                            <div>
                                <!--
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <button type="button" class="btn btn-default">New Language</button>
                                    </div>
                                </div>-->
                                <div class="form-group">
                                    <div class="col-xs-2">
                                        <label>Language :</label>
                                    </div>
                                    <div class="col-xs-10">
                                        <select class="form-control" name="meaning[0][language]" required="">
                                            <option disabled>Select Language</option>
                                            <option value="English">English</option>
                                            <option value="Vietnamese" selected>Vietnamese</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-2">
                                        <label>Meaning :</label>
                                    </div>
                                    <div class="col-xs-10">
                                        <textarea type="text" class="form-control" name="meaning[0][content]" style="resize: vertical" rows="10" placeholder="meaning here" required=""></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
