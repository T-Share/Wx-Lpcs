/*! Pc.Heigh-ho.Renovation-1.0.0.js 2017-03-23 */
!function(){var a=angular.module("heeyhomeApp"),b="/api/public/order/user/getrefundinfo",c="/api/public/order/user/subrefundinfo",d=/^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/,e=/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/,f={init:function(){f.initEvent()},initEvent:function(){var a=this;a.initRemoveHeadEvent(),a.initGetInfoEvent(),a.initJudgementEvent()},initRemoveHeadEvent:function(){$("#headerWrapper").remove()},initJudgementEvent:function(){var a=sessionStorage.getItem("orderid");$(document).off("click",".submit_application").on("click",".submit_application",function(){null==$("#account").val()||""==$("#account").val()?layer.msg("支付宝账户不能为空~~"):d.test($("#account").val())||e.test($("#account").val())?$.ajax({url:c,type:"GET",async:!0,dataType:"jsonp",data:{order_id:a,alipay_account:$("#account").val()},success:function(a){null!=a&&"000"==a.code?(layer.msg(a.msg),window.location.href="refund.html#/refund/home/refund_step_2",location.reload()):layer.msg(a.msg)},error:function(){}}):layer.msg("支付宝账户格式不正确~~")})},initGetInfoEvent:function(){a.controller("refund1",["$scope","$state","$timeout",function(){var a=sessionStorage.getItem("orderid");$.ajax({url:b,type:"GET",async:!0,dataType:"jsonp",data:{order_id:a},success:function(a){null!=a&&"000"==a.code?"0"==a.data.refund_status?(window.location.href="refund.html#/refund/home/refund_step_1",$(".stepOne_wrap").show(),$(".stepOne_wrap").show(),$(".title_detail").eq(0).addClass("active"),$("#order_number").html(a.data.order_id),"17"==a.data.order_step&&$("#order_step").html("油漆工完工阶段"),$("#order_time").html(a.data.order_time),$("#order_money").html(-a.data.pay_amount)):"1"==a.data.refund_status?(window.location.href="refund.html#/refund/home/refund_step_2",$(".stepOne_wrap").hide(),$(".deal_wrap").show(),$(".title_detail").eq(0).removeClass("active"),$(".title_detail").eq(1).addClass("active")):(window.location.href="refund.html#/refund/home/refund_step_3",$(".stepOne_wrap").hide(),$(".deal_wrap").hide(),$(".successfully").show(),$(".title_detail").eq(0).removeClass("active"),$(".title_detail").eq(2).addClass("active"),$("#wait span").html(a.data.refund_account)):layer.msg(a.msg)},error:function(){}})}])}};a.controller("refund",["$scope","$http",function(){f.init()}])}();