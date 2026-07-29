(function () {
    'use strict';

    function activateSlide(slider, targetIndex) {
        var slides = slider.querySelectorAll('[data-hero-slide]');
        var thumbs = slider.querySelectorAll('[data-hero-target]');
        var target = slider.querySelector('[data-hero-slide="' + targetIndex + '"]');

        if (!target) return;

        slides.forEach(function (slide) {
            var active = slide === target;
            slide.classList.toggle('is-active', active);
            slide.setAttribute('aria-hidden', active ? 'false' : 'true');
        });

        thumbs.forEach(function (thumb) {
            var active = thumb.getAttribute('data-hero-target') === String(targetIndex);
            thumb.classList.toggle('is-active', active);
            thumb.setAttribute('aria-pressed', active ? 'true' : 'false');
        });

        slider.setAttribute('data-active-slide', String(targetIndex));
    }

    function initialize() {
        document.querySelectorAll('[data-phim-hero-slider]').forEach(function (slider) {
            slider.addEventListener('click', function (event) {
                var thumb = event.target.closest('[data-hero-target]');
                if (!thumb || !slider.contains(thumb)) return;

                activateSlide(slider, thumb.getAttribute('data-hero-target'));
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }
})();
