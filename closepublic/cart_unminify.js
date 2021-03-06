function CartTotalElements() {
    $.ajax({
        url: "/cart/GetTotalElements",
        data: "",
        dataType: "text",
        type: "GET",
        error: function(t, e, a) {
            alert(client_cart_e01)
        },
        success: function(t) {
            $("#cart_totalelements").html(t + " " + client_cart_things)
        }
    })
}

function CartTotalSum() {
    $.ajax({
        url: "/cart/GetTotalSum",
        dataType: "text",
        type: "GET",
        error: function(t, e, a) {
            alert(client_cart_e01)
        },
        success: function(t) {
            $("#cart_totalsum").html(t + " " + client_cart_currency_grn)
        }
    })
}

function CartReload() {
    $.ajax({
        url: "/cart/reload",
        dataType: "html",
        type: "GET",
        error: function(t, e, a) {
            alert(client_cart_e01)
        },
        success: function(t) {
            var e = $(t).find("#cart_content");
            $("#cart_content").html(e)
        }
    })
}

function ElementAdd(t, e) {
    $.ajax({
        url: "/cart/addElement/" + t + "/" + e,
        dataType: "html",
        type: "GET",
        error: function(t, e, a) {
            alert(client_cart_e01)
        },
        success: function(t) {
            if (0 != t) {
                var e = $(t).filter("#totalsum"),
                    a = $(t).filter("#totalitems");
                $("#cart_totalsum").html(e.html()), $("#cart_totalitems").html(a.html()), $(".cart_nums").html(a.html()), opendialog()
            } else alert(client_cart_e02)
        }
    })
}

function ElementEdit(t, e) {
    $.ajax({
        url: "/cart/ElementEdit/" + t + "/" + e,
        dataType: "text",
        type: "GET",
        error: function(t, e, a) {
            alert(client_cart_e01)
        },
        success: function(t) {
            if (0 != t) {
                var e = JSON.parse(t);
                $("#sumorder").val(e.sum.toFixed(2)), $("#cart_totalsum").html(e.sum), $("#cart_totalitems").html(e.count), $(".cart_nums").html(e.count), CartReload()
            } else alert(client_cart_e02)
        }
    })
}

function ElementDelete(t) {
    $.ajax({
        url: "/cart/ElementDelete/" + t,
        dataType: "text",
        type: "GET",
        error: function(t, e, a) {
            alert(client_cart_e01)
        },
        success: function(t) {
            if (0 != t) {
                var e = JSON.parse(t);
                $("#sumorder").val(e.sum), $("#cart_totalsum").html(e.sum), $("#cart_totalitems").html(e.count), $(".cart_nums").html(e.count), CartReload()
            } else alert(client_cart_e03)
        }
    })
}

function mustAdress() {
    1 != $("#delivery").val() ? $("#adress").attr("required", "required") : $("#adress").removeAttr("required")
}

function opendialog(t) {
    void 0 === t && (t = ".prodaddform"), $(".overlay").css("visibility", "visible"), $(".overlay").css("opacity", "1"), $(t).fadeIn()
}

function closedialog(t) {
    void 0 === t && (t = ".prodaddform"), $(t).fadeOut(), $(".overlay").css("visibility", "hidden"), $(".overlay").css("opacity", "0")
}
$(document).ready(function() {
    $("#cart_button").on("mouseenter click", function(t) {
        "0 " == $("#cart_totalitems").html() ? ($(".cart_sub_content").hide(), $(".cart_empty").show()) : ($(".cart_sub_content").show(), $(".cart_empty").hide()), "block" !== $(".cart_sub").css("display") && ($(".cart_sub").toggle(), t.preventDefault()), $("#cart_button").click(function(t) {
            $(".cart_sub").toggle(), t.preventDefault()
        })
    }), $(".cart_clear").click(function(t) {
        $.ajax({
            url: "/cart/ajaxclear/",
            dataType: "text",
            type: "GET",
            error: function(t, e, a) {
                alert("cart_clear error")
            },
            success: function(t) {
                $("#cart_totalsum").html("0 "), $("#cart_totalitems").html("0 "), $(".cart_nums").html("0 "), CartReload()
            }
        }), t.preventDefault()
    }), $("#cbform").submit(function() {
        var t = $(this).attr("id"),
            e = $("#" + t);
        return $.ajax({
            type: "POST",
            url: "/ajax/callback",
            data: e.serialize(),
            success: function(t) {
                closedialog(".callbackform"), alert(t)
            },
            error: function(t, e, a) {
                alert(a)
            }
        }), !1
    });

    var sum = parseInt($('#cart_sum_all').text());
    var min_sum = parseInt($('#min_sum').text());
    if(sum) {
        if (sum < min_sum) {
            $('#cartmessage').append('Сумма заказа должна быть не менее '+ min_sum +' грн').css('display', 'block');
        }
        $('#getorder').click(function (e) {

            if (sum < min_sum) {
                e.preventDefault();
                $('#cartmessage').text('Сумма заказа должна быть не менее '+ min_sum +' грн').css('display', 'block');

            }
            else {
                $('.formorder').submit();
            }

        });

        setInterval(function () {
            if (!$('#cart_sum_all').length > 0) {
                $('#cartmessage').text('').css('display', 'none');
            }
            else {
                var sum = parseInt($('#cart_sum_all').text());

                if (sum > min_sum) {

                    $('#cartmessage').text('').css('display', 'none');
                }
                if (sum < min_sum) {
                    $('#cartmessage').text('Сумма заказа должна быть не менее ' + min_sum + ' грн').css('display', 'block');
                }
            }

        }, 2000);
    }

});
