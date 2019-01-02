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
	<li class="navigation__item navigation__item--with-submenu">
		<a class="navigation__link">
			<span class="navigation__icon far fa-balance-scale fa-fw" aria-hidden="true"></span>
			Solicitors
		</a>
		<ul class="navigation">
			<li class="navigation__item">
				<a href="/solicitors/onboarding" class="navigation__link">Onboarding
					<span id="onboard-count" class="badge badge-pill badge-danger ml-1">{{ $onboarding }}</span>
				</a>
			</li>
			<li class="navigation__item">
				<a href="/solicitors/" class="navigation__link">Solicitor Market Place</a>
			</li>
			<li class="navigation__item">
				<a href="/solicitors/performance" class="navigation__link">Solicitor Performance</a>
			</li>
		</ul>
	</li>
	<li class="navigation__item">
		<a href="/disbursements" class="navigation__link">
			<span class="navigation__icon far fa-pound-sign fa-fw" aria-hidden="true"></span>
			Disbursements
		</a>
	</li>
	<li class="navigation__item">
		<a href="/milestones/conveyancing" class="navigation__link">
			<span class="navigation__icon far fa-chart-line fa-fw" aria-hidden="true"></span>
			Milestones
		</a>
	</li>
	<li class="navigation__item">
		<a href="/feedback/service" class="navigation__link">
			<span class="navigation__icon far fa-user-alt fa-fw" aria-hidden="true"></span>
			Service Feedback
		</a>
	</li>
	<li class="navigation__item">
		<a href="/cases/update-requests" class="navigation__link">
			<span class="navigation__icon far fa-paper-plane fa-fw" aria-hidden="true"></span>
			Agent Update Requests
		</a>
	</li>
</ul>
