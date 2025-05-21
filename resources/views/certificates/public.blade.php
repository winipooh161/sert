<!DOCTYPE html>
<html lang="ru">
<head>
    @include('certificates.partials.meta')
    @include('certificates.partials.styles')
</head>
<body>
    <div class="main-container" id="mainContainer">
        @include('certificates.partials.cover-section')
        @include('certificates.partials.certificate-section')
    </div>
    
    @include('certificates.partials.modals')
    @include('certificates.partials.animation-container')
    @include('certificates.partials.scripts')
</body>
</html>


