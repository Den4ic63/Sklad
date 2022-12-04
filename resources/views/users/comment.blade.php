@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>User Comments</h2>
            </div>
            <form action="/takecomment" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Comment</strong>
                            <input type="text" name="comment" class="form-control" placeholder="Leave a comment">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>№</th>
            <th>Email</th>
            <th>Comment</th>
        </tr>
        @foreach ($data as $key => $comments)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $comments->email }}</td>
                <td>{{ $comments->comment }}</td>
            </tr>
        @endforeach
    </table>


@endsection
