(function () {
    var mobileQuery = window.matchMedia('(max-width: 991.98px)');

    function closeOverlay(header, overlay, launch) {
        overlay.classList.remove('is-open');
        launch.classList.remove('responsive-button-close');
        launch.setAttribute('aria-expanded', 'false');
    }

    function setupSubmenus(root) {
        root.querySelectorAll('li').forEach(function (item) {
            var submenu = item.querySelector(':scope > ul');

            if (!submenu) {
                item.classList.add('last-li');
                return;
            }

            item.classList.add('parent-ul');
            submenu.style.display = 'none';

            var link = item.querySelector(':scope > a');
            if (!link) {
                return;
            }

            link.addEventListener('click', function (event) {
                if (!mobileQuery.matches) {
                    return;
                }

                if (submenu.style.display === 'block') {
                    return;
                }

                event.preventDefault();

                item.parentElement.querySelectorAll(':scope > li.parent-ul > ul').forEach(function (list) {
                    if (list !== submenu) {
                        list.style.display = 'none';
                    }
                });

                submenu.style.display = 'block';
            });
        });
    }

    function ensureResponsiveMarkup(headerNav) {
        if (headerNav.querySelector('.ccm-responsive-navigation.original')) {
            return;
        }

        var menu = headerNav.querySelector('ul.nav');
        if (!menu) {
            return;
        }

        var nav = document.createElement('nav');
        nav.className = 'ccm-responsive-navigation original';
        menu.parentNode.insertBefore(nav, menu);
        nav.appendChild(menu);

        if (!headerNav.querySelector('.ccm-responsive-menu-launch')) {
            var launch = document.createElement('div');
            launch.className = 'ccm-responsive-menu-launch';
            launch.innerHTML = '<i></i>';
            headerNav.appendChild(launch);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.theme-a-thousand-words .atw-header').forEach(function (header) {
            var headerNav = header.querySelector('.atw-header-nav');
            if (!headerNav) {
                return;
            }

            ensureResponsiveMarkup(headerNav);

            var original = headerNav.querySelector('.ccm-responsive-navigation.original');
            var launch = headerNav.querySelector('.ccm-responsive-menu-launch');
            if (!original || !launch) {
                return;
            }

            launch.setAttribute('role', 'button');
            launch.setAttribute('tabindex', '0');
            launch.setAttribute('aria-expanded', 'false');
            launch.setAttribute('aria-label', 'Toggle navigation');

            var overlay = header.querySelector('.ccm-responsive-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'ccm-responsive-overlay';
                header.appendChild(overlay);
            }

            if (!overlay.querySelector('.ccm-responsive-navigation')) {
                var clone = original.cloneNode(true);
                clone.classList.remove('original');
                clone.querySelectorAll('[id]').forEach(function (element) {
                    element.id = 'cloned-ccm-ro_' + element.id;
                });
                overlay.appendChild(clone);
                setupSubmenus(clone);
            }

            launch.addEventListener('click', function () {
                var isOpen = !overlay.classList.contains('is-open');
                if (isOpen) {
                    overlay.classList.add('is-open');
                    launch.classList.add('responsive-button-close');
                    launch.setAttribute('aria-expanded', 'true');
                } else {
                    closeOverlay(header, overlay, launch);
                }
            });

            launch.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    launch.click();
                }
            });

            document.addEventListener('click', function (event) {
                if (!header.contains(event.target)) {
                    closeOverlay(header, overlay, launch);
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeOverlay(header, overlay, launch);
                }
            });

            mobileQuery.addEventListener('change', function () {
                closeOverlay(header, overlay, launch);
            });
        });
    });
})();
