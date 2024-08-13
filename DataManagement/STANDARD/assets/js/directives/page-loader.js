'use strict';
/** 
 * A directive used for "close buttons" (eg: alert box).
 * It hides its parent node that has the class with the name of its value.
 */
app.directive('pageLoader', [
    '$timeout', '$transitions', '$rootScope',

    function($timeout, $transitions, $rootScope, scope) {
        return {
            restrict: 'AE',
            template: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div>',
            link: function(scope, el, attrs) {
                el.addClass('pageLoader animate');
                scope.$on('$viewContentLoaded', function(event) {
                    $timeout(function() {
                        el.fadeOut("slow");
                    }, 600);
                });
                /*    $transitions.onStart({}, function(trans) {
                        el.toggleClass('hide animate');
                    });*/






                /* scope.$on('$stateChangeStart', function(event) {
                     el.toggleClass('hide animate');
                 });*/
                /* scope.$watch('$viewContentLoaded', function(event) {
                     $timeout(function() {
                         el.toggleClass('hide animate')
                     }, 600);
                 });*/
                /*el.addClass('pageLoader animate');
                $timeout(function() {
                    el.removeClass('hide animate')

                }, 1600);*/
                /*    el.addClass('pageLoader hide');

                    scope.$on('$viewContentLoaded', function(event) {
                        $timeout(function() {
                            el.toggleClass('hide animate')
                        }, 600);
                    });*/
                /*
                                $transitions.onSuccess({}, function(trans) {
                                    $timeout(function() {
                                        el.removeClass('hide animate')
                                    }, 600);
                                });

                */
            }
        };
    }
])