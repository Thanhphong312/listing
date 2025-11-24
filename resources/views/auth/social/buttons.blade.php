@if ($socialProviders)
    <ul class="list-inline">
        @if (in_array('facebook', $socialProviders))
            <li class="list-inline-item">
                <a href="{{ url('auth/facebook/login') }}" class="social-list-item bg-primary text-white border-primary">
                    <i class="mdi mdi-facebook"></i>
                </a>
            </li>
        @endif

        @if (in_array('twitter', $socialProviders))
            <li class="list-inline-item">
                <a href="{{ url('auth/twitter/login') }}" class="social-list-item bg-info text-white border-info">
                    <i class="mdi mdi-twitter"></i>
                </a>
            </li>
        @endif

        @if (in_array('google', $socialProviders))
            <li class="list-inline-item">
                <a href="{{ url('auth/google/login') }}" class="social-list-item bg-danger text-white border-danger">
                    <i class="mdi mdi-google"></i>
                </a>
            </li>
        @endif
    </ul>
@endif
