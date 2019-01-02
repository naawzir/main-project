<div class="col-sm-24">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">The Conveyancing Partnership</a></li>

            @php
                $link = '';
            @endphp

            @if (isset($breadcrumbs))
                @if (array_keys($breadcrumbs) !== range(0, count($breadcrumbs) - 1))
                    @foreach ((array) $breadcrumbs as $key => $value)
                        @php
                            $link .= $key . '/';
                            $linkname = $value;
                        @endphp
                        <li class="breadcrumb-item"><a href="/{{ $link }}">{{ $linkname }}</a></li>
                    @endforeach
                @else
                    @foreach ((array) $breadcrumbs as $breadcrumb)
                        @php
                            $link .= $breadcrumb . '/';
                        @endphp
                        <li class="breadcrumb-item"><a href="/{{ $link }}">{{ ucfirst($breadcrumb) }}</a></li>
                    @endforeach
                @endif
            @endif
        </ol>
    </nav>
</div>
@include ('layouts.errors')
@include ('layouts.message')
