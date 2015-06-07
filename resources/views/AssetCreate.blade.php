@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create New Word</div>

                <div class="panel-body">
                    <form method="POST" role="form" class="form-horizontal" action="/asset" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="col-xs-2">
                                <label>Image :</label>
                            </div>
                            <div class="col-xs-10">
                                <input type="file" class="form-control" name="image" required="">
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
