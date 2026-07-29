(function () {
    'use strict';

    var storageKey = 'phim_favourites_v1';
    var toastTimer;

    function readFavourites() {
        try {
            var value = JSON.parse(window.localStorage.getItem(storageKey) || '[]');
            return Array.isArray(value) ? value : [];
        } catch (error) {
            return [];
        }
    }

    function writeFavourites(items) {
        try {
            window.localStorage.setItem(storageKey, JSON.stringify(items));
            return true;
        } catch (error) {
            return false;
        }
    }

    function isFavourite(items, movieId) {
        return items.some(function (item) {
            return String(item.id) === String(movieId);
        });
    }

    function updateFavouriteButton(button, active) {
        var movieName = button.getAttribute('data-movie-name') || 'phim';
        var label = active
            ? 'Xóa ' + movieName + ' khỏi yêu thích'
            : 'Thêm ' + movieName + ' vào yêu thích';

        button.classList.toggle('is-active', active);
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
        button.setAttribute('aria-label', label);
        button.setAttribute('title', active ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích');
    }

    function syncFavouriteButtons(movieId, active) {
        document.querySelectorAll('[data-phim-favourite]').forEach(function (button) {
            if (String(button.getAttribute('data-movie-id')) === String(movieId)) {
                updateFavouriteButton(button, active);
            }
        });
    }

    function showToast(message) {
        var toast = document.querySelector('.phim-action-toast');

        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'phim-action-toast';
            toast.setAttribute('role', 'status');
            toast.setAttribute('aria-live', 'polite');
            document.body.appendChild(toast);
        }

        toast.textContent = message;
        toast.classList.add('is-visible');
        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(function () {
            toast.classList.remove('is-visible');
        }, 1800);
    }

    function toggleFavourite(button) {
        var movieId = button.getAttribute('data-movie-id');

        if (!movieId) {
            showToast('Không thể lưu phim này');
            return;
        }

        var items = readFavourites();
        var active = isFavourite(items, movieId);

        if (active) {
            items = items.filter(function (item) {
                return String(item.id) !== String(movieId);
            });
        } else {
            items.unshift({
                id: movieId,
                name: button.getAttribute('data-movie-name') || '',
                url: button.getAttribute('data-movie-url') || window.location.href,
                poster: button.getAttribute('data-movie-poster') || '',
                addedAt: new Date().toISOString()
            });
        }

        if (!writeFavourites(items)) {
            showToast('Trình duyệt không cho phép lưu yêu thích');
            return;
        }

        syncFavouriteButtons(movieId, !active);
        showToast(active ? 'Đã xóa khỏi yêu thích' : 'Đã thêm vào yêu thích');
    }

    function fallbackCopy(text) {
        var input = document.createElement('textarea');
        input.value = text;
        input.setAttribute('readonly', '');
        input.style.position = 'fixed';
        input.style.opacity = '0';
        document.body.appendChild(input);
        input.select();

        var copied = document.execCommand('copy');
        document.body.removeChild(input);

        return copied;
    }

    function markCopied(button) {
        var icon = button.querySelector('i');
        var previousClass = icon ? icon.className : '';

        button.classList.add('is-copied');
        button.setAttribute('title', 'Đã sao chép');
        if (icon) {
            icon.className = 'fa fa-check';
        }

        window.setTimeout(function () {
            button.classList.remove('is-copied');
            button.setAttribute('title', 'Sao chép liên kết');
            if (icon) {
                icon.className = previousClass;
            }
        }, 1500);
    }

    function copyMovieUrl(button) {
        var url = button.getAttribute('data-share-url') || window.location.href;
        url = new URL(url, window.location.origin).href;

        var copyPromise;
        if (navigator.clipboard && window.isSecureContext) {
            copyPromise = navigator.clipboard.writeText(url);
        } else {
            copyPromise = Promise.resolve(fallbackCopy(url));
        }

        copyPromise.then(function (copied) {
            if (copied === false) {
                throw new Error('Copy failed');
            }
            markCopied(button);
            showToast('Đã sao chép liên kết');
        }).catch(function () {
            showToast('Không thể sao chép liên kết');
        });
    }

    function initialize() {
        var items = readFavourites();

        document.querySelectorAll('[data-phim-favourite]').forEach(function (button) {
            updateFavouriteButton(
                button,
                isFavourite(items, button.getAttribute('data-movie-id'))
            );
        });

        document.addEventListener('click', function (event) {
            var favouriteButton = event.target.closest('[data-phim-favourite]');
            var shareButton = event.target.closest('[data-phim-share]');

            if (favouriteButton) {
                toggleFavourite(favouriteButton);
            } else if (shareButton) {
                copyMovieUrl(shareButton);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }
})();
