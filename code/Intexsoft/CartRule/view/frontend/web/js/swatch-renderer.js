define(
    [
        'jquery'
    ],
    function ($) {
    'use strict';

    return function (SwatchRenderer) {
        let discountAmount = document.querySelector("#DiscountAmount");
        let status = document.querySelector("#IsActive");
        let base_subtotal = document.querySelector("#ConditionsSerialized");
        let cartTotal = document.querySelector(".content");

        let getJSON = function (url, callback) {

            let xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.responseType = 'json';
            xhr.onload = function () {

                let status = xhr.status;

                if (status === 200) {
                    callback(null, xhr.response);
                } else {
                    callback(status);
                }
            };

            xhr.send();
        };

        var graph = document.createElement('div');
        graph.id = "cart-info";
        cartTotal.append(graph);
        cartTotal = document.querySelector("#cart-info");

        $.widget('mage.SwatchRenderer', $['mage']['SwatchRenderer'], {
            _init: function () {
                let $widget = this;
                this._getJsonCart();
                $(document).ajaxComplete(function () {
                    $widget._getJsonCart();
                });
                this._super();
            },
            /**
             * Get prices
             *
             * @param {Object} newPrices
             * @param {Object} displayPrices
             * @returns {*}
             * @private
             */
            _getPrices: function (newPrices, displayPrices) {
                var $widget = this;
                let basePrice = displayPrices["baseOldPrice"].amount;
                if (_.isEmpty(newPrices)) {
                    newPrices = $widget._getNewPrices();
                }
                _.each(displayPrices, function (price, code) {
                    if (newPrices[code]) {
                        if (displayPrices[code].amount === basePrice
                            && (cartTotal.textContent !== null && parseFloat(cartTotal.textContent) > parseFloat(base_subtotal.textContent))
                            && status.textContent !== "0") {
                            displayPrices[code].amount = newPrices[code].amount - displayPrices[code].amount - (displayPrices[code].amount * discountAmount.textContent) / 100;
                        } else displayPrices[code].amount = newPrices[code].amount - displayPrices[code].amount;
                    }
                });

                    return displayPrices;
                },
                _UpdatePrice: function () {
                    var $widget = this,
                        $product = $widget.element.parents($widget.options.selectorProduct),
                        $productPrice = $product.find(this.options.selectorProductPrice),
                        result = $widget._getNewPrices(),
                        tierPriceHtml,
                        isShow;

                    $productPrice.trigger(
                        'updatePrice',
                        {
                            'prices': $widget._getPrices(result, $productPrice.priceBox('option').prices)
                        }
                        );

                    isShow = true;

                    $productPrice.find('span:first').toggleClass('special-price', isShow);

                    $product.find(this.options.slyOldPriceSelector)[isShow ? 'show' : 'hide']();

                    if (typeof result != 'undefined' && result.tierPrices && result.tierPrices.length) {
                        if (this.options.tierPriceTemplate) {
                            tierPriceHtml = mageTemplate(
                                this.options.tierPriceTemplate,
                                {
                                    'tierPrices': result.tierPrices,
                                    '$t': $t,
                                    'currencyFormat': this.options.jsonConfig.currencyFormat,
                                    'priceUtils': priceUtils
                                }
                                );
                            $(this.options.tierPriceBlockSelector).html(tierPriceHtml).show();
                        }
                    } else {
                        $(this.options.tierPriceBlockSelector).hide();
                    }

                    $(this.options.normalPriceLabelSelector).hide();

                    _.each($('.' + this.options.classes.attributeOptionsWrapper), function (attribute) {
                        if ($(attribute).find('.' + this.options.classes.optionClass + '.selected').length === 0) {
                            if ($(attribute).find('.' + this.options.classes.selectClass).length > 0) {
                                _.each($(attribute).find('.' + this.options.classes.selectClass), function (dropdown) {
                                    if ($(dropdown).val() === '0') {
                                        $(this.options.normalPriceLabelSelector).show();
                                    }
                                }.bind(this));
                            } else {
                                $(this.options.normalPriceLabelSelector).show();
                            }
                        }
                    }.bind(this));
                },
                //get cart total and update catalog price
                _getJsonCart: function () {
                    let $widget = this;
                    getJSON('http://bsh.loc/default/customer/section/load/?sections=cart', function (err, data) {
                        if (err != null) {
                            console.error(err);
                        } else {
                            cartTotal.textContent = `${data.cart.subtotalAmount}`;
                            $widget._UpdatePrice();
                        }
                    });
                },

        });
        return $['mage']['SwatchRenderer'];
    };
});
