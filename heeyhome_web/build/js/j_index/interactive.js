/*! Pc.Heigh-ho.Renovation-1.0.0.js 2017-03-23 */
define(["app"],function(a){var b="/api/public/banner",c="/api/public/panorama";!function(){var d={init:function(){d.initEvent()},initEvent:function(){var a=this;a.initCarouselEvent(),a.initVrEvent()},initCarouselEvent:function(){$.ajax({type:"get",url:b,async:!0,dataType:"jsonp",success:function(a){function b(a){for(var b=[],a="object"!=typeof a?[a]:a,c=0;c<a.length;c++)b[c]=new Image,b[c].src=a[c]}if(null!=a&&"000"==a.code){var c=a.data.length,d='<ul class="car-inner">',e=[];$.each(a.data,function(a,b){d+=0==a?'<li class="item active"><a href="javascript:;" target="_blank"><img src="'+b.img+'"></a></li>':'<li class="item"><a href="javascript:;" target="_blank"><img src="'+b.img+'"></a></li>',e.push(b.img),$(".number_control ul").append("<li>0"+(a+1)+"</li>")}),d+="</ul>",$("#myCarousel").append(d),superSlide.slidePic(),onchangeNumber.change(c),b(e)}},error:function(){}})},initVrEvent:function(){$.ajax({type:"get",url:c,async:!0,dataType:"jsonp",success:function(a){if(null!=a&&"000"==a.code){for(var b='<div class="vr_picture"><ul>',c=0;3>c;c++)b+='<li> <a href="'+a.data[c].panorama_url+'" target="_blank"> <div class="panoramaImg">',b+='<img src="'+a.data[c].panorama_img+'"></div>',b+="<figcaption>",b+='<i class="sprite-image pic_hover"></i>',b+=" <b>"+a.data[c].panorama_style+"</b>",b+=" <span>"+a.data[c].panorama_area+"m²</span>",b+=" </figcaption></a> </li>";b+="</ul></div>",$(".virtual_content").append(b),boxPicture.cssSetting()}},error:function(){}})}};superSlide={slidePic:function(){$("#myCarousel").slide({titCell:".hd ul",mainCell:".car-inner",autoPage:!0,trigger:"click",effect:"left",autoPlay:!0,vis:1})}},onchangeNumber={change:function(a){var b=$(".hd li"),c=$(".number_control li"),d=0;setInterval(function(){for(d=0;a>d;d++)"on"==$(b).eq(d).attr("class")&&($(c).eq(d).addClass("active"),$(c).eq(d).siblings().removeClass("active"))},1)}},boxPicture={cssSetting:function(){$(".box_picture").eq(0).addClass("box_picture_1"),$(".box_picture").eq(1).addClass("box_picture_2"),$(".box_picture").eq(2).addClass("box_picture_2"),$(".box_picture").eq(3).addClass("box_picture_1"),$(".box_picture").eq(4).addClass("box_picture_1"),$(".box_picture").eq(5).addClass("box_picture_2")}},a.indexWrapHandler=function(){d.init()}}()});