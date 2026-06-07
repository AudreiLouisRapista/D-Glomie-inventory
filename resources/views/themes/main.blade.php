    <!DOCTYPE html>
    <html lang="en">

    {{-- 1. HEAD Partial --}}
    @include('layout.partials.head')

    <body class="hold-transition sidebar-mini layout-fixed app-loading">

        <div class="wrapper">

            @include('layout.partials.navbar')
            @include('layout.partials.sidebar')

            {{-- 4. CONTENT WRAPPER: This is where the extending page content goes --}}
            <div class="content-wrapper">
                @yield('content_header')

                <section class="content">
                    <div class="container-fluid">
                        {{-- THIS IS THE MAIN CONTENT SECTION --}}
                        @yield('content')
                    </div>
                </section>
            </div>
            {{-- /.content-wrapper --}}



        </div>
        {{-- ./wrapper --}}

        {{-- 6. SCRIPT Partial --}}
        @include('layout.partials.script')

    </body>

    </html>
