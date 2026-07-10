(function ($) {
    'use strict';

    $(function () {
        var $menu = $('#menu');
        var $mobileNav = $('#slicknav');

        if (!$menu.length || !$mobileNav.length || typeof $.fn.slicknav !== 'function') {
            return;
        }

        var $brandLink = $('<a>', {
            href: $mobileNav.attr('data-home-url') || '/',
            text: $mobileNav.attr('data-brand') || ''
        });
        var brand = $('<div>').append($brandLink).html();

        $menu.slicknav({
            label: '',
            duration: 200,
            easingOpen: 'swing',
            easingClose: 'swing',
            closedSymbol: '&#9658;',
            openedSymbol: '&#9660;',
            prependTo: '#slicknav',
            appendTo: '',
            parentTag: 'a',
            closeOnClick: false,
            allowParentLinks: true,
            nestedParentLinks: true,
            showChildren: false,
            removeIds: true,
            removeClasses: false,
            removeStyles: false,
            brand: brand
        });
    });
}(jQuery));
