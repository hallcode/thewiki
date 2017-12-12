@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">Users</a>
@endsection

@section('rightTabs')
    <a href="{{ route('resource', ['namespace' => 'Special', "reference" => "home"]) }}" class="active tab">Read</a>
    <a href="{{ route('home.edit') }}" class="tab">Edit</a>
    <a href="{{ route('resource', ['namespace' => 'Special', "reference" => "home"]) }}" class="tab">View history</a>
@endsection

@section('content')
    <header>
        <h1>Users</h1>
    </header>

    <div class="main">
        <p>
            This page shows a list of users.
        </p>

        <p>
        <table>
            <thead>
            <tr>
                <th>Username</th>
                <th>Role(s)</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="badge badge-dark">{{ strtoupper($role->type) }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="#!">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </p>

        <p>
            <a href="#!" class="btn btn-primary">Create User</a>
        </p>
    </div>
@endsection
