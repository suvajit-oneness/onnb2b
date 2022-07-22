@extends('layouts.app')

@section('content')
    <div class="b2blogin_wrap">
        <div class="register-logo">
            <a href="{{ url('/') }}"><img src="https://onninternational.com/img/footer-logo.png"></a>
        </div>
        <div class="b2blogin">
            <div class="b2blogin_header">
                {{ __('Login') }}
            </div>

            <div class="b2blogin_body">
                <form method="POST" action="{{ route('front.user.check') }}">
                    @csrf

                    <div class="form-group row">
                        <div class="col-12">
                            @if (session('message'))
                                <label for="mobile" class="form-label text-danger">{{ session('message') }}</label>
                            @endif
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17 2H7C5.89543 2 5 2.89543 5 4V20C5 21.1046 5.89543 22 7 22H17C18.1046 22 19 21.1046 19 20V4C19 2.89543 18.1046 2 17 2Z"
                                                stroke="#EB1C26" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12 18H12.01" stroke="#EB1C26" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                {{-- <input id="mobile" placeholder="Enter Mobile No" type="text"
                                    class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                                    value="{{ old('mobile') }}" required autocomplete="mobile" autofocus> --}}
                                {{-- <input placeholder="Enter Email" type="email"
                                    class="form-control @error('mobile') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autofocus> --}}
                                <input placeholder="Enter Mobile number" type="number" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autofocus onkeypress="return onlyNumberKey(event)">
                            </div>
                            @error('mobile') <p class="small text-danger">{{$message}}</p> @enderror
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17 2H7C5.89543 2 5 2.89543 5 4V20C5 21.1046 5.89543 22 7 22H17C18.1046 22 19 21.1046 19 20V4C19 2.89543 18.1046 2 17 2Z"
                                                stroke="#EB1C26" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12 18H12.01" stroke="#EB1C26" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <input placeholder="Enter password" type="password"
                                    class="form-control @error('mobile') is-invalid @enderror" name="password"
                                    value="{{ old('password') }}" required>
                            </div>
                            @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="text-center col-12">
                            <button type="submit" class="btn btn-primary loginbtn">
                                {{ __('Login') }}
                            </button>
                        </div>
                        <div class="sendotptext col-12 text-center">
                            <p class="mb-0">Enter your mobile number and password to login</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
