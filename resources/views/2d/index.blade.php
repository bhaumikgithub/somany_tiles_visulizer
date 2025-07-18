@extends('layouts.front')
@section('content')
@php
    $categories = [
        ['key' => 'livingroom', 'label' => 'Living Room', 'imageClass' => 'image1', 'mobileClass' => 'mobile_image1'],
        ['key' => 'bedroom', 'label' => 'Bedroom', 'imageClass' => 'image2', 'mobileClass' => 'mobile_image2'],
        ['key' => 'kitchen', 'label' => 'Kitchen', 'imageClass' => 'image3', 'mobileClass' => 'mobile_image3'],
        ['key' => 'bathroom', 'label' => 'Bathroom', 'imageClass' => 'image4', 'mobileClass' => 'mobile_image4'],
        ['key' => 'prayer-room', 'label' => 'Prayer Room', 'imageClass' => 'image5', 'mobileClass' => 'mobile_image5'],
        ['key' => 'outdoor', 'label' => 'Outdoor', 'imageClass' => 'image6', 'mobileClass' => 'mobile_image6'],
        ['key' => 'commercial', 'label' => 'Commercial', 'imageClass' => 'image7', 'mobileClass' => 'mobile_image7'],
    ];
@endphp
    <div class="onLoadWrapper" style="display: none;">
        <div class="container-fluid mt-2 desktop-div" style="padding-right: 2px;">
            @foreach($categories as $index => $category)
                <div class="grow hover_2 {{ $index === 0 ? 'hover_1 first_width' : '' }}">
                    <a href="javascript:void(0);" onclick="fetchCategory('{{ $category['key'] }}', '2d');">
                        <div class="{{ $category['imageClass'] }}" style="writing-mode: vertical-rl;">
                            <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">{{ $category['label'] }}</h1>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container pr-0 mt-2 mobile-div">
        <div>
            <div class="mobile-index-main">
                @foreach($categories as $index => $category)
                    <a href="javascript:void(0);" onclick="fetchCategory('{{ $category['key'] }}', '2d');">
                        <div class="mobile_grow {{ $category['mobileClass'] }} mobile_hover_2 {{ $index === 0 ? 'mobile_hover_1 col-md-12' : '' }}">
                            <h1 class="mobile_h1">{{ $category['label'] }}</h1>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="cmn-ai-button">
        <span class="span-icon">
            <button id="startRecording" class="share-link d-flex flex-wrap align-items-center">🎤</button></span>
    </div>

    @include('common.speech_to_text')
@endsection
