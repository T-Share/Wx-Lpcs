/*! Pc.Heigh-ho.Renovation-1.0.0.js 2017-01-22 */
!function(){var a,b,c,d,e,f,g=angular.module("heeyhomeApp"),h="http://www.heeyhome.com/api/public/",i=h+"personal/foremaninfo",j=h+"personal/safe",k=h+"personal/safe/authverify",l=h+"personal/safe/auth",m=h+"editpassword",n=h+"sendsms",o=h+"sendmail",p=h+"personal/safe/phoneverify",q=h+"personal/safe/phonechange",r=h+"personal/safe/emailverify",s=h+"personal/safe/emailchange",t=h+"editpassword/initialpwd",u=h+"editpassword/smsedit",v=h+"personal/loginhistory?callback=JSON_CALLBACK",w=/^[0-9a-zA-Z_]{6,14}$/,x=/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/,y=/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/,z="密码不能为空",A="密码长度为6~14位不为空格的字符",B="输入密码不一致",C="验证码不能为空",D="邮箱不能为空",E="邮箱格式不正确",F="姓名与身份证号不能为空",G="身份证号码错误！",H="身份证格式不正确",I={init:function(){var a=this;a.initEvent()},initEvent:function(){var b=this;b.initAuthenticationEvent(),b.initSafeLevelEvent(),b.initBindOldPhoneEvent(),b.initEmailEvent(),b.initPasswordAuthEvent(),$(document).off("click","#oldPhoneCaptcha").on("click","#oldPhoneCaptcha",function(){b.sendCaptcha(n,{phone:a},$("#oldPhoneCaptcha"))}),$(document).off("click","#code_button").on("click","#code_button",function(){b.sendCaptcha(n,{phone:$("#newphone_num1").val()},$("#code_button"))}),$(document).off("click","#resetCaptcha").on("click","#resetCaptcha",function(){b.sendCaptcha(n,{phone:a},$("#resetCaptcha"))}),$(document).off("click","#email_code").on("click","#email_code",function(){""==$("#new_email").val()?layer.alert(D):x.test($("#new_email").val())?b.sendCaptcha(o,{email:$("#new_email").val()},$("#email_code")):layer.alert(E)})},initCommonEvent:function(c){$.ajax({url:i,type:"GET",async:!0,dataType:"jsonp",data:{foreman_id:$.base64.decode($.cookie("userId"))},success:function(d){d&&"000"==d.code&&(a=d.data.foremaninfo_phone,b=d.data.foremaninfo_email,c.call())},error:function(){}})},initAuthenticationEvent:function(){$(document).off("click",".next_auth").on("click",".next_auth",function(){if(""==$("#rel_name").val()||""==$("#id_code").val())layer.alert(F);else if(y.test($("#id_code").val())){if(18==$("#id_code").val().length){for(var a=[7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2],b=[1,0,10,9,8,7,6,5,4,3,2],c=0,d=0;17>d;d++)c+=$("#id_code").val().substring(d,d+1)*a[d];var e=c%11,f=$("#id_code").val().substring(17);2==e?"X"==f||"x"==f?($(".authentic_one").hide(),$(".authentic_two").show().removeClass("hide"),$(".revise_process a").eq(1).addClass("active").siblings().removeClass("active")):layer.alert(G):f==b[e]?($(".authentic_one").hide(),$(".authentic_two").show().removeClass("hide"),$(".revise_process a").eq(1).addClass("active").siblings().removeClass("active")):layer.alert(G)}}else layer.alert(H)}),g.controller("auth1",["$scope","$state",function(){function a(){$.ajax({url:l,type:"GET",async:!0,dataType:"jsonp",data:{user_id:$.base64.decode($.cookie("userId"))},success:function(a){console.log(a),"130"==a.code?($(".revise_process").remove(),$(".bind_phonecnt").remove(),$("#new_info").append('<div id="face" class="showImg fl"><img src=""> </div><!--showImg--><div id="back" class="showImg fl"><img src=""></div><!--showImg--><div class="deal_wrap clearfix fl"> <div class="deal_left fl"> <i class="iconfont">&#xe67c;</i> </div><!--deal_left--> <div class="deal_right fl"> <p class="process">身份正在验证中...</p> <p class="wait">请耐心等待</p> </div><!--deal_left--> </div><!--deal_wrap-->'),$(".content_wrap").css("height","898px"),$("#face img").attr("src","http://www.heeyhome.com/"+a.data.facephoto),$("#back img").attr("src","http://www.heeyhome.com/"+a.data.backphoto)):"131"==a.code?($(".revise_process").hide(),$(".bind_phonecnt").hide(),$("#new_info").append('<div id="wrap"><div id="face" class="showImg fl"><img src=""> </div><!--showImg--><div id="back" class="showImg fl"><img src=""></div><!--showImg--><div class="deal_wrap clearfix fl"> <div class="deal_left fl"> <i class="iconfont">&#xe625;</i> </div><!--deal_left--> <div class="deal_right fl"> <p class="process">您的审核尚未通过！</p> <p class="wait">请点击<a id="new_upload" href="javascript:;">重新上传信息~~</a></p> </div><!--deal_left--> </div><!--deal_wrap--></div>'),$(".content_wrap").css("height","898px"),$("#face img").attr("src","http://www.heeyhome.com/"+a.data.facephoto),$("#back img").attr("src","http://www.heeyhome.com/"+a.data.backphoto)):"000"==a.code&&($(".revise_process").remove(),$(".bind_phonecnt").remove(),$("#new_info").append('<div id="face" class="showImg fl"><img src=""> </div><!--showImg--><div id="back" class="showImg fl"><img src=""></div><!--showImg--><div class="deal_wrap clearfix fl"> <div class="deal_left fl"> <i class="iconfont">&#xe615;</i> </div><!--deal_left--> <div class="deal_right fl"> <p class="process complete_process">您的审核已通过！</p></div><!--deal_left--> </div><!--deal_wrap-->'),$(".content_wrap").css("height","898px"),$("#face img").attr("src","http://www.heeyhome.com/"+a.data.facephoto),$("#back img").attr("src","http://www.heeyhome.com/"+a.data.backphoto))},error:function(){}})}$("#user_id").val($.base64.decode($.cookie("userId"))),$("#form").attr("action",k),$(".auth_wrap").find("input").change(function(){var a=$(this),b=a.get(0).files[0],c=new FileReader;return/image\/\w+/.test(b.type)?(c.onload=function(b){a.parent().addClass("opacity"),a.parent().parent().css("background-image",'url("'+b.target.result+'")'),a.parent().parent().find(".close").show()},void c.readAsDataURL(b)):(a.parent().parent().css("background-image",""),a.parent().removeClass("opacity"),layer.alert("请确保文件为图像类型"),a.val(""),!1)}),$(".close").click(function(){$(this).parent().find("a").removeClass("opacity"),$(this).parent().css("background-image",""),$(this).hide()}),a(),$(document).on("click","#new_upload",function(){$("#new_info #wrap").remove(),$(".revise_process").show(),$(".bind_phonecnt").show()}),$(document).ready(function(){var b="";$(".next_confirm").click(function(){setTimeout(function(){b=$(document.getElementById("if").contentWindow.document.body).html();var c=b.substring(b.indexOf("(")+1,b.indexOf(")")),d=JSON.parse(c);"000"==d.code?a():layer.alert("111"==d.code?"信息上传失败~~":"图片上传出错~~")},1e3)})})}])},sendCaptcha:function(a,b,c){var d=60;$.ajax({url:a,type:"GET",async:!0,dataType:"jsonp",data:b,success:function(a){"000"==a.code?(c.addClass("zc_btnClick").attr("disabled",!0),c.val(d+"秒后重新获取"),InterValObj=window.setInterval(function(){1==d?(window.clearInterval(InterValObj),c.removeClass("zc_btnClick").removeAttr("disabled"),c.val("获取验证码")):(d--,c.val(d+"秒后重新获取"))},1e3)):layer.alert(a.msg)},error:function(){}})},initBindOldPhoneEvent:function(){g.controller("bind_phone1",["$scope","$state",function(){var b=window.location.hash;$("#left_ul li").removeClass("left_active");var d=$("#left_ul a");d.each(function(){var a=$(this).attr("ui-sref");-1!=b.indexOf(a.replace(".","/"))&&$(this).parent().addClass("left_active")});var e=function(){$(".revise_process a").eq(0).addClass("active").siblings().removeClass("active");var b=a.substr(0,3)+"****"+a.substr(7,11);$("#old_phone").html(b),$(document).off("click","#next1").on("click","#next1",function(){""!=$("#confirm_code1").val()?$.ajax({url:p,type:"GET",async:!0,dataType:"jsonp",data:{user_id:$.base64.decode($.cookie("userId")),oldphone:a,captcha:$("#confirm_code1").val()},success:function(a){"000"==a.code?(c=a.data.flag,window.location.href="#/master/setting/bind/bind_phone_2"):layer.alert(a.msg)},error:function(){}}):layer.alert(C)})};a?e():I.initCommonEvent(e)}]),g.controller("bind_phone2",["$scope","$state",function(){$(".revise_process a").eq(1).addClass("active").siblings().removeClass("active"),$(document).off("click","#next2").on("click","#next2",function(){$.ajax({url:q,type:"GET",async:!0,dataType:"jsonp",data:{user_id:$.base64.decode($.cookie("userId")),flag:c,newphone:$("#newphone_num1").val(),captcha:$("#newphone_code1").val()},success:function(a){"000"==a.code?window.location.href="#/master/setting/bind/bind_phone_3":layer.alert(a.msg)},error:function(){}})})}]),g.controller("bind_phone3",["$scope","$state",function(){$(".revise_process a").eq(2).addClass("active").siblings().removeClass("active"),$(document).off("click","#back").on("click","#back",function(){window.location.href="#/master/setting",location.reload(!0)})}])},initEmailEvent:function(){g.controller("emailOne",["$scope","$state",function(){var b=window.location.hash;$("#left_ul li").removeClass("left_active");var c=$("#left_ul a");c.each(function(){var a=$(this).attr("ui-sref");-1!=b.indexOf(a.replace(".","/"))&&$(this).parent().addClass("left_active")});var e=function(){$(".revise_process a").eq(0).addClass("active").siblings().removeClass("active");var b=a.substr(0,3)+"****"+a.substr(7,11);$("#old_phone").html(b),$(document).off("click","#next_email1").on("click","#next_email1",function(){""!=$("#confirm_code").val()?$.ajax({url:r,type:"GET",async:!0,dataType:"jsonp",data:{user_id:$.base64.decode($.cookie("userId")),phone:a,captcha:$("#confirm_code").val()},success:function(a){"000"==a.code?(d=a.data.flag,window.location.href="#/master/setting/email/email_2"):layer.alert(a.msg)},error:function(){}}):layer.alert(C)})};a?e():I.initCommonEvent(e)}]),g.controller("emailTwo",["$scope","$state",function(){$(".revise_process a").eq(1).addClass("active").siblings().removeClass("active"),$(document).off("click","#next_email2").on("click","#next_email2",function(){$.ajax({url:s,type:"GET",async:!0,dataType:"jsonp",data:{user_id:$.base64.decode($.cookie("userId")),flag:d,newemail:$("#new_email").val(),captcha:$("#newphone_code").val()},success:function(a){"000"==a.code?window.location.href="#/master/setting/email/email_3":layer.alert(a.msg)},error:function(){}})})}]),g.controller("emailThree",["$scope","$state",function(){$(".revise_process a").eq(2).addClass("active").siblings().removeClass("active"),$(document).off("click","#back").on("click","#back",function(){window.location.href="#/master/setting"})}])},initPasswordAuthEvent:function(){g.controller("reset1",["$scope","$state",function(){$(".revise_process a").eq(0).addClass("active").siblings().removeClass("active"),$(document).off("click","#message").on("click","#message",function(){window.location.href="#/master/setting/reset/reset_2"}),$(document).off("click","#password").on("click","#password",function(){window.location.href="#/master/setting/reset/reset_3"})}]),g.controller("reset2",["$scope","$state",function(){var b=function(){$(".revise_process a").eq(0).addClass("active").siblings().removeClass("active");var b=a.substr(0,3)+"****"+a.substr(7,11);$("#old_phone").html(b),$(document).off("click","#re_select").on("click","#re_select",function(){window.location.href="#/master/setting/reset/reset_1"}),$(document).off("click","#next_message1").on("click","#next_message1",function(){""!=$("#password_code").val()?$.ajax({url:u,type:"GET",async:!0,dataType:"jsonp",data:{user_id:$.base64.decode($.cookie("userId")),phone:a,captcha:$("#password_code").val()},success:function(a){"000"==a.code?(f=a.data.flag,window.location.href="#/center/setting/reset/reset_6"):layer.alert(a.msg)},error:function(){}}):layer.alert(C)})};a?b():I.initCommonEvent(b)}]),g.controller("reset3",["$scope","$state",function(){$(".revise_process a").eq(0).addClass("active").siblings().removeClass("active"),$(document).off("click","#re_select").on("click","#re_select",function(){window.location.href="#/master/setting/reset/reset_1"}),$(document).off("click","#next_password1").on("click","#next_password1",function(){""==$("#login_code").val()?layer.alert(z):$.ajax({url:t,type:"GET",async:!0,dataType:"jsonp",data:{user_id:$.base64.decode($.cookie("userId")),oldpassword:$("#login_code").val()},success:function(a){"000"==a.code?(e=a.data.flag,window.location.href="#/master/setting/reset/reset_4"):layer.alert(a.msg)},error:function(){}})})}]),g.controller("reset4",["$scope","$state",function(){$(".revise_process a").eq(1).addClass("active").siblings().removeClass("active"),$(document).off("click","#next_reset2").on("click","#next_reset2",function(){""==$("#new_password1").val()||""==$("#new_password2").val()?layer.alert(z):w.test($("#new_password1").val())?$("#new_password1").val()!=$("#new_password2").val()?layer.alert(B):$.ajax({url:m,type:"GET",async:!0,dataType:"jsonp",data:{user_id:$.base64.decode($.cookie("userId")),flag:e,new_password:$("#new_password1").val()},success:function(a){"000"==a.code?window.location.href="#/master/setting/reset/reset_5":layer.alert(a.msg)},error:function(){}}):layer.alert(A)})}]),g.controller("reset6",["$scope","$state",function(){$(".revise_process a").eq(1).addClass("active").siblings().removeClass("active"),$(document).off("click","#ms_reset2").on("click","#ms_reset2",function(){""==$("#new_password1").val()||""==$("#new_password2").val()?layer.alert(z):w.test($("#new_password1").val())?$("#new_password1").val()!=$("#new_password2").val()?layer.alert(B):$.ajax({url:m,type:"GET",async:!0,dataType:"jsonp",data:{user_id:$.base64.decode($.cookie("userId")),flag:f,new_password:$("#new_password1").val()},success:function(a){"000"==a.code?window.location.href="#/master/setting/reset/reset_5":layer.alert(a.msg)},error:function(){}}):layer.alert("密码格式不正确")})}]),g.controller("reset5",["$scope","$state",function(){function a(){b-=1,c.html(b),0==b&&(window.location.href="register.html"),setTimeout(a,1e3)}$(".revise_process a").eq(2).addClass("active").siblings().removeClass("active"),$(document).off("click","#password_button").on("click","#password_button",function(){window.location.href="register.html"});var b=5,c=$("#countdown");a()}])},initSafeLevelEvent:function(){g.controller("SettingCtrl",["$scope","$http",function(a,b){$(".Jforeman").html("安全设置"),$(".left_ul li").eq(6).addClass("left_active").siblings().removeClass("left_active"),$.ajax({dataType:"JSONP",url:j,type:"GET",async:!0,data:{user_id:$.base64.decode($.cookie("userId"))},success:function(a){if(a&&"000"==a.code){if(2==parseInt(a.data.score))for(var b=0;1>b;b++)$(".star i").eq(b).addClass("active_i");else if(4==parseInt(a.data.score))for(var b=0;2>b;b++)$(".star i").eq(b).addClass("active_i");else if(6==parseInt(a.data.score))for(var b=0;3>b;b++)$(".star i").eq(b).addClass("active_i");else if(8==parseInt(a.data.score))for(var b=0;4>b;b++)$(".star i").eq(b).addClass("active_i");else if(10==parseInt(a.data.score))for(var b=0;5>b;b++)$(".star i").eq(b).addClass("active_i");$("#number").html(parseInt(a.data.score)+".0")}},error:function(){}}),b({method:"JSONP",url:v,params:{user_id:$.base64.decode($.cookie("userId"))}}).success(function(b){if(console.log(b),"000"===b.code){a.safes=b.data.slice(0,2);var c=b.data.length;a.mores=b.data.slice(2,c);var d=$("#more");d.click(function(){$(".login_next").toggle(300),d.html("更多"==d.html()?"隐藏":"更多")})}else"117"===b.code?($(".not_information").show().removeClass("hide"),$(".not_information_text").html("您最近还没有登录过哦~~"),$(".login_note").hide(),$(".login_history p").hide()):layer.msg(b.msg)}).error(function(){})}])}};I.init()}();