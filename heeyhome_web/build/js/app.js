define(["angular","require","angular-ui-router","oclazyLoad","ChineseDistricts","distpicker","pagination","paging","superSlide","dialog","ymdClass","layer","idCode","cookie","base64"],function(angular){function routeFn($ocLazyLoadProvider,Modules_Config){$ocLazyLoadProvider.config({debug:!1,events:!1,modules:Modules_Config})}var app=angular.module("heeyhomeApp",["ui.router","oc.lazyLoad","tm.pagination"]);return app.factory("interceptors",[function(){return{request:function(request){return request.beforeSend&&request.beforeSend(),request},response:function(response){return response.config.complete&&response.config.complete(response),response}}}]),app.init=function(){angular.bootstrap(document,["heeyhomeApp"])},app.config(["$provide","$compileProvider","$controllerProvider","$filterProvider",function($provide,$compileProvider,$controllerProvider,$filterProvider){app.controller=$controllerProvider.register,app.directive=$compileProvider.directive,app.filter=$filterProvider.register,app.factory=$provide.factory,app.service=$provide.service,app.constant=$provide.constant}]),app.config(function($httpProvider){$httpProvider.defaults.transformRequest=function(obj){var str=[];for(var p in obj)str.push(encodeURIComponent(p)+"="+encodeURIComponent(obj[p]));return str.join("&")},$httpProvider.defaults.headers.post={"Content-Type":"application/x-www-form-urlencoded; charser=UTF-8"}}),app.constant("Modules_Config",[{name:"treeControl",serie:!0,files:[]}]),app.config(["$ocLazyLoadProvider","Modules_Config",routeFn]),app.run(function(){}),app});