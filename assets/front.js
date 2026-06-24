(function(){
    var queue = [];
    var current = null;
    var autoCloseTimer = null;
    var showTimer = null;
    var autoplayTimers = new WeakMap();

    function getCookie(name){
        var match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/[.$?*|{}()\[\]\\\/\+^]/g, '\\$&') + '=([^;]*)'));
        return match ? decodeURIComponent(match[1]) : null;
    }

    function setCookie(name, value, days){
        var expires = '';
        if(days){
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/; SameSite=Lax';
    }

    function openPopup(popup){
        if(!popup){return;}
        current = popup;
        popup.hidden = false;
        popup.classList.add('is-open');
        popup.setAttribute('aria-hidden', 'false');
        document.body.classList.add('eoksp-lock-scroll');
        initSlider(popup);

        var autoClose = parseInt(popup.dataset.autoClose || '0', 10);
        if(autoClose > 0){
            autoCloseTimer = window.setTimeout(function(){ closeCurrent(false); }, autoClose * 1000);
        }
    }

    function closeCurrent(showNext, hideForToday){
        if(!current){return;}
        if(hideForToday && current.dataset.hideToday === '1'){
            var days = parseInt(current.dataset.cookieDays || '1', 10) || 1;
            setCookie('eoksp_hide_' + current.dataset.popupId, '1', days);
        }
        destroySlider(current);
        current.classList.remove('is-open');
        current.setAttribute('aria-hidden', 'true');
        current.hidden = true;
        current = null;
        window.clearTimeout(autoCloseTimer);
        autoCloseTimer = null;
        document.body.classList.remove('eoksp-lock-scroll');
        if(showNext){
            runQueue();
        }
    }

    function runQueue(){
        window.clearTimeout(showTimer);
        if(current || !queue.length){return;}
        var next = queue.shift();
        var delay = parseInt(next.dataset.openDelay || '0', 10);
        showTimer = window.setTimeout(function(){ openPopup(next); }, Math.max(0, delay));
    }

    function updateDots(popup, index){
        popup.querySelectorAll('[data-dot]').forEach(function(dot){
            dot.classList.toggle('is-active', parseInt(dot.dataset.dot, 10) === index);
        });
    }

    function goToSlide(popup, index){
        var track = popup.querySelector('[data-slides-track]');
        var slides = popup.querySelectorAll('.eoksp-slide');
        if(!track || !slides.length){return;}
        var max = slides.length - 1;
        if(index < 0){ index = popup.dataset.sliderLoop === '1' ? max : 0; }
        if(index > max){ index = popup.dataset.sliderLoop === '1' ? 0 : max; }
        popup.dataset.currentSlide = String(index);
        track.style.transform = 'translateX(' + (index * -100) + '%)';
        updateDots(popup, index);
    }

    function nextSlide(popup){
        var currentIndex = parseInt(popup.dataset.currentSlide || '0', 10);
        goToSlide(popup, currentIndex + 1);
    }

    function prevSlide(popup){
        var currentIndex = parseInt(popup.dataset.currentSlide || '0', 10);
        goToSlide(popup, currentIndex - 1);
    }

    function startAutoplay(popup){
        stopAutoplay(popup);
        if(popup.dataset.sliderAutoplay !== '1'){return;}
        var slides = popup.querySelectorAll('.eoksp-slide');
        if(slides.length < 2){return;}
        var speed = parseInt(popup.dataset.sliderSpeed || '3500', 10);
        var timer = window.setInterval(function(){ nextSlide(popup); }, Math.max(1200, speed));
        autoplayTimers.set(popup, timer);
    }

    function stopAutoplay(popup){
        var timer = autoplayTimers.get(popup);
        if(timer){
            window.clearInterval(timer);
            autoplayTimers.delete(popup);
        }
    }

    function initSlider(popup){
        popup.dataset.currentSlide = '0';
        goToSlide(popup, 0);

        var prev = popup.querySelector('[data-prev]');
        var next = popup.querySelector('[data-next]');
        if(prev){
            prev.addEventListener('click', popup._eokspPrevHandler = function(){ prevSlide(popup); startAutoplay(popup); });
        }
        if(next){
            next.addEventListener('click', popup._eokspNextHandler = function(){ nextSlide(popup); startAutoplay(popup); });
        }
        popup.querySelectorAll('[data-dot]').forEach(function(dot){
            dot.addEventListener('click', function(){
                goToSlide(popup, parseInt(dot.dataset.dot, 10));
                startAutoplay(popup);
            });
        });
        startAutoplay(popup);
    }

    function destroySlider(popup){
        stopAutoplay(popup);
        var prev = popup.querySelector('[data-prev]');
        var next = popup.querySelector('[data-next]');
        if(prev && popup._eokspPrevHandler){
            prev.removeEventListener('click', popup._eokspPrevHandler);
            popup._eokspPrevHandler = null;
        }
        if(next && popup._eokspNextHandler){
            next.removeEventListener('click', popup._eokspNextHandler);
            popup._eokspNextHandler = null;
        }
    }

    document.addEventListener('DOMContentLoaded', function(){
        var popups = Array.prototype.slice.call(document.querySelectorAll('.eoksp-queue-item'));
        popups.forEach(function(popup){
            var isPreview = popup.dataset.preview === '1';
            if(!isPreview && getCookie('eoksp_hide_' + popup.dataset.popupId) === '1'){
                return;
            }
            var overlay = popup.querySelector('[data-overlay]');
            if(overlay){
                overlay.addEventListener('click', function(){
                    if(popup.dataset.overlayClose === '1'){ closeCurrent(true); }
                });
            }
            popup.querySelectorAll('[data-close]').forEach(function(btn){
                btn.addEventListener('click', function(){ closeCurrent(true); });
            });
            popup.querySelectorAll('[data-hide-today]').forEach(function(btn){
                btn.addEventListener('click', function(){ closeCurrent(true, true); });
            });
            queue.push(popup);
        });

        document.addEventListener('keydown', function(e){
            if(e.key === 'Escape' && current){
                closeCurrent(true);
            }
        });

        runQueue();
    });
})();
