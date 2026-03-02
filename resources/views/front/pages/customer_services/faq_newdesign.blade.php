@extends('front.layouts.new_design_layout')
@section('title', isset($title) ? $title : __('FAQ'))
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')

    @php
        // Banner title: prefer SEO title if provided, otherwise use generic FAQ
        $bannerTitle = $title ?? __('FAQ');
    @endphp

    @include('front.partials.category_banner', ['title' => $bannerTitle])

    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold mb-4">{{ $bannerTitle }}</h1>

                <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $i => $fq)
                        @php
                            $htmlLocale = session('HTML_LANG', session('APP_LOCALE', app()->getLocale() ?? 'en'));
                            $isAr = in_array($htmlLocale, ['ar', 'fr']);
                            $question = $isAr ? ($fq->question_fr ?? $fq->question) : ($fq->question ?? $fq->question_fr);
                            $answer = $isAr ? ($fq->answer_fr ?? $fq->answer) : ($fq->answer ?? $fq->answer_fr);
                            $headingId = 'heading' . $i;
                            $collapseId = 'collapse' . $i;
                        @endphp
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="{{ $headingId }}">
                                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} bg-light rounded" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                    aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                                    {!! $question !!}
                                </button>
                            </h2>
                            <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
                                aria-labelledby="{{ $headingId }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body bg-white text-muted">
                                    {!! clean($answer) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            <div class="col-lg-5 text-center">
                <img src="{{ asset('new-design/images/faq.png') }}" alt="faq" class="img-fluid"
                    style="max-height:520px; object-fit:cover;">
            </div>
        </div>
    </div>

@endsection