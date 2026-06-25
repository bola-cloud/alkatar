@extends('admin.master', ['menu' => 'site_content', 'submenu' => 'csr'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Edit CSR Initiative') }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.csr.index') }}">{{ __('CSR Initiatives') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="gallery__area bg-style">
                <div class="gallery__content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-one" role="tabpanel" aria-labelledby="nav-one-tab">
                            <form enctype="multipart/form-data" method="POST" action="{{ route('admin.csr.update', $initiative->id) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-vertical__item bg-style">
                                            <div class="item-top mb-30">
                                                <h2>{{ __('English Details') }}</h2>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title_en">{{ __('Title') }} (EN)</label>
                                                <input type="text" id="title_en" name="title_en" required value="{{ old('title_en', $initiative->title_en) }}" placeholder="{{ __('Title') }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="description_en">{{ __('Description') }} (EN)</label>
                                                <textarea name="description_en" id="description_en" required class="form-control" rows="8">{{ old('description_en', $initiative->description_en) }}</textarea>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="type">{{ __('Type') }}</label>
                                                <select class="form-control" name="type" id="type" required>
                                                    <option value="project" {{ $initiative->type == 'project' ? 'selected' : '' }}>{{ __('Project') }}</option>
                                                    <option value="initiative" {{ $initiative->type == 'initiative' ? 'selected' : '' }}>{{ __('Initiative') }}</option>
                                                    <option value="video" {{ $initiative->type == 'video' ? 'selected' : '' }}>{{ __('Video') }}</option>
                                                </select>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="image">{{ __('Image') }} ({{ __('Leave empty to keep current') }})</label>
                                                <input type="file" name="image" id="image" class="form-control">
                                                @if($initiative->image)
                                                    <div class="mt-2">
                                                        <img src="{{ asset('uploaded_files/csr/' . $initiative->image) }}" width="120" class="img-thumbnail" />
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="pdf_file">{{ __('PDF File') }} ({{ __('Leave empty to keep current') }})</label>
                                                <input type="file" name="pdf_file" id="pdf_file" class="form-control" accept=".pdf">
                                                @if($initiative->pdf_file)
                                                    <div class="mt-2">
                                                        <a href="{{ asset('uploaded_files/csr/' . $initiative->pdf_file) }}" target="_blank" class="text-primary font-bold">
                                                            <i class="fas fa-file-pdf"></i> {{ __('View Current PDF') }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="video_url">{{ __('Video URL') }} ({{ __('Optional') }})</label>
                                                <input type="url" name="video_url" id="video_url" class="form-control" value="{{ old('video_url', $initiative->video_url) }}" placeholder="https://youtube.com/...">
                                            </div>
                                            <div class="input__button">
                                                <button type="submit" class="btn btn-blue">{{ __('Update') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-vertical__item bg-style">
                                            <div class="item-top mb-30">
                                                <h2>{{ __('Arabic Details') }}</h2>
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="title_ar">{{ __('Title') }} (AR)</label>
                                                <input type="text" id="title_ar" name="title_ar" required value="{{ old('title_ar', $initiative->title_ar) }}" placeholder="{{ __('Title') }}">
                                            </div>
                                            <div class="input__group mb-25">
                                                <label for="description_ar">{{ __('Description') }} (AR)</label>
                                                <textarea name="description_ar" id="description_ar" required class="form-control" rows="8">{{ old('description_ar', $initiative->description_ar) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
