@php
    $isRtl = $isRtl ?? app()->getLocale() === 'ar';
@endphp
@if ($isRtl)
    <style>
        html[dir="rtl"] body {
            direction: rtl;
            text-align: right;
        }

        html[dir="rtl"] {
            --tw-space-x-reverse: 1;
        }

        html[dir="rtl"] .text-left {
            text-align: right;
        }

        html[dir="rtl"] .ml-0 {
            margin-left: 0;
            margin-right: 0;
        }

        html[dir="rtl"] .ml-1 {
            margin-left: 0;
            margin-right: 0.25rem;
        }

        html[dir="rtl"] .ml-2 {
            margin-left: 0;
            margin-right: 0.5rem;
        }

        html[dir="rtl"] .ml-6 {
            margin-left: 0;
            margin-right: 1.5rem;
        }

        html[dir="rtl"] .ml-8 {
            margin-left: 0;
            margin-right: 2rem;
        }

        html[dir="rtl"] .mr-1 {
            margin-right: 0;
            margin-left: 0.25rem;
        }

        html[dir="rtl"] .mr-2 {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        html[dir="rtl"] .mr-3 {
            margin-right: 0;
            margin-left: 0.75rem;
        }

        html[dir="rtl"] .pl-5 {
            padding-left: 0;
            padding-right: 1.25rem;
        }

        html[dir="rtl"] .left-0 {
            left: auto;
            right: 0;
        }

        html[dir="rtl"] .right-0 {
            right: auto;
            left: 0;
        }

        html[dir="rtl"] .left-1 {
            left: auto;
            right: 0.25rem;
        }

        html[dir="rtl"] .right-1 {
            right: auto;
            left: 0.25rem;
        }

        html[dir="rtl"] .right-3 {
            right: auto;
            left: 0.75rem;
        }

        html[dir="rtl"] aside nav a.flex.items-center {
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        html[dir="rtl"] aside nav button .flex.items-center {
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        html[dir="rtl"] .fa-arrow-left,
        html[dir="rtl"] .fa-arrow-right,
        html[dir="rtl"] .fa-chevron-left,
        html[dir="rtl"] .fa-chevron-right,
        html[dir="rtl"] .fa-angle-left,
        html[dir="rtl"] .fa-angle-right,
        html[dir="rtl"] .fa-caret-left,
        html[dir="rtl"] .fa-caret-right {
            transform: scaleX(-1);
        }
    </style>
@endif
