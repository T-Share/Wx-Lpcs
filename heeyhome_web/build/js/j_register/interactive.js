/*! Pc.Heigh-ho.Renovation-1.0.0.js 2017-03-22 */
!function(){var a=angular.module("heeyhomeApp"),b="/api/public/resetpassword",c="/api/public/verification/phoneverify",d="/api/public/verification/emailverify",e="/api/public/sendsms",f="/api/public/sendmail",g=/^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/,h=/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/,i=/^[0-9a-zA-Z_]{6,14}$/,j=60,k="邮箱不能为空",l="手机号不能为空",m="请输入有效的手机号码",n="密码不能为空",o="密码长度为6~14位不为空格的字符",p="确认密码不能为空",q="输入密码不一致",r="验证码不能为空",s="请输入有效的邮箱",t={init:function(){t.initEvent()},initEvent:function(){var a=this;a.sureAccount(),a.quickReset(),a.newPassword()},sureAccount:function(){a.controller("processOneCtrl",["$scope","$http","$state",function(){$("#headerWrapper").remove(),$(".guide span").eq(0).addClass("active").siblings().removeClass("active"),$(".process_1 li input").on("click",function(){var a=$(this).attr("data-belong");sessionStorage.setItem("style",a),window.location.href="#/forget/public/process_2"})}])},quickReset:function(){a.controller("processTwoCtrl",["$scope","$http","$state",function(){$("#headerWrapper").remove(),$(".guide span").eq(1).addClass("active").siblings().removeClass("active");var a=sessionStorage.getItem("style");"phone"==a?$(".process_2 .phone_validate").removeClass("display"):$(".process_2 .email_validate").removeClass("display"),$(".reselect").on("click",function(){window.location.href="#/forget/public/process_1"}),getCode.codeEvent(),ajaxSubmitThree.threeSection()}])},newPassword:function(){a.controller("processThreeCtrl",["$scope","$http","$state",function(){$("#headerWrapper").remove(),$(".guide span").eq(2).addClass("active").siblings().removeClass("active"),complete.backLogin()}])}};getCode={codeEvent:function(){var a=$(".phone_validate .phone_code .get_code"),b=$(".email_validate .email_code .get_code");a.on("click",function(){var b=$(".phone_validate .phone input"),c=b.val();return null==c||""==c?void inputErrorHendler.errorContent(l,b):g.test(c)?void confirmCodeHendler.sendMessage(a,j,"phone",c):void inputErrorHendler.errorContent(m,b)}),b.on("click",function(){var a=$(".email_validate .email .click"),c=$(".email_validate .email .click").val();return null==c||""==c?void inputErrorHendler.errorContent(k,a):h.test(c)?void confirmCodeHendler.sendMessage(b,j,"email",c):void inputErrorHendler.errorContent(s,a)}),clickInput.clickInputEvent()}},clickInput={clickInputEvent:function(){$(".process .click").on("click",function(){$(this).siblings("label").removeClass("whether"),$(this).removeClass("inputClickError").addClass("inputClick").siblings().removeClass("itemClick")})}},inputErrorHendler={errorContent:function(a,b){b.siblings("label").text(a).addClass("whether").siblings("input").addClass("inputClickError")}},confirmCodeHendler={sendMessage:function(a,b,c,d){a.addClass("btnClick").attr("disabled",!0),a.val(b+"秒后重新获取"),InterValObj=window.setInterval(function(){1==b?(window.clearInterval(InterValObj),a.removeClass("btnClick").removeAttr("disabled"),a.val("获取验证码")):(b--,a.val(b+"秒后重新获取"))},1e3),$.ajax("phone"==c?{url:e,type:"get",async:!0,dataType:"jsonp",data:{phone:d},success:function(){},error:function(){}}:{url:f,type:"get",async:!0,dataType:"jsonp",data:{email:d},success:function(){},error:function(){}})}},ajaxSubmitThree={threeSection:function(){$(".phone_validate .besure").on("click",function(){var a=$(".phone_validate .phone_code .click"),b=a.val(),d=$(".phone_validate .phone .click"),e=d.val(),f=!0,h=$("#loading");(null==b||""==b)&&(inputErrorHendler.errorContent(r,a),f=!1),null==e||""==e?(inputErrorHendler.errorContent(l,d),f=!1):g.test(e)||(inputErrorHendler.errorContent(m,d),f=!1),clickInput.clickInputEvent(),f&&$.ajax({type:"get",url:c,async:!0,dataType:"jsonp",data:{phone:e,captcha:b},beforeSend:function(){f&&h.removeClass("display"),$(".phone_validate .besure").attr("disabled",!0)},success:function(a){null!=a&&"000"==a.code?(sessionStorage.setItem("user_flag",a.data.flag),window.location.href="#/forget/public/process_3"):null!=a&&"000"!=a.code&&layer.alert(a.msg)},complete:function(){$(".phone_validate .besure").removeAttr("disabled"),h.addClass("display")},error:function(){}})}),$(".email_validate .besure").on("click",function(){var a=$(".email_validate .email input"),b=a.val(),c=$(".email_validate .email_code .click"),e=c.val(),f=!0,g=$("#loading");(null==e||""==e)&&(inputErrorHendler.errorContent(r,c),f=!1),null==b||""==b?(inputErrorHendler.errorContent(k,a),f=!1):h.test(b)||(inputErrorHendler.errorContent(s,a),f=!1),clickInput.clickInputEvent(),f&&$.ajax({type:"get",url:d,async:!0,dataType:"jsonp",data:{email:b,captcha:e},beforeSend:function(){f&&g.removeClass("display"),$(".email_validate .besure").attr("disabled",!0)},success:function(a){null!=a&&"000"==a.code?(sessionStorage.setItem("user_flag",a.data.flag),window.location.href="#/forget/public/process_3"):null!=a&&"000"!=a.code&&layer.alert(a.msg)},complete:function(){$(".email_validate .besure").removeAttr("disabled"),g.addClass("display")},error:function(){}})})}},complete={backLogin:function(){$(".process_3 .sure").on("click",function(){var a=$(".new_password input"),c=a.val(),d=$(".sure_passowrd input"),e=d.val(),f=!0,g=$("#loading"),h=sessionStorage.getItem("user_flag");null==c||""==c?(inputErrorHendler.errorContent(n,a),f=!1):i.test(c)||(inputErrorHendler.errorContent(o,a),f=!1),null==e||""==e?(inputErrorHendler.errorContent(p,d),f=!1):e!=c&&(inputErrorHendler.errorContent(q,d),f=!1),clickInput.clickInputEvent(),f&&$.ajax({type:"get",url:b,async:!0,dataType:"jsonp",data:{new_password:c,flag:h},beforeSend:function(){f&&g.removeClass("display"),$(".process_3 .sure").attr("disabled",!0)},success:function(a){null!=a&&"000"==a.code?window.location.href="register.html":null!=a&&"000"!=a.code&&layer.alert(a.msg)},complete:function(){$(".process_3 .sure").removeAttr("disabled"),g.addClass("display")},error:function(){}})})}},t.init()}();