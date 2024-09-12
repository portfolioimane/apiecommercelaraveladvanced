<li class="nav-item">
    <a class="nav-link position-relative" href="{{ url('/cart') }}">
        <i class="fas fa-shopping-cart"></i>
        @if($itemCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $itemCount }}
                <span class="visually-hidden">unread messages</span>
            </span>
        @endif
    </a>
</li>
