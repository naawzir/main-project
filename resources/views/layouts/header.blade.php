<div class="fake-header">

	<ul class="iap-fake-menu">
		<li><a href="">Agents</a></li>
		<li><a href="">Staff</a></li>
		<li><a href="">My Account</a></li>
		<li><a href="">Logout</a></li>
	</ul>

</div>

<style>
	.fake-header {
		display: flex;
		justify-content: flex-end;
		height: auto;
	}

	.iap-fake-menu {
		display: flex;
		list-style-type: none;
		margin-bottom: 0;
		padding: 1rem 0;
	}

	.iap-fake-menu li + li {
		margin-left: 0.5rem;
	}

	.iap-fake-menu a {
		color: white;
		font-weight: bold;
		padding: 1rem 0.5rem;
		text-decoration: underline;
	}
</style>
