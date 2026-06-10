<section x-data="{ 
    activeSlide: 0,
    slides: {{ $sliders->count() }},
    next() { this.activeSlide = (this.activeSlide + 1) % this.slides },
    prev() { this.activeSlide = (this.activeSlide - 1 + this.slides) % this.slides }
}" class="relative h-[600px] overflow-hidden bg-katar-green">
    
    @foreach($sliders as $index => $slider)
    <div x-show="activeSlide === {{ $index }}" 
         x-transition:enter="transition ease-out duration-1000"
         x-transition:enter-start="opacity-0 scale-110"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute inset-0">
        @php 
            $sliderImg = $slider->Slider_Icon;
            $imgSrc = (strpos($sliderImg, 'http') === 0) ? $sliderImg : asset(SliderImage().$sliderImg);
        @endphp
        <img src="{{ $imgSrc }}" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-r from-katar-dark/80 to-transparent flex items-center">
            <div class="container mx-auto px-4">
                <div class="max-w-2xl text-white">
                    <span class="inline-block px-4 py-1 bg-katar-gold text-white font-bold rounded-full mb-6 transform -rotate-2">
                        {{ langConverter($slider->en_Sub_Title, $slider->fr_Sub_Title) }}
                    </span>
                    <h1 class="text-6xl md:text-7xl font-extrabold mb-6 leading-tight">
                        {!! langConverter($slider->en_Title, $slider->fr_Title) !!}
                    </h1>
                    <p class="text-xl text-katar-cream/80 mb-10 leading-relaxed">
                        {{ langConverter($slider->en_Description, $slider->fr_Description) }}
                    </p>
                    <div class="flex gap-4">
                        <a href="{{ $slider->en_Button_URL }}" class="bg-katar-gold hover:bg-white hover:text-katar-green text-white px-8 py-4 rounded-xl font-bold transition-all transform hover:-translate-y-1 shadow-lg">
                            {{ langConverter($slider->en_Button_Text, $slider->fr_Button_Text) }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Controls -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex gap-4 z-10">
        @foreach($sliders as $index => $slider)
        <button @click="activeSlide = {{ $index }}" 
                :class="activeSlide === {{ $index }} ? 'bg-katar-gold w-12' : 'bg-white/30 w-3'"
                class="h-3 rounded-full transition-all duration-500"></button>
        @endforeach
    </div>
</section>
