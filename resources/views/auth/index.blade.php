@extends('app')
@section('title', 'Login')
@include('components.hnav')
@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center section-container" style="height: 90vh">
        @if(session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="login-container">
            <form method="POST" action="{{ route('login') }}" class="form-login ">
                @csrf
                <!-- Email input -->
                <div class="d-flex flex-column form-outline mt-2">
                    <label class="form-label" for="email">Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control"
                           value="{{old('email')}}"
                           required
                    />
                    @if($errors->any())
                        <span class="alert alert-danger">
                        @error('email')
                            {{ $message }}
                            @enderror
                    </span>
                    @endif
                </div>


                <!-- Password input -->
                <div class="d-flex flex-column form-outline mt-2">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required/>
                    @if($errors->any())
                    <span class="alert alert-danger">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </span>
                    @endif
                </div>

                <!-- Submit button -->
                <div class="d-flex flex-column form-outline align-items-center justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary btn-block mb-4 btn-submit" style="max-width: 50%">Sign in</button>
                </div>
            </form>
        </div>
    </div>
    @php
        $data = session()->all();
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    @endphp
@endsection
