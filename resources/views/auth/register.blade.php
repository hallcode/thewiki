@extends('layouts.app')

@section('content')
    <header>
        <h1>Register</h1>
        <nav></nav>
    </header>

    @if (config('security.registration.open'))
        <p>
            Use this form to register a new account.
        </p>

        @if (config('security.registration.approval'))
            <div class="alert alert-warning">
                <p>
                    <strong>This site requires approval.</strong>
                </p>
                <p>
                    Please note that this wiki requires new users are approved before they can do certain things. This may
                    include viewing or editing pages.
                </p>
            </div>
        @endif

        <form role="form" method="POST" action="{{ route('register') }}" class="narrow">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="control-label">Username</label>
                <p>
                    Please pick a unique username.
                </p>
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required
                       autofocus>

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="control-label">E-Mail Address</label>

                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="control-label">Password</label>
                <p>
                    Please enter a strong password, that means numbers, letters, and preferably symbols and capital letters
                    as well. No less that eight (8) characters.
                </p>
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="password-confirm" class="control-label">Confirm Password</label>

                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label class="control-label">Confirm your humanity</label>

                {!! app('captcha')->display() !!}

                @if ($errors->has('captcha'))
                    <span class="help-block">
                        <strong>{{ $errors->first('captcha') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Register
                </button>
            </div>
        </form>
    @else
        <div class="alert alert-danger">
            <p>
                <strong>This site is not currently accepting new users.</strong>
            </p>
            <p>
                Unfortunately we are not currently open to new users. This may change at some time in the future so
                remember to
                check back again or get in touch.
            </p>
        </div>
    @endif
@endsection
