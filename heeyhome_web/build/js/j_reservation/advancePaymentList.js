/*! Pc.Heigh-ho.Renovation-1.0.0.js 2017-03-23 */
!function(){var a=angular.module("heeyhomeApp"),b="/api/public/order/aeckonandactual/seldata",c="/api/public/alipay/pay",d=!0,e={init:function(){e.initEvent()},initEvent:function(){var a=this;a.initWebDataEvent(),a.initPaySumbitEvent(),a.initRefundEvent()},initWebDataEvent:function(){var a=this;a.initPaymentListDetailsEvent()},initPaymentListDetailsEvent:function(){getDataForAjaxHandler.getDataEvent()},initPaySumbitEvent:function(){var a=getUrlParamHandler.getUrlParam("pos");$(document).on("click","#checkYt",function(){d&&($(this).is(":checked")?($(this).siblings("em").addClass("defalut_ico"),$(this).parents().siblings("span").addClass("yes_check")):($(this).siblings("em").removeClass("defalut_ico"),$(this).parents().siblings("span").removeClass("yes_check")))}),$(document).on("click","#Jsubmit",function(){if(d)if($("#checkYt").is(":checked")){var b=$("#Jsubmit").data("submit");c=c+"?pay_type="+b+"&order_id="+a,$("#orderFrom").attr("action",c),$("#orderFrom").submit()}else layer.msg("请先仔细阅读合同条款并勾选确认")}),$(document).on("click","#Rsubmit",function(){$("#checkYt").is(":checked")?window.open("refund.html#/refund/home/refund_step_1"):layer.msg("请先仔细阅读合同条款并勾选确认")})},initRefundEvent:function(){$(document).off("click","#Rsubmit").on("click","#Rsubmit",function(){$("#checkYt").is(":checked")?window.open("refund.html#/refund/home/refund_step_1"):layer.msg("请先仔细阅读合同条款并勾选确认")})}};getDataForAjaxHandler={getDataEvent:function(){var a=getUrlParamHandler.getUrlParam("pos"),c=getUrlParamHandler.getUrlParam("order_step_type"),e=splicePaymentContHandler;$.ajax({url:b,type:"GET",async:!0,dataType:"jsonp",data:{order_id:a,order_step_type:c}}).done(function(a){if("000"==a.code){e.splicePayDetailsDataEvent(a.data.data_list,"工长"),e.splicePayMoneyDataEvent(a.data.data_list["需付款"]),0==a.data.pay_type&&($("#Jsubmit").val("已支付").addClass("alreadyPaid"),$("#checkYt").prop("checked",!0),$("#checkYt").attr("disabled",!0),$(".lookit_default").find("em").addClass("defalut_ico"),$(".lookit_default").find("label").addClass("cursor"),$("#nocheck").addClass("yes_check"),d=!1);var b=$(".titlelist li");"5"==c?(e.splicePayDetailsDataEvent(a.data.data_list,"水电工"),b.removeClass("payment_on"),b.eq(2).addClass("payment_on")):"9"==c?(e.splicePayDetailsDataEvent(a.data.data_list,"瓦工"),b.removeClass("payment_on"),b.eq(3).addClass("payment_on")):"13"==c?(e.splicePayDetailsDataEvent(a.data.data_list,"木工"),b.removeClass("payment_on"),b.eq(4).addClass("payment_on")):"17"==c&&(e.splicePayDetailsDataEvent(a.data.data_list,"油漆工"),b.removeClass("payment_on"),b.eq(5).addClass("payment_on")),$(document).on("click",".titlelist li",function(){e.splicePayDetailsDataEvent(a.data.data_list,$(this).find("a").text()),$(this).addClass("payment_on").siblings().removeClass("payment_on")})}else $(".paymentList_wrap ").html('<div class="nullpage"><i>&nbsp;</i><span>订单号不存在~</span></div>')})}},splicePaymentContHandler={splicePayDetailsDataEvent:function(a,b){var c="";$.each(a[b],function(a,b){var d,e=0;for(d in b)e++;if("小计/元"==a){c+='<tr><td colspan="4" class="border_eee">'+a+"</td>";for(var f in b)c+="<td>"+b[f]+"</td>";c+="</tr>"}else if("结转金额/元"==a)c+='<tr><td colspan="4" class="border_eee"><span class="con carryoverTxt" ><em class="compare">'+a+'</em></span></td><td colspan="2">'+b+"</td></tr>";else{c+='<tr><td rowspan="'+e+'" class="border_eee">'+a+"</td>";for(var f in b)c+="<td>"+f+"</td>",$.each(b[f],function(a,b){c+="<td>"+b+"</td>"}),c+="</tr>"}}),$(".paymentlist_cardtable table tbody").html(c),$(".paymentlist_cardtable").find("table tbody > tr").each(function(a,b){window.setTimeout(function(){$(b).addClass("shows")},20*a)})},splicePayMoneyDataEvent:function(a){var b=getUrlParamHandler.getUrlParam("order_step_type"),c="";$.each(a,function(a,b){"总计/元"==a?c+='<tr><td colspan="2" class="border_eee">'+a+"</td><td >"+b+"</td></tr>":(c+="<tr>",$.each(b,function(a,b){c+="<td>"+b+"</td>"}),c+="</tr>")}),c+="17"==b&&a["总计/元"]<0?'<tr><td colspan="2" class="border_eee"><label for="checkYt" class="lookit_default"><input id="checkYt" class="display" type="checkbox" /><em class=""></em><span>请仔细核对线下填写预算单上的金额并已认真阅读和同意以下条款之后在退款</span></label><span id="nocheck" class="err_check">请仔细阅读合同条款并勾选确认</span></td><td><a id="Rsubmit" class="submit" type="button" data-submit="order">退款</a></td></tr>':'<tr><td colspan="2" class="border_eee"><label for="checkYt" class="lookit_default"><input id="checkYt" class="display" type="checkbox" /><em class=""></em><span>请仔细核对线下填写预算单上的金额并已认真阅读和同意以下条款之后在支付</span></label><span id="nocheck" class="err_check">请仔细阅读合同条款并勾选确认</span></td><td><input id="Jsubmit" class="submit" type="button" data-submit="order" value="支付"></td></tr>',$(".paylist_cardtable table tbody").html(c),$(".paylist_cardtable").find("table tbody > tr").each(function(a,b){window.setTimeout(function(){$(b).addClass("shows")},20*a)}),(window.ActiveXObject||"ActiveXObject"in window)&&$(".lookit_default em").addClass("em")}},getUrlParamHandler={getUrlParam:function(a){var b=new RegExp("(^|&)"+a+"=([^&]*)(&|$)"),c=window.location.hash.split("?")[1].match(b);return null!=c?unescape(c[2]):null}},a.controller("advancelistCtrl",["$scope","$http",function(){e.init()}])}();