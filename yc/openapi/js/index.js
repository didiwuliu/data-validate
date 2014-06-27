/**
 * @title index
 * @description
 * index
 * @author zhangchunsheng423@gmail.com
 * @version V1.0
 * @date 2014-06-27
 * @copyright  Copyright (c) 2014-2014 Luomor Inc. (http://www.luomor.com)
 */
var url = "http://data-validate.yongche.org/index.php?method=all_price";
var url = "http://data-validate.yongche.test/index.php?method=all_price";

var errors = [];

$(document).ready(function() {
    $("#check").click(function() {
        clear();
        $("#wait").html("校验中...");
        $.ajax({
            url: url,
            type: "get",
            success: function(data) {
                $("#wait").html("正在校验...");
                var result = JSON.parse(data);

                if(typeof result.result == "undefined") {
                    $("#wait").html("网络连接错误，请重试");
                    return;
                }

                result = result.result;
                var price_list = result.price_list;

                var car_type;
                var car_type_value;
                var product_list;
                var product_list_value;
                var valid_key_array;
                var flag = true;
                var valid_value;
                var product_type_value;

                var log = "";
                errors = [];

                for(var key in price_list) {
                    car_type = price_list[key];
                    for(var car_type_key in car_type) {
                        car_type_value = car_type[car_type_key];
                        if(typeof car_type_value.name != "undefined"
                            && typeof car_type_value.person_number != "undefined"
                            && typeof car_type_value.order_id != "undefined"
                            && typeof car_type_value.desc != "undefined"
                            && typeof car_type_value.list != "undefined") {
                            product_list = car_type_value.list;
                            for(var product_list_key in product_list) {
                                product_list_value = product_list[product_list_key];
                                if(product_list_key == 1) {
                                    valid_key_array = [
                                        "min_fee",
                                        "fee_per_hour",
                                        "fee_per_kilometer",
                                        "night_service_fee",
                                        "min_response_time",
                                        "min_time_length",
                                        "granularity",
                                        "cancel_time"
                                    ];
                                    flag = true;
                                    for(var valid_key in valid_key_array) {
                                        valid_value = valid_key_array[valid_key];
                                        if(product_list_value[valid_value] == null) {
                                            flag = false;
                                            log = "city:" + key + ",car_type:" + car_type_key + ",product_type:" + product_list_key + ", " + valid_value + " is not set\n";
                                            error(log);
                                        }
                                    }
                                    if(flag) {
                                        log = "city:" + key + ",car_type:" + car_type_key + ",product_type:" + product_list_key + " is right\n";
                                        info(log);
                                    }
                                } else if(product_list_key == 7 || product_list_key == 8 || product_list_key == 11 || product_list_key == 12) {
                                    for(var product_type_key in product_list_value) {
                                        product_type_value = product_list_value[product_type_key];
                                        valid_key_array = [
                                            "fee",
                                            "min_fee",
                                            "fee_per_hour",
                                            "fee_per_kilometer",
                                            "night_service_fee",
                                            "distance",
                                            "time_length",
                                            "cancel_time",
                                            "min_response_time",
                                            "is_activity",
                                            "granularity"
                                        ];
                                        flag = true;
                                        for(var valid_key in valid_key_array) {
                                            valid_value = valid_key_array[valid_key];
                                            if(product_type_value[valid_value] == null) {
                                                flag = false;
                                                log = "city:" + key + ",car_type:" + car_type_key + ",product_type:" + product_list_key + ", " + valid_value + " is not set\n";
                                                error(log);
                                            }
                                        }
                                        if(flag) {
                                            log = "city:" + key + ",car_type:" + car_type_key + ",product_type:" + product_list_key + " is right\n";
                                            info(log);
                                        }
                                    }
                                }
                            }
                        } else {
                            log = "city:" + key + ",car_type:" + car_type_key + " has an error. name,person_number,order_id,desc is not set\n";
                            error(log);
                        }
                    }
                }
                $("#wait").html("校验完毕...");
                showErrors();
            }
        });
    });
});

function clear() {
    $("#result").html("");
    $("#errors").html("");
}

function info(content) {
    log("result", content, "green");
}

function error(content) {
    errors.push(content);
    log("result", content, "red");
}

function log(id, content, color) {
    var result = $("#" + id);
    result.append($("<li style=\"color:" + color + "\">" + content + "</li>"));
}

function showErrors() {
    var errors_div = $("#errors");
    if(errors.length == 0) {
        errors_div.append("数据正确")
    } else {
        for(var error in errors) {
            log("errors", errors[error], "red");
        }
    }
}