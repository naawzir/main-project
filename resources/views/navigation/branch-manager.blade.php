<ul class="navigation">
    <li class="navigation__item">
        <a href="/" class="navigation__link">
            <span class="navigation__icon far fa-home fa-fw" aria-hidden="true"></span>
            Home
        </a>
    </li>
    <li class="navigation__item">
        <a href="/cases" class="navigation__link">
            <span class="navigation__icon far fa-file-alt fa-fw" aria-hidden="true"></span>
            Cases
        </a>
    </li>
    <li class="navigation__item">
        <a href="/cases/create" class="navigation__link">
            <span class="navigation__icon far fa-file-plus fa-fw" aria-hidden="true"></span>
            Add a Case
        </a>
    </li>
    <li class="navigation__item navigation__item--with-submenu">
        <a class="navigation__link">
            <span class="navigation__icon far fa-balance-scale fa-fw" aria-hidden="true"></span>
            Solicitors
        </a>
        <ul class="navigation">
            <li class="navigation__item">
                <a href="{{ route('panel') }}" class="navigation__link">My Solicitors</a>
            </li>
            <li class="navigation__item">
                <a href="/solicitors/" class="navigation__link">Solicitor Market Place</a>
            </li>
        </ul>
    </li>
    <li class="navigation__item">
        <a href="/feedback/service">
            <span class="navigation__icon far fa-user-alt fa-fw" aria-hidden="true"></span>
            Service Feedback
        </a>
    </li>
    <li class="navigation__item">
        <a href="/branch/">
            <span class="navigation__icon far fa-trophy fa-fw" aria-hidden="true"></span>
            Branch &amp; Staff Performance
        </a>
    </li>
</ul>
