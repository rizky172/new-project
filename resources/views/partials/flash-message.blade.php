@if (session('error') || session('success'))
<!-- Flash Message -->
<div class="page_title section-padding alert fade show">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @if (session('error'))
                <div class="notification_card">
                    <div class="notification failure alert-dismissible">
                        <div class="d-flex">
                            <div class="alert_icon me-3">
                                <span><i class="fa fa-times-circle"></i></span>
                            </div>
                            <p>Testing {{ session('error') }}</p>
                        </div>
                        <span class="close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="la la-close"></i>
                        </span>
                    </div>
                </div>
                @endif
                @if (session('success'))
                <div class="notification_card">
                    <div class="notification success alert-dismissible">
                        <div class="d-flex">
                            <div class="alert_icon me-3">
                                <span><i class="fa fa-check-circle"></i></span>
                            </div>
                            <p>{{ session('success') }}</p>
                        </div>
                        <span class="close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="la la-close"></i>
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- /Flash Message -->
@endif
