<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2013 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://openpayu.com
 * http://twitter.com/openpayu
 *
 */
require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';

OpenPayU_Configuration::setApiVersion(2);
require_once realpath(dirname(__FILE__)) . '/../../config.php';
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Transparent Payment Process - OpenPayU v2</title>
    <link rel="stylesheet" href="<?php echo EXAMPLES_DIR;?>layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo EXAMPLES_DIR;?>layout/css/style.css">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Transparent Payment Process - OpenPayU v2</h1>
    </div>
    <div id="message"></div>
    <div id="unregisteredCardData">
        <div id="card_container">
            <div id="cardTopWraper" class="clearfix">
                <div class="pull-left">
                    <img class="pull-left" src="<?php echo EXAMPLES_DIR;?>layout/img/loglock_small.png" id="cardlock">
                </div>
                <div class="pull-right">
                    <img src="<?php echo EXAMPLES_DIR;?>layout/img/footLogo4Transparent.png" id="cardPci">
                    <img src="<?php echo EXAMPLES_DIR;?>layout/img/footLogo3Transparent.png" id="cardVerVisa">
                    <img src="<?php echo EXAMPLES_DIR;?>layout/img/footLogo2Transparent.png" id="cardSecMaster">
                </div>
            </div>
            <div id="cardInfo">
                <form id="card-form-payment" action="#">
                    <div id="payu-payment" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="amount">Payment amount</label>
                            <div class="controls">
                                <input id="amount" class="disabled span3 payment-amount" disabled="disabled"
                                       type="text" value="100,00"/> PLN
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="card-number">Card number</label>
                            <div class="controls">
                                <input id="card-number" class="payu-card-number required span3"
                                       name="credit-card-number" type="text" autocomplete="off" value="">
                            </div>
                            <div id="sCard"></div>
                            <div id="imgCard"></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="card-month">Expiry date</label>
                            <div class="controls">
                                <input class="payu-card-expm span1" id="card-month" type="text" size="2" maxlength="2"
                                       autocomplete="off" value=""/>
                                <span class="delimiter">/</span>
                                <input class="payu-card-expy span1" id="card-year" type="text" size="4" maxlength="4"
                                       autocomplete="off" value=""/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="card-cvv">CVV2/CVC2</label>
                            <div class="controls">
                                <input id="cvv" class="payu-card-cvv span1" type="text" size="2" maxlength="4"
                                       autocomplete="off" value="">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pay-button"></label>

                            <div class="controls">
                                <button class="btn btn-success" id="pay-button" type="button">Pay via Card</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div id="modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">CVV/CVV2</h3>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="cvv-button">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo EXAMPLES_DIR;?>layout/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo EXAMPLES_DIR;?>layout/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo EXAMPLES_DIR;?>layout/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo EXAMPLES_DIR;?>layout/js/jquery.maskedinput.min.js"></script>
<script src="https://secure.payu.com/res/v2/jquery-1.7.2.js"></script>
<script src="https://secure.payu.com/res/v2/openpayu-2.0.js"></script>
<script src="https://secure.payu.com/res/v2/plugin-token-2.0.js"></script>
<script type="text/javascript">
    OpenPayU.merchantId = '<?php echo OpenPayU_Configuration::getMerchantPosId();?>';
    OpenPayU.shopBackEnd = window.location.href.replace('OrderCreate.php', '') + 'TransparentOrderCreateRequest.php';

        clearCardNumber();
        $('#pay-button').click(function () {
            $('#card-form-payment input').removeClass('error');
            $('.payu-card-number').val(OpenPayU.Validation.normalize($('.payu-card-number').val()));
            OpenPayU.Token.create({}, function (data) {
                processTokenCreateResponse(data);
            });
        });

        function clearCardNumber() {
            $('.payu-card-number').val('');
            $('.payu-card-number').unmask();
            $('.payu-card-number').mask("?9999 9999 9999 9999 999", {placeholder: " "});
        }

        function processTokenCreateResponse(data) {
            console.log('<- token create callback, data: ' + JSON.stringify(data));

            $('.payu-card-number').unmask();
            $('.payu-card-number').mask("?9999 9999 9999 9999 999", {placeholder: " "});
            if (data.status.statusCode == 'SUCCESS') {
                processTransparentPayment(data);
            }
            else {
                cardValidate(data.status.code);
            }
        }

        function cardValidate(statusCode)
        {
            switch (statusCode) {
                case "4000":// INVALID_NUMBER
                    $('.payu-card-number').addClass('error');
                    showErrorMessage('<strong>Error!</strong> Invalid card number');
                    break;
                case "4001":// INVALID_EXPIRY_MONTH
                    $('.payu-card-expm').addClass('error');
                    showErrorMessage('<strong>Error!</strong> Invalid value of month');
                    break;
                case "4002":// INVALID_EXPIRY_YEAR
                    $('.payu-card-expy').addClass('error');
                    showErrorMessage('<strong>Error!</strong> Invalid value of year');
                    break;
                case "4003":// INVALID_CVV
                    $('.payu-card-cvv').addClass('error');
                    showErrorMessage('<strong>Error!</strong> Invalid cvv');
                    break;
                case "4004":// CARD_EXPIRED
                    $('.payu-card-number').addClass('error');
                    showErrorMessage('<strong>Error!</strong> Invalid card number');
                    break;
                /* 3DS authorization*/
                case "117":
                    showErrorMessage(data.status.statusCode + ': ' + data.status.code);
                    break;
                /* CVV authorization*/
                case "116":
                    showErrorMessage(data.status.statusCode + ': ' + data.status.code);
                    break;
                default:
                    showErrorMessage(data.status.statusCode + ': ' + data.status.code);
                    break;
            }
        }

        function processTransparentPayment(data)
        {
            $.post(OpenPayU.shopBackEnd, data,
                function (response) {
                /* You should remove that line*/
                console.log('merchant backend, data: ' + JSON.stringify(data));
                data = OPU.parseJSON(response);
                if (data.status.StatusCode == 'WARNING_CONTINUE_CVV') {
                    $('#cardInfo .payu-card-cvv').remove();
                    $(".modal-body").html('<input type="text" size="1" class="payu-card-cvv error span1" value="" />').show();
                    $('#modal').modal({keyboard: false});
                    $('.payu-card-cvv').focus();
                    $('#cvv-button').click(function () {
                        OpenPayU.authorizeCVV({url: data.RedirectUri}, function (data) {
                            if (data.status.statusCode == 'SUCCESS') {
                                $("#modal").modal('hide');
                                $(".modal-body").html('');
                                showSuccessMessage('Well done! Your card has been charged ' + $('.payment-amount').val() + ' PLN.');
                                /* You should remove that line*/
                                console.log('CVV send, data: ' + JSON.stringify(data));
                            } else {
                                showErrorMessage('<strong>Error!</strong> You can not make a payment');
                                /* You should remove that line*/
                                console.log('CVV error, data: ' + JSON.stringify(data));
                            }
                        });
                    })
                }
                else if (data.status.StatusCode == 'WARNING_CONTINUE_3DS') {
                    window.location.href = data.RedirectUri
                }
                else if (data.status.StatusCode == 'SUCCESS') {
                    showSuccessMessage('Well done! Your card has been charged ' + $('.payment-amount').val() + ' PLN.');
                }
                else {
                    showErrorMessage('<strong>Error!</strong> You can not make a payment');
                }
            });
        }

        function showErrorMessage(text) {
            $('#message').html('<div class="alert alert-error">' + text + '</div>')
        }

        function showSuccessMessage(text) {
            $('#message').html('<div class="alert alert-success">' + text + '</div>')
        }
</script>
</body>
</html>