<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Feedback cum Certificate Form — {{ $workshop->title }}</title>

    <link rel="icon" href="{{ asset('images/qwaretech-hub-favicon.png') }}">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Karla:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --brand: #005495;
            --brand-dark: #003b68;
            --brand-light: #eaf4fb;
            --brand-border: #d9edf9;
            --ink: #1f2937;
            --muted: #718096;
            --border: #d7e2eb;
            --danger: #dc3545;
            --success: #198754;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f6f9fc;
            color: var(--ink);
            font-family: 'Karla', system-ui, sans-serif;
        }

        h1,
        h2,
        h3 {
            font-family: 'Fraunces', serif;
        }

        .hero {
            background: linear-gradient(
                135deg,
                var(--brand),
                var(--brand-dark)
            );

            color: #fff;
            border-radius: 0 0 1.5rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            right: -180px;
            top: -260px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            left: -130px;
            bottom: -150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-logo {
            max-width: 230px;
            max-height: 58px;
            object-fit: contain;
            background: #fff;
            border-radius: .6rem;
            padding: .35rem .55rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .12);
        }

        .hero-title {
            font-weight: 700;
            letter-spacing: -.01em;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: .55rem;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 999px;
            padding: .45rem .8rem;
            font-size: .84rem;
            backdrop-filter: blur(5px);
        }

        .hero-pill i {
            opacity: .9;
        }

        .form-card {
            border: 0;
            border-radius: 1.15rem;
            box-shadow: 0 12px 40px rgba(16, 42, 67, .08);
            overflow: hidden;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: .65rem;

            font-weight: 700;
            color: var(--brand);

            background: var(--brand-light);
            border: 1px solid var(--brand-border);

            border-radius: .75rem;
            padding: .7rem .85rem;
        }

        .section-title i {
            width: 32px;
            height: 32px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: .55rem;
            background: #fff;

            flex-shrink: 0;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: .45rem;
        }

        .required {
            color: var(--danger);
        }

        .form-control,
        .form-select {
            min-height: 46px;
            border-color: var(--border);
            border-radius: .7rem;
            transition:
                border-color .18s ease,
                box-shadow .18s ease,
                background-color .18s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 .2rem rgba(0, 84, 149, .12);
        }

        .form-select:disabled {
            background-color: #f2f5f8;
            color: #8a98a6;
            cursor: not-allowed;
        }

        .readonly-field {
            background: #f4f8fb !important;
            color: #4b6275;
        }

        .field-help {
            color: var(--muted);
            font-size: .78rem;
            margin-top: .3rem;
        }

        .field-help.loading {
            color: var(--brand);
        }

        .field-help.error {
            color: var(--danger);
        }

        .field-help.success {
            color: var(--success);
        }

        .required-note {
            font-size: .78rem;
            color: var(--muted);
        }

        .phone-group {
            display: flex;
            align-items: stretch;
            border: 1px solid var(--border);
            border-radius: .7rem;
            background: #fff;
            overflow: hidden;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .phone-group:focus-within {
            border-color: var(--brand);
            box-shadow: 0 0 0 .2rem rgba(0, 84, 149, .12);
        }

        .phone-code {
            display: flex;
            align-items: center;
            padding: 0 .85rem;
            background: #f4f8fb;
            color: #4b6275;
            font-weight: 600;
            border-right: 1px solid var(--border);
            white-space: nowrap;
        }

        .phone-group .form-control {
            border: 0;
            border-radius: 0;
            box-shadow: none !important;
        }

        .phone-group .form-control:focus {
            border: 0;
            box-shadow: none !important;
        }

        .same-check {
            margin-top: .55rem;
        }

        .same-check .form-check-input:checked {
            background-color: var(--brand);
            border-color: var(--brand);
        }

        .btn-brand {
            background: var(--brand);
            border-color: var(--brand);
        }

        .btn-brand:hover,
        .btn-brand:focus {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
        }

        .error-summary {
            border-left: 4px solid var(--danger);
        }

        .invalid-feedback {
            font-weight: 500;
        }

        .location-loading {
            position: relative;
        }

        .location-loading::after {
            content: "";
            width: 15px;
            height: 15px;

            border: 2px solid #d7e2eb;
            border-top-color: var(--brand);

            border-radius: 50%;

            position: absolute;
            right: 14px;
            top: 50%;

            transform: translateY(-50%);
            animation: spin .7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: translateY(-50%) rotate(360deg);
            }
        }

        .sticky-submit {
            position: sticky;
            bottom: 0;

            background: rgba(255, 255, 255, .96);
            backdrop-filter: blur(8px);

            border-top: 1px solid #e8eef4;

            z-index: 5;
        }

        .footer-brand img {
            max-height: 32px;
            max-width: 150px;
            object-fit: contain;
        }

        .feedback-choice-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:8px; }
        .feedback-option { min-height:45px; display:flex; align-items:center; justify-content:center; }

        .form-check-input {
            cursor: pointer;
        }

        .form-check-label {
            cursor: pointer;
        }

        @media (max-width: 767px) {
            .hero {
                border-radius: 0 0 1rem 1rem;
            }

            .hero-logo {
                max-width: 190px;
            }

            .hero-title {
                font-size: 1.35rem;
            }

            .sticky-submit {
                margin-left: -1rem;
                margin-right: -1rem;
            }
        }

        @media (max-width: 575px) {
            .hero-meta {
                justify-content: flex-start;
            }

            .hero-pill {
                font-size: .78rem;
            }

            .phone-code {
                padding: 0 .65rem;
            }
        }
    </style>
</head>

<body>

<header class="hero px-3 px-md-4 py-4 py-md-5 text-center">

    <div class="hero-content">

        <img
            class="hero-logo mb-3"
            src="{{ asset('images/qwaretech-hub-logo.png') }}"
            alt="Quaere Tech Hub"
        >

        <div class="small text-white-50 text-uppercase fw-semibold mb-1">
            Feedback cum Certificate Form
        </div>

        <h1 class="h3 hero-title mb-2">
            {{ $workshop->title }}
        </h1>

        <div class="mb-3 opacity-75">
            {{ $workshop->college_name }}
        </div>

        <div class="hero-meta">

            <span class="hero-pill">
                <i class="bi bi-calendar3 me-1"></i>
                {{ $workshop->start_date?->format('d M Y') ?? '—' }}
            </span>

            @if ($workshop->end_date)
                <span class="hero-pill">
                    <i class="bi bi-calendar-check me-1"></i>
                    {{ $workshop->end_date->format('d M Y') }}
                </span>
            @endif

            <span class="hero-pill">
                <i class="bi bi-clock-history me-1"></i>
                {{ $workshop->duration_label }}
            </span>

            @if ($workshop->trainer_name)
                <span class="hero-pill">
                    <i class="bi bi-person-badge me-1"></i>
                    {{ $workshop->trainer_name }}
                </span>
            @endif

        </div>

    </div>

</header>


<main class="container py-4 py-md-5" style="max-width: 760px;">

    @if ($isFull)

        <div class="card form-card">

            <div class="card-body p-5 text-center">

                <div class="display-6 text-warning mb-3">
                    <i class="bi bi-people-fill"></i>
                </div>

                <h2 class="h4">
                    Registration is Full
                </h2>

                <p class="text-muted mb-0">
                    Sorry, this workshop has reached its maximum number
                    of participants.
                </p>

            </div>

        </div>

    @else

        {{-- Server Validation Errors --}}
        @if ($errors->any())

            <div
                class="alert alert-danger error-summary shadow-sm mb-3"
                role="alert"
                tabindex="-1"
                id="server-errors"
            >

                <div class="d-flex gap-2">

                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>

                    <div>

                        <strong>
                            Please correct the highlighted details.
                        </strong>

                        <ul class="mb-0 mt-1 ps-3 small">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif


        <form
            data-client-validation="true"
            method="POST"
            action="{{ route('workshop.register.store', $workshop) }}"
            class="card form-card"
            id="registration-form"
            novalidate
        >

            @csrf

            <div class="card-body p-3 p-md-4 p-lg-5">


                {{-- ===================================================== --}}
                {{-- PERSONAL INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="section-title mb-3">
                    <i class="bi bi-person-fill"></i>
                    <span>Personal Information</span>
                </div>


                {{-- Full Name --}}
                <div class="mb-3">

                    <label class="form-label" for="name">
                        Full Name
                        <span class="required">*</span>
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        oninput="this.value = this.value.toLocaleUpperCase()"
                        style="text-transform: uppercase;"
                        placeholder="Enter your full name"
                        class="form-control @error('name') is-invalid @enderror"
                        maxlength="191"
                        autocomplete="name"
                        required
                    >

                    <div class="field-help">
                        Use your name exactly as you want it on the certificate.
                    </div>

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- ===================================================== --}}
                {{-- HOME LOCATION --}}
                {{-- ===================================================== --}}

                <div class="section-title mb-3 mt-4">

                    <i class="bi bi-geo-alt-fill"></i>

                    <span>Home Location</span>

                </div>


                <div class="row g-3 mb-3">


                    {{-- State --}}
                    <div class="col-md-6">

                        <label class="form-label" for="home_state">

                            State
                            <span class="required">*</span>

                        </label>

                        <select
                            id="home_state"
                            name="home_state"
                            class="form-select @error('home_state') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select State
                            </option>

                        </select>

                        <div
                            class="field-help"
                            id="state-help"
                        >
                            Select your home state.
                        </div>

                        @error('home_state')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- District --}}
                    <div class="col-md-6">

                        <label class="form-label" for="home_district">

                            District
                            <span class="required">*</span>

                        </label>

                        <select
                            id="home_district"
                            name="home_district"
                            class="form-select @error('home_district') is-invalid @enderror"
                            required
                            disabled
                        >

                            <option value="">
                                Select State First
                            </option>

                        </select>

                        <div
                            class="field-help"
                            id="district-help"
                        >
                            Select your state to load districts.
                        </div>

                        @error('home_district')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- CONTACT INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="section-title mb-3 mt-4">

                    <i class="bi bi-telephone-fill"></i>

                    <span>Contact Information</span>

                </div>


                <div class="row g-3">


                    {{-- Mobile --}}
                    <div class="col-md-6">

                        <label class="form-label" for="mobile">

                            Mobile Number
                            <span class="required">*</span>

                        </label>

                        <div class="phone-group">

                            <span class="phone-code">
                                +91
                            </span>

                            <input
                                id="mobile"
                                type="tel"
                                name="mobile"
                                value="{{ old('mobile') }}"
                                placeholder="10-digit mobile number"
                                class="form-control @error('mobile') is-invalid @enderror"
                                inputmode="numeric"
                                maxlength="10"
                                autocomplete="tel"
                                required
                            >

                        </div>

                        <div class="field-help">
                            Enter a valid 10-digit Indian mobile number.
                        </div>

                        @error('mobile')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- WhatsApp --}}
                    <div class="col-md-6">

                        <label class="form-label" for="whatsapp_number">

                            WhatsApp Number
                            <span class="required">*</span>

                        </label>

                        <div class="phone-group">

                            <span class="phone-code">
                                +91
                            </span>

                            <input
                                id="whatsapp_number"
                                type="tel"
                                name="whatsapp_number"
                                value="{{ old('whatsapp_number') }}"
                                placeholder="10-digit WhatsApp number"
                                class="form-control @error('whatsapp_number') is-invalid @enderror"
                                inputmode="numeric"
                                maxlength="10"
                                autocomplete="tel"
                                required
                            >

                        </div>

                        <div class="form-check same-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="same_as_mobile"
                            >

                            <label
                                class="form-check-label small"
                                for="same_as_mobile"
                            >
                                Same as mobile number
                            </label>

                        </div>

                        @error('whatsapp_number')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Email --}}
                    <div class="col-12">

                        <label class="form-label" for="email">

                            Email ID
                            <span class="required">*</span>

                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            class="form-control @error('email') is-invalid @enderror"
                            maxlength="191"
                            autocomplete="email"
                            required
                        >

                        @error('email')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- ACADEMIC INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="section-title mb-3 mt-4">

                    <i class="bi bi-mortarboard-fill"></i>

                    <span>Academic Information</span>

                </div>


                {{-- College --}}
                <div class="mb-3">

                    <label class="form-label" for="college_name">

                        College Name

                    </label>

                    <input
                        id="college_name"
                        type="text"
                        value="{{ $workshop->college_name }}"
                        class="form-control readonly-field"
                        readonly
                    >

                    <div class="field-help">

                        Automatically filled from this workshop.

                    </div>

                </div>


                <div class="row g-3">
                    {{-- Course --}}
                    <div class="col-md-6">
                        <label class="form-label" for="course_id">
                            Course / Program <span class="required">*</span>
                        </label>
                        <select
                            id="course_id"
                            name="course_id"
                            class="form-select @error('course_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Course / Program</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" @selected((string) old('course_id') === (string) $course->id)>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="field-help">Managed by the admin from Academic Options.</div>
                    </div>

                    {{-- Branch --}}
                    <div class="col-md-6">
                        <label class="form-label" for="branch_id">
                            Branch / Specialization <span class="required">*</span>
                        </label>
                        <select
                            id="branch_id"
                            name="branch_id"
                            class="form-select @error('branch_id') is-invalid @enderror"
                            required
                            disabled
                        >
                            <option value="">Select Course First</option>
                        </select>
                        @error('branch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="field-help" id="branch-help">Select a course to load its branches.</div>
                    </div>

                    {{-- Year --}}
                    <div class="col-md-6">

                        <label class="form-label" for="year">

                            Year / Semester
                            <span class="required">*</span>

                        </label>

                        <input
                            id="year"
                            type="text"
                            name="year"
                            value="{{ old('year') }}"
                            placeholder="e.g. 3rd Year"
                            class="form-control @error('year') is-invalid @enderror"
                            maxlength="50"
                            required
                        >

                        @error('year')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Enrollment --}}
                    <div class="col-md-6">

                        <label class="form-label" for="enrollment_number">

                            Enrollment / Roll Number
                            <span class="required">*</span>

                        </label>

                        <input
                            id="enrollment_number"
                            type="text"
                            name="enrollment_number"
                            value="{{ old('enrollment_number') }}"
                            placeholder="Enter enrollment / roll number"
                            class="form-control @error('enrollment_number') is-invalid @enderror"
                            maxlength="50"
                            required
                        >

                        @error('enrollment_number')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- ===================================================== --}}
                {{-- FIXED FEEDBACK --}}
                {{-- ===================================================== --}}

                <div class="section-title mb-3 mt-4">
                    <i class="bi bi-chat-square-text-fill"></i>
                    <span>Workshop Feedback <span class="required">*</span></span>
                </div>

                @foreach(config('workshop_feedback.questions', []) as $question)
                    <div class="mb-3 feedback-question" data-feedback-question="feedback_{{ $question['key'] }}">
                        <label class="form-label">
                            {{ $question['label'] }} <span class="required">*</span>
                        </label>

                        @error('feedback_'.$question['key'])
                            <div class="text-danger small mb-1">{{ $message }}</div>
                        @enderror

                        @if($question['type'] === 'choice')
                            <div class="feedback-choice-grid">
                                @foreach($question['options'] as $index => $option)
                                    @php $inputId = 'feedback_' . $question['key'] . '_' . $index; @endphp
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        name="feedback_{{ $question['key'] }}"
                                        id="{{ $inputId }}"
                                        value="{{ $option }}"
                                        autocomplete="off"
                                        required
                                        @checked(old('feedback_'.$question['key']) === $option)
                                    >
                                    <label class="btn btn-outline-primary feedback-option" for="{{ $inputId }}">{{ $option }}</label>
                                @endforeach
                            </div>
                        @elseif($question['type'] === 'select')
                            <select
                                name="feedback_{{ $question['key'] }}"
                                class="form-select"
                                required
                            >
                                <option value="">Select Speaker</option>
                                @foreach($question['options'] as $option)
                                    <option value="{{ $option }}" @selected(old('feedback_'.$question['key']) === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        @else
                            <textarea
                                name="feedback_{{ $question['key'] }}"
                                rows="3"
                                maxlength="2000"
                                class="form-control"
                                placeholder="Please share your valuable comments..."
                                required
                            >{{ old('feedback_'.$question['key']) }}</textarea>
                        @endif
                    </div>
                @endforeach

            {{-- ========================================================= --}}
            {{-- SUBMIT --}}
            {{-- ========================================================= --}}

            <div class="sticky-submit p-3 p-md-4">

                <div
                    class="d-flex align-items-center justify-content-between gap-3"
                >

                    <div class="small text-muted d-none d-md-block">

                        <i class="bi bi-shield-check me-1"></i>

                        Your details are submitted securely.

                    </div>


                    <button
                        type="submit"
                        class="btn btn-brand text-white w-100 w-md-auto px-4 py-2"
                        id="submit-registration"
                    >

                        <i class="bi bi-check2-circle me-1"></i>

                        Submit Registration

                    </button>

                </div>

            </div>

        </form>

    @endif

</main>


<footer class="container pb-4 text-center footer-brand">

    <img
        src="{{ asset('images/qwaretech-hub-logo.png') }}"
        alt="Quaere Tech Hub"
    >

    <div class="small text-muted mt-2">
        Feedback cum Certificate Portal
    </div>

</footer>
@php
    $academicOptions = $courses->map(function ($course) {
        return [
            'id' => $course->id,
            'branches' => $course->branches->map(function ($branch) {
                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                ];
            })->values()->all(),
        ];
    })->values()->all();
@endphp

<script>
(() => {

    'use strict';


    /* ============================================================
       FORM ELEMENTS
    ============================================================ */

    const form = document.getElementById('registration-form');

    if (!form) {
        return;
    }


    const mobile = document.getElementById('mobile');
    const whatsapp = document.getElementById('whatsapp_number');
    const sameAsMobile = document.getElementById('same_as_mobile');

    const homeState = document.getElementById('home_state');
    const homeDistrict = document.getElementById('home_district');

    const stateHelp = document.getElementById('state-help');
    const districtHelp = document.getElementById('district-help');

const courseSelect = document.getElementById('course_id');
const branchSelect = document.getElementById('branch_id');
const branchHelp = document.getElementById('branch-help');

const oldCourseId = @json(old('course_id'));
const oldBranchId = @json(old('branch_id'));

const academicOptions = @json($academicOptions);

const submitButton = document.getElementById('submit-registration');
    /* ============================================================
       OLD VALUES
    ============================================================ */

    const oldState = @json(old('home_state'));
    const oldDistrict = @json(old('home_district'));


    /* ============================================================
       ERROR HELPERS
    ============================================================ */

    const setFieldError = (field, message) => {

        if (!field) {
            return false;
        }

        field.classList.add('is-invalid');

        let feedback = field.parentElement?.querySelector(
            '.js-invalid-feedback'
        );

        if (!feedback) {

            feedback = document.createElement('div');

            feedback.className =
                'invalid-feedback js-invalid-feedback';

            field.parentElement?.appendChild(feedback);
        }

        feedback.textContent = message;

        return false;
    };


    const clearFieldError = (field) => {

        if (!field) {
            return;
        }

        field.classList.remove('is-invalid');

        field.parentElement
            ?.querySelector('.js-invalid-feedback')
            ?.remove();
    };


    /* ============================================================
       PHONE CLEANING
    ============================================================ */

    const cleanPhone = (field) => {

        if (!field) {
            return;
        }

        field.value = field.value
            .replace(/\D/g, '')
            .slice(0, 10);
    };


    /* ============================================================
       INDIA STATE / DISTRICT DATA
    ============================================================ */

    let locationData = [];


    const getStates = () => {

        if (Array.isArray(locationData)) {
            return locationData;
        }

        return Array.isArray(locationData?.states)
            ? locationData.states
            : [];
    };


    const populateDistricts = (
        stateName,
        selectedDistrict = ''
    ) => {

        if (!homeDistrict) {
            return;
        }

        const states = getStates();

        const selectedState = states.find(
            item => item.state === stateName
        );


        homeDistrict.innerHTML =
            '<option value="">Select District</option>';


        if (
            !selectedState ||
            !Array.isArray(selectedState.districts)
        ) {

            homeDistrict.disabled = true;

            if (districtHelp) {

                districtHelp.className =
                    'field-help error';

                districtHelp.textContent =
                    'Please select a valid state first.';
            }

            return;
        }


        const districts = [
            ...selectedState.districts
        ].sort((a, b) =>
            a.localeCompare(b)
        );


        districts.forEach(district => {

            const option =
                document.createElement('option');

            option.value = district;
            option.textContent = district;

            if (district === selectedDistrict) {
                option.selected = true;
            }

            homeDistrict.appendChild(option);

        });


        homeDistrict.disabled = false;


        if (districtHelp) {

            districtHelp.className =
                'field-help success';

            districtHelp.textContent =
                `${districts.length} district${districts.length !== 1 ? 's' : ''} available.`;
        }


        clearFieldError(homeState);
        clearFieldError(homeDistrict);

    };


    const loadLocationData = async () => {

        if (!homeState || !homeDistrict) {
            return;
        }


        homeState.classList.add('location-loading');


        if (stateHelp) {

            stateHelp.className =
                'field-help loading';

            stateHelp.textContent =
                'Loading states...';
        }


        try {

            const response = await fetch(
                '{{ asset('data/india-states-districts.json') }}',
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    },
                    cache: 'force-cache'
                }
            );


            if (!response.ok) {

                throw new Error(
                    `Location data request failed: ${response.status}`
                );
            }


            const data = await response.json();


            locationData = data;


            const states = getStates();


            if (!states.length) {

                throw new Error(
                    'No states found in location data.'
                );
            }


            homeState.innerHTML =
                '<option value="">Select State</option>';


            states
                .slice()
                .sort((a, b) =>
                    a.state.localeCompare(b.state)
                )
                .forEach(item => {

                    const option =
                        document.createElement('option');

                    option.value = item.state;
                    option.textContent = item.state;

                    if (item.state === oldState) {
                        option.selected = true;
                    }

                    homeState.appendChild(option);

                });


            if (stateHelp) {

                stateHelp.className =
                    'field-help';

                stateHelp.textContent =
                    `${states.length} states / UTs available.`;
            }


            if (oldState) {

                populateDistricts(
                    oldState,
                    oldDistrict
                );

            }


        } catch (error) {

            console.error(
                'Location data error:',
                error
            );


            homeState.innerHTML =
                '<option value="">Unable to load states</option>';


            homeDistrict.innerHTML =
                '<option value="">Unable to load districts</option>';


            homeDistrict.disabled = true;


            if (stateHelp) {

                stateHelp.className =
                    'field-help error';

                stateHelp.textContent =
                    'Unable to load states. Please refresh the page.';
            }


            if (districtHelp) {

                districtHelp.className =
                    'field-help error';

                districtHelp.textContent =
                    'Districts are unavailable until states are loaded.';
            }

        } finally {

            homeState.classList.remove(
                'location-loading'
            );

        }

    };


    /* ============================================================
       COURSE / BRANCH DEPENDENCY
    ============================================================ */

    const loadBranches = (courseId, selectedBranch = '') => {
        if (!branchSelect) return;

        const course = academicOptions.find(item => String(item.id) === String(courseId));
        branchSelect.innerHTML = '<option value="">Select Branch / Specialization</option>';

        if (!course || !Array.isArray(course.branches) || !course.branches.length) {
            branchSelect.disabled = true;
            if (branchHelp) {
                branchHelp.textContent = course ? 'No active branches are configured for this course yet.' : 'Select a course to load its branches.';
                branchHelp.className = 'field-help ' + (course ? 'error' : '');
            }
            return;
        }

        course.branches.forEach(branch => {
            const option = document.createElement('option');
            option.value = branch.id;
            option.textContent = branch.name;
            option.selected = String(branch.id) === String(selectedBranch);
            branchSelect.appendChild(option);
        });

        branchSelect.disabled = false;
        if (branchHelp) {
            branchHelp.className = 'field-help success';
            branchHelp.textContent = `${course.branches.length} active branch${course.branches.length !== 1 ? 'es' : ''} available.`;
        }
    };

    courseSelect?.addEventListener('change', () => {
        clearFieldError(courseSelect);
        clearFieldError(branchSelect);
        loadBranches(courseSelect.value);
    });

    branchSelect?.addEventListener('change', () => clearFieldError(branchSelect));
    loadBranches(oldCourseId, oldBranchId);


    /* ============================================================
       STATE CHANGE
    ============================================================ */

    homeState?.addEventListener(
        'change',
        () => {

            clearFieldError(homeState);
            clearFieldError(homeDistrict);


            const state = homeState.value;


            if (!state) {

                homeDistrict.innerHTML =
                    '<option value="">Select State First</option>';

                homeDistrict.disabled = true;


                if (districtHelp) {

                    districtHelp.className =
                        'field-help';

                    districtHelp.textContent =
                        'Select your state to load districts.';
                }

                return;
            }


            populateDistricts(state);

        }
    );


    /* ============================================================
       DISTRICT CHANGE
    ============================================================ */

    homeDistrict?.addEventListener(
        'change',
        () => {

            clearFieldError(homeDistrict);

        }
    );


    /* ============================================================
       MOBILE
    ============================================================ */

    mobile?.addEventListener(
        'input',
        () => {

            cleanPhone(mobile);


            if (sameAsMobile?.checked) {

                whatsapp.value =
                    mobile.value;
            }


            clearFieldError(mobile);
            clearFieldError(whatsapp);

        }
    );


    /* ============================================================
       WHATSAPP
    ============================================================ */

    whatsapp?.addEventListener(
        'input',
        () => {

            cleanPhone(whatsapp);

            clearFieldError(whatsapp);

        }
    );


    /* ============================================================
       SAME AS MOBILE
    ============================================================ */

    sameAsMobile?.addEventListener(
        'change',
        () => {

            if (sameAsMobile.checked) {

                cleanPhone(mobile);

                whatsapp.value =
                    mobile.value;

                whatsapp.readOnly = true;

                whatsapp.classList.add(
                    'readonly-field'
                );

                clearFieldError(whatsapp);

            } else {

                whatsapp.readOnly = false;

                whatsapp.classList.remove(
                    'readonly-field'
                );

                whatsapp.focus();

            }

        }
    );


    /* ============================================================
       CLEAR CLIENT ERRORS ON INPUT
    ============================================================ */

    form.querySelectorAll(
        'input[type="text"], input[type="email"], input[type="tel"], select, textarea'
    ).forEach(field => {

        field.addEventListener('input', () => clearFieldError(field));
        field.addEventListener('change', () => clearFieldError(field));

    });

    form.querySelectorAll('[data-feedback-question]').forEach(question => {
        const clearQuestionError = () => {
            const radios = Array.from(question.querySelectorAll('input[type="radio"]'));
            const field = question.querySelector('select, textarea');
            const answered = radios.length
                ? radios.some(radio => radio.checked)
                : Boolean(field && String(field.value).trim());

            if (answered) {
                question.querySelector('.js-invalid-feedback')?.remove();
                question.querySelectorAll('.is-invalid').forEach(control => control.classList.remove('is-invalid'));
            }
        };

        question.querySelectorAll('input[type="radio"], select, textarea').forEach(control => {
            control.addEventListener('input', clearQuestionError);
            control.addEventListener('change', clearQuestionError);
        });
    });


    /* ============================================================
       FORM SUBMIT VALIDATION
    ============================================================ */

    form.addEventListener(
        'submit',
        (event) => {

            let valid = true;

            const firstInvalid = [];


            /* Remove previous JS errors */

            form.querySelectorAll(
                '.js-invalid-feedback'
            ).forEach(
                el => el.remove()
            );


            form.querySelectorAll(
                '.is-invalid'
            ).forEach(
                el => el.classList.remove('is-invalid')
            );


            /* Required fields */

            const Fields = [
                ['name', 'Please enter your full name.'],
                ['home_state', 'Please select your home state.'],
                ['home_district', 'Please select your home district.'],
                ['mobile', 'Please enter your mobile number.'],
                ['whatsapp_number', 'Please enter your WhatsApp number.'],
                ['email', 'Please enter your email ID.'],
                ['course_id', 'Please select your course/program.'],
                ['branch_id', 'Please select your branch/specialization.'],
                ['year', 'Please enter your year/semester.'],
                ['enrollment_number', 'Please enter your enrollment/roll number.']
            ];


           Fields.forEach(
                ([id, message]) => {

                    const field =
                        document.getElementById(id);


                    if (
                        !field ||
                        !String(field.value).trim()
                    ) {

                        valid = false;

                        setFieldError(
                            field,
                            message
                        );


                        if (
                            !firstInvalid.length
                        ) {

                            firstInvalid.push(
                                field
                            );
                        }

                    }

                }
            );


            /* State/District relationship */

            if (
                homeState?.value &&
                homeDistrict?.disabled
            ) {

                valid = false;


                setFieldError(
                    homeDistrict,
                    'Please select your state again so districts can load.'
                );


                if (
                    !firstInvalid.length
                ) {

                    firstInvalid.push(
                        homeState
                    );
                }

            }


            /* Mobile */

            const phoneRegex =
                /^[6-9]\d{9}$/;


            if (
                mobile?.value &&
                !phoneRegex.test(
                    mobile.value
                )
            ) {

                valid = false;


                setFieldError(
                    mobile,
                    'Enter a valid 10-digit Indian mobile number.'
                );


                if (
                    !firstInvalid.length
                ) {

                    firstInvalid.push(
                        mobile
                    );
                }

            }


            /* WhatsApp */

            if (
                whatsapp?.value &&
                !phoneRegex.test(
                    whatsapp.value
                )
            ) {

                valid = false;


                setFieldError(
                    whatsapp,
                    'Enter a valid 10-digit Indian WhatsApp number.'
                );


                if (
                    !firstInvalid.length
                ) {

                    firstInvalid.push(
                        whatsapp
                    );
                }

            }


            /* Email */

            const email =
                document.getElementById('email');


            const emailRegex =
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


            if (
                email?.value &&
                !emailRegex.test(
                    email.value.trim()
                )
            ) {

                valid = false;


                setFieldError(
                    email,
                    'Enter a valid email ID.'
                );


                if (
                    !firstInvalid.length
                ) {

                    firstInvalid.push(
                        email
                    );
                }

            }


            /* Every feedback question is required (including comments). */
            form.querySelectorAll('[data-feedback-question]').forEach(question => {
                const radios = Array.from(question.querySelectorAll('input[type="radio"]'));
                const field = question.querySelector('select, textarea');
                const questionIsInvalid = radios.length
                    ? !radios.some(radio => radio.checked)
                    : (!field || !String(field.value).trim());

                if (!questionIsInvalid) return;

                valid = false;
                if (radios.length) {
                    radios.forEach(radio => radio.classList.add('is-invalid'));
                } else if (field) {
                    field.classList.add('is-invalid');
                }

                const error = document.createElement('div');
                error.className = 'invalid-feedback d-block js-invalid-feedback mt-1';
                error.textContent = 'Please answer this question.';
                question.appendChild(error);

                if (firstInvalid.length === 0) {
                    firstInvalid.push(radios[0] || field || question);
                }
            });

            /* Clear inline feedback errors as the user answers a question. */
            /* Handlers are attached once below; this block only validates. */

            /* Prevent submit if invalid */

            if (!valid) {

                event.preventDefault();


                const field =
                    firstInvalid[0];


                field?.focus();


                field?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });


                return;

            }


            /* Prevent double submission */

            if (
                submitButton &&
                !submitButton.disabled
            ) {

                submitButton.disabled = true;


                submitButton.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm me-1"
                        role="status"
                        aria-hidden="true"
                    ></span>
                    Submitting…
                `;

            }

        }
    );


    /* ============================================================
       LOAD STATES
    ============================================================ */

    loadLocationData();


})();
</script>


</body>
</html>