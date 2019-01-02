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
                <a href="/solicitors/create" class="navigation__link">Add Solicitor</a>
            </li>
            <li class="navigation__item">
                <a href="/solicitors/onboarding" class="navigation__link">Onboarding
                    <span id="onboard-count" class="badge badge-pill badge-danger ml-1">{{ $onboarding }}</span>
                </a>
            </li>
            <li class="navigation__item">
                <a href="/solicitors/" class="navigation__link">Solicitor Market Place</a>
            </li>
        </ul>
    </li>
    <li class="navigation__item">
        <a href="/staff/" class="navigation__link">
            <span class="navigation__icon far fa-trophy" aria-hidden="true"></span>
            Staff Performance
        </a>
    </li>
    <li class="navigation__item">
        <a href="/feedback/service" class="navigation__link">
            <span class="navigation__icon far fa-user-alt" aria-hidden="true"></span>
            Service Feedback
        </a>
    </li>
    <li class="navigation__item">
        <a href="/disbursements" class="navigation__link">
            <span class="navigation__icon_no_size fa-stack icon">
                <i class="far fa-circle fa-stack-2x"></i>
                <i class="fas fa-pound-sign fa-stack-1x"></i>
            </span>
            Disbursements
        </a>
    </li>
</ul>
