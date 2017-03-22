/*! Pc.Heigh-ho.Renovation-1.0.0.js 2017-03-22 */
!function(){var a=window.location.hash,b="/api/public/register/user_register",c="/api/public/login/login",d="/api/public/sendsms",e="/api/public/qqlogin",f="/api/public/wxlogin",g=/^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/,h=/^[0-9a-zA-Z_]{6,14}$/,i="手机号不能为空",j="请输入有效的手机号码",k="密码不能为空",l="密码长度为6~14位不为空格的字符",m="确认密码不能为空",n="输入密码不一致",o="验证码不能为空",p={init:function(){p.initEvent()},initEvent:function(){var a=this;a.initLoginEvent(),a.tabSelectEvent(),a.clickInputEvent(),a.clickLoginEvent(),a.pswSaveEvent(),a.getConfirmCodeEvent(),a.thirdLoginEvent()},initLoginEvent:function(){switch((""==a||null==a)&&(a="#dl",window.location.hash="#dl"),a){case"#zc":changeTabHendler.swtchTab(0);break;case"#dl":changeTabHendler.swtchTab(1)}},tabSelectEvent:function(){var a=$(".tab li");a.on("click",function(){changeTabHendler.swtchTab($(this).index())})},clickInputEvent:function(){$(".main input").on("click",function(){$(this).siblings("label").removeClass("whether"),$(this).parent("div.registerItem").removeClass("itemClickError").addClass("itemClick").siblings().removeClass("itemClick")})},clickLoginEvent:function(){var a=$(".promptly_do");$(document).keydown(function(b){13==b.keyCode&&a.click()}),a.on("click",function(){var a=window.location.hash,b=a.split("#")[1];null!=a&&""!=a&&ajaxSubmitHendler.submitInfo(b)})},pswSaveEvent:function(){var a=$(".remember"),b=$.cookie("user"),c=$.cookie("a"),d=$.cookie("checked");null!=b&&""!=b&&$(".dl_iphone").val($.base64.decode(b)),null!=c&&""!=c&&h.test(c)&&$(".dl_password").val($.base64.decode(c)),1==d&&a.attr("checked",!0),a.on("click",function(){a.prop("checked")?$.cookie("checked",1):($.cookie("checked",0),$.cookie("user",null,{expires:-1,path:"/"}),$.cookie("a",null,{expires:-1,path:"/"}))})},getConfirmCodeEvent:function(){var a=$(".zc_btnSendCode"),b=$(".zc_phone"),c=60;a.on("click",function(){return null==b.val()||""==b.val()?void inputErrorHendler.errorContent(i,b):g.test(b.val())?void confirmCodeHendler.sendMessage(a,c,"zc",b.val()):void inputErrorHendler.errorContent(j,b)})},thirdLoginEvent:function(){$(".qq").on("click",function(){window.location.href=e}),$(".wechat").on("click",function(){window.location.href=f})}};changeTabHendler={swtchTab:function(a){var b=$(".container .tab"),c=$(".container .main");a?(c.find(".login,.third_login").removeClass("display"),c.find(".register").addClass("display"),b.find(".tab_click_login").addClass("active").siblings(".tab_click_register").removeClass("active"),c.children(".logo_title").find("img").attr("src","image/login.png"),c.find(".promptly_do").removeClass("margin_top_1").addClass("margin_top_2").attr("value","立即登录"),window.location.hash="#dl"):(c.find(".register").removeClass("display"),c.find(".login,.third_login").addClass("display"),b.find(".tab_click_register").addClass("active").siblings(".tab_click_login").removeClass("active"),c.children(".logo_title").find("img").attr("src","image/register.png"),c.find(".promptly_do").removeClass("margin_top_2").addClass("margin_top_1").attr("value","立即注册"),window.location.hash="#zc")}},ajaxSubmitHendler={submitInfo:function(a){var d=$("."+a+"_phone"),e=d.val(),f=$("."+a+"_password"),p=f.val(),q=$("."+a+"_sure_password"),r=q.val(),s=$("."+a+"_codes"),t=s.val(),u=$(".remember"),v=!0,w=$("#loading"),x=$(".promptly_do");null==e||""==e?(inputErrorHendler.errorContent(i,d),v=!1):11==e.length&&g.test(e)||(inputErrorHendler.errorContent(j,d),v=!1),null==p||""==p?(inputErrorHendler.errorContent(k,f),v=!1):h.test(p)||(inputErrorHendler.errorContent(l,f),v=!1),"zc"==a&&(null==r||""==r?(inputErrorHendler.errorContent(m,q),v=!1):r!=p&&(inputErrorHendler.errorContent(n,q),v=!1),(null==t||""==t)&&(inputErrorHendler.errorContent(o,s),v=!1)),v&&("zc"==a?$.ajax({type:"get",url:b,async:!0,dataType:"jsonp",data:{phone:e,password:p,captcha:t},beforeSend:function(){v&&w.removeClass("display"),x.attr("disabled",!0)},success:function(a){null!=a&&"000"==a.code?(layer.alert(a.msg),changeTabHendler.swtchTab(1)):null!=a&&"000"!=a.code&&layer.alert(a.msg)},complete:function(){x.removeAttr("disabled"),w.addClass("display")},error:function(){}}):"dl"==a&&$.ajax({url:c,type:"GET",async:!0,dataType:"jsonp",data:{account:e,password:p},beforeSend:function(){v&&w.removeClass("display"),x.attr("disabled",!0),null!=e&&null!=p&&""!=e&&""!=p&&(u.prop("checked")?($.cookie("user",$.base64.encode(e),{expires:7,path:"/"}),$.cookie("a",$.base64.encode(p),{expires:7,path:"/"}),$.cookie("checked",1)):($.cookie("user",null,{expires:-1,path:"/"}),$.cookie("a",null,{expires:-1,path:"/"}),$.cookie("checked",0)))},success:function(a){null!=a&&"000"==a.code?($.cookie("userId",$.base64.encode(a.data.user_id),{expires:7,path:"/"}),$.cookie("userName",$.base64.encode(escape(a.data.user_name)),{expires:7,path:"/"}),$.cookie("userPhone",$.base64.encode(a.data.user_phone),{expires:7,path:"/"}),$.cookie("userType",$.base64.encode(a.data.user_type),{expires:7,path:"/"}),$.cookie("userEmail",$.base64.encode(a.data.user_email),{expires:7,path:"/"}),$.cookie("userShopId",$.base64.encode(a.data.shop_id),{expires:7,path:"/"}),$.cookie("userNickName",$.base64.encode(escape(a.data.nickname)),{expires:7,path:"/"}),window.location.href="http://www.heeyhome.com/"):null!=a&&"000"!=a.code&&layer.alert(a.msg)},complete:function(){x.removeAttr("disabled"),w.addClass("display")},error:function(){}}))}},confirmCodeHendler={sendMessage:function(a,b,c,e){a.addClass("zc_btnClick").attr("disabled",!0),a.val(b+"秒后重新获取"),InterValObj=window.setInterval(function(){1==b?(window.clearInterval(InterValObj),a.removeClass("zc_btnClick").removeAttr("disabled"),a.val("获取验证码")):(b--,a.val(b+"秒后重新获取"))},1e3),$.ajax({url:d,type:"get",async:!0,dataType:"jsonp",data:{phone:e},success:function(){},error:function(){}})}},inputErrorHendler={errorContent:function(a,b){b.siblings("label").text(a).addClass("whether").parent("div").addClass("itemClickError")}},errorMsgHendler={remindBox:function(a){var b=$("#ReminderBox"),c=$(".remindebox");b.removeClass("display"),$(".info_header span").text(a),c.stop().animate({"margin-top":"-150px",opacity:1},500),$(".remindemodel_ok").on("click",function(){c.stop().animate({"margin-top":"-40px",opacity:0},500,function(){b.addClass("display")})})}},p.init()}();