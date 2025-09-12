<x-guest-layout>
    <div class="auth-challenge">
        <div class="container" id="container">

            {{-- SIGN UP --}}
            <div class="form-container sign-up-container">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <h1>Create account</h1>

                    <span>Use your email for registration</span>

                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" required />
                    @error('name')<small class="err">{{ $message }}</small>@enderror

                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required />
                    @error('email')<small class="err">{{ $message }}</small>@enderror

                    <input type="password" name="password" placeholder="Password" required />
                    @error('password')<small class="err">{{ $message }}</small>@enderror

                    <input type="password" name="password_confirmation" placeholder="Confirm password" required />

                    <button type="submit">Sign up</button>
                </form>
            </div>

            {{-- SIGN IN --}}
            <div class="form-container sign-in-container">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <h1>Sign in</h1>

                    <span>Use your account</span>

                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus />
                    @error('email')<small class="err">{{ $message }}</small>@enderror

                    <input type="password" name="password" placeholder="Password" required />
                    @error('password')<small class="err">{{ $message }}</small>@enderror

                    <div class="row">
                        <label class="remember">
                            <input type="checkbox" name="remember">
                            Keep me signed in
                        </label>

                        @if (Route::has('password.request'))
                            <a class="link" href="{{ route('password.request') }}">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit">Sign in</button>
                </form>
            </div>

            {{-- OVERLAY --}}
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>Welcome back!</h1>
                        <p>To keep connected, please sign in with your personal info.</p>
                        <button class="ghost" id="signIn" type="button">Sign in</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h1>Hello, friend!</h1>
                        <p>Enter your details and start your journey with us.</p>
                        <button class="ghost" id="signUp" type="button">Sign up</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toggle --}}
    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton?.addEventListener('click', () => container.classList.add('right-panel-active'));
        signInButton?.addEventListener('click', () => container.classList.remove('right-panel-active'));
    </script>
</x-guest-layout>
