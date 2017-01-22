/*! Pc.Heigh-ho.Renovation-1.0.0.js 2017-01-22 */
define(["app","angular-ui-router","oclazyLoad"],function(a){a.config(["$stateProvider","$urlRouterProvider","$httpProvider",function(a,b,c){c.interceptors.push("interceptors"),a.state("home",{url:"/",views:{content:{templateUrl:"view/v_index/index_wrap.html"}}}).state("cal",{url:"/cal",views:{content:{templateUrl:"view/v_cal/v_cal.html",controller:"calCtrl",controllerAs:"cal"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_cal/cal.js")}]}}).state("panorama",{url:"/panorama",views:{content:{templateUrl:"view/v_panorama/panorama_wrap.html",controller:"panoramaCtrl",controllerAs:"panorama"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["css/c_common/fliter.css","css/c_panorama/panorama.css","css/c_common/pagewrap.css","lib/layui/css/modules/layer/default/layer.css","js/j_panorama/interactive.js"])}]}}).state("shoplist",{url:"/shoplist",views:{content:{templateUrl:"view/v_shoplist/shoplist_wrap.html",controller:"myShoplist",controllerAs:"shoplist"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["css/c_common/fliter.css","css/c_shoplist/shoplist.css","css/c_common/pagewrap.css","js/j_shoplist/interactive.js"])}]}}).state("gonglve",{url:"/gonglve",views:{content:{templateUrl:"view/v_gonglve/v_gonglve_nav.html",controller:"myGonglve",controllerAs:"gonglve"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["css/c_common/pagewrap.css","css/c_gonglve/gonglve_nav.css","css/c_gonglve/gonglve_title.css","js/j_gonglve/interactive.js"])}]}}).state("detail",{url:"/detail",views:{detail_content:{templateUrl:"view/v_gonglve/v_gonglve_detail.html",controller:"myDetail",controllerAs:"detail"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["js/j_gonglve/interactive.js"])}]}}).state("refund",{url:"/refund",views:{refund_content:{templateUrl:"view/v_refund/refund.html"}}}).state("refund.home",{url:"/home",views:{my_refund:{templateUrl:"view/v_refund/refund_home.html",controller:"refund",controllerAs:"refund"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_refund/interactive.js")}]}}).state("refund.home.refund_step_1",{url:"/refund_step_1",views:{refund_head:{templateUrl:"view/v_refund/refund_step_1.html"}}}).state("refund.home.refund_step_2",{url:"/refund_step_2",views:{refund_head:{templateUrl:"view/v_refund/refund_step_2.html"}}}).state("refund.home.refund_step_3",{url:"/refund_step_3",views:{refund_head:{templateUrl:"view/v_refund/refund_step_3.html"}}}).state("forget",{url:"/forget",views:{forget_content:{templateUrl:"view/v_reset/reset.html"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["css/c_register/loading.css","lib/layui/css/modules/layer/default/layer.css","js/j_register/interactive.js"])}]}}).state("forget.public",{url:"/public",views:{forget_public:{templateUrl:"view/v_reset/reset_password.html"}}}).state("forget.public.process_1",{url:"/process_1",views:{forget_password:{templateUrl:"view/v_reset/reset_password_1.html",controller:"processOneCtrl",controllerAs:"process_1"}}}).state("forget.public.process_2",{url:"/process_2",views:{forget_password:{templateUrl:"view/v_reset/reset_password_2.html",controller:"processTwoCtrl",controllerAs:"process_2"}}}).state("forget.public.process_3",{url:"/process_3",views:{forget_password:{templateUrl:"view/v_reset/reset_password_3.html",controller:"processThreeCtrl",controllerAs:"process_3"}}}).state("center",{url:"/center",views:{center_content:{templateUrl:"view/v_center/v_centernav.html",controller:"styleCtrl",controllerAs:"center"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/interactive.js")}]}}).state("center.mhome",{url:"/mhome",views:{user_right:{templateUrl:"view/v_center/v_mypage.html",controller:"homeCtrl",controllerAs:"mhome"}}}).state("center.mdata",{url:"/mdata",views:{user_right:{templateUrl:"view/v_center/v_mdata.html",controller:"mDataCtrl",controllerAs:"mdata"}}}).state("center.morder",{url:"/morder",views:{user_right:{templateUrl:"view/v_center/v_morder.html",controller:"mMorderCtrl",controllerAs:"morder"}}}).state("morder_wrap",{url:"/morder_wrap",views:{my_orderdetail:{templateUrl:"view/v_center/v_morder_wrap.html"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/interactive.js")}]}}).state("morder_wrap.morder_detail",{url:"/morder_detail",views:{order_detail:{templateUrl:"view/v_center/v_morder_detail.html",controller:"order_detailCtrl",controllerAs:"order_detail"}}}).state("center.mcollection",{url:"/mcollection",views:{user_right:{templateUrl:"view/v_center/v_mcollection.html",controller:"mCollectionCtrl",controllerAs:"mcollection"}}}).state("center.msgcenter",{url:"/msgcenter",views:{user_right:{templateUrl:"view/v_center/v_msgcenter.html",controller:"msgCtrl",controllerAs:"msgcenter"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["css/c_master/reminde.css","css/c_master/dialog.css","css/c_master/dialog-theme.css"])}]}}).state("center.setting",{url:"/setting",views:{user_right:{templateUrl:"view/v_center/v_setting.html",controller:"mSettingCtrl",controllerAs:"setting"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.maddress",{url:"/maddress",views:{user_right:{templateUrl:"view/v_center/v_address.html",controller:"mAddressCtrl",controllerAs:"maddress"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/maddress.js")}]}}).state("center.authentication",{url:"/setting/authentication",views:{user_right:{templateUrl:"view/v_center/authentication.html"}}}).state("center.authentication.authentication_1",{url:"/authentication_1",views:{authentication:{templateUrl:"view/v_center/authentication_1.html",controller:"auth1",controllerAs:"authentication_1"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.reset",{url:"/setting/reset",views:{user_right:{templateUrl:"view/v_center/reset.html"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.reset.reset_1",{url:"/reset_1",views:{reset:{templateUrl:"view/v_center/reset_1.html",controller:"reset1",controllerAs:"reset_1"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.reset.reset_2",{url:"/reset_2",views:{reset:{templateUrl:"view/v_center/reset_2.html",controller:"reset2",controllerAs:"reset_2"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.reset.reset_3",{url:"/reset_3",views:{reset:{templateUrl:"view/v_center/reset_3.html",controller:"reset3",controllerAs:"reset_3"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.reset.reset_4",{url:"/reset_4",views:{reset:{templateUrl:"view/v_center/reset_4.html",controller:"reset4",controllerAs:"reset_4"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.reset.reset_5",{url:"/reset_5",views:{reset:{templateUrl:"view/v_center/reset_5.html",controller:"reset5",controllerAs:"reset_5"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.reset.reset_6",{url:"/reset_6",views:{reset:{templateUrl:"view/v_center/reset_6.html",controller:"reset6",controllerAs:"reset_6"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.bind",{url:"/setting/bind",views:{user_right:{templateUrl:"view/v_center/bind.html"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.bind.bind_phone_1",{url:"/bind_phone_1",views:{bind_phone:{templateUrl:"view/v_center/bind_phone_1.html",controller:"bind_phone1",controllerAs:"bind_phone_1"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.bind.bind_phone_2",{url:"/bind_phone_2",views:{bind_phone:{templateUrl:"view/v_center/bind_phone_2.html",controller:"bind_phone2",controllerAs:"bind_phone_2"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.bind.bind_phone_3",{url:"/bind_phone_3",views:{bind_phone:{templateUrl:"view/v_center/bind_phone_3.html",controller:"bind_phone3",controllerAs:"bind_phone_3"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.email",{url:"/setting/email",views:{user_right:{templateUrl:"view/v_center/email.html"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.email.email_1",{url:"/email_1",views:{email:{templateUrl:"view/v_center/email_1.html",controller:"emailOne",controllerAs:"email_1"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.email.email_2",{url:"/email_2",views:{email:{templateUrl:"view/v_center/email_2.html",controller:"emailTwo",controllerAs:"email_2"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("center.email.email_3",{url:"/email_3",views:{email:{templateUrl:"view/v_center/email_3.html",controller:"emailThree",controllerAs:"email_3"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/safe.js")}]}}).state("shopdetails",{url:"/shopdetails",views:{shop_content:{templateUrl:"view/v_shopdetails/v_shopdetail.html",controller:"shopDetailCtrl",controllerAs:"shopdetails"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["js/j_shopdetail/parabola.js","js/j_shopdetail/shopdetail.js"])}]}}).state("successcase",{url:"/successcase",views:{sc_content:{templateUrl:"view/v_successcase/v_successcase.html",controller:"successcaseCtrl",controllerAs:"successcase"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["js/j_successcase/successcase.js"])}]}}).state("reservation",{url:"/reservation",views:{reservation_content:{templateUrl:"view/v_reservation/v_startreservation.html",controller:"reservationCtrl",controllerAs:"reservation"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["lib/laydate/laydate.js","js/j_reservation/reservation.js"])}]}}).state("waitcontact",{url:"/waitcontact",views:{reservation_content:{templateUrl:"view/v_reservation/v_waitcontact.html",controller:"waitcontactCtrl",controllerAs:"waitcontact"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["js/j_reservation/waitcontact.js"])}]}}).state("advancelist",{url:"/advancelist",views:{reservation_content:{templateUrl:"view/v_reservation/v_advancePaymentList.html",controller:"advancelistCtrl",controllerAs:"advancelist"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["js/j_reservation/advancePaymentList.js"])}]}}).state("materiallist",{url:"/materiallist",views:{reservation_content:{templateUrl:"view/v_reservation/v_material.html",controller:"materiallistCtrl",controllerAs:"materiallist"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["js/j_reservation/material.js"])}]}}).state("calresult",{url:"/calresult",views:{calresult_content:{templateUrl:"view/v_cal/v_calresult.html",controller:"calresultCtrl",controllerAs:"calresult"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["js/j_cal/calresult.js"])}]}}).state("master",{url:"/master",views:{master_content:{templateUrl:"view/v_master/v_masterNav.html",controller:"leftCtrl",controllerAs:"master"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("master.mhome",{url:"/mhome",views:{master_right:{templateUrl:"view/v_master/master_home.html",controller:"mhomeCtrl",controllerAs:"mhome"}}}).state("master.mdata",{url:"/mdata",views:{master_right:{templateUrl:"view/v_master/personal_information.html",controller:"mDataCtrl",controllerAs:"mdata"}}}).state("master.mshop",{url:"/mshop",views:{master_right:{templateUrl:"view/v_master/my_shop.html",controller:"mShopCtrl",controllerAs:"mshop"}}}).state("master.morder",{url:"/morder",views:{master_right:{templateUrl:"view/v_master/my_order.html",controller:"mOrderCtrl",controllerAs:"morder"}}}).state("order",{url:"/order",views:{order_content:{templateUrl:"view/v_master/order.html"}}}).state("order.home",{url:"/home",views:{my_order:{templateUrl:"view/v_master/order_home.html",controller:"order",controllerAs:"order"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("budget_sheet",{url:"/budget_sheet",views:{budget_sheet:{templateUrl:"view/v_master/budget_sheet.html"}}}).state("budget_sheet.edit_sheet",{url:"/edit_sheet",views:{edit_sheet:{templateUrl:"view/v_master/edit_sheet.html",controller:"edit_sheetCtrl",controllerAs:"edit_sheet"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("material",{url:"/material",views:{material:{templateUrl:"view/v_master/material_wrap.html",controller:"materialCtrl",controllerAs:"material"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("material.list",{url:"/list",views:{material_type:{templateUrl:"view/v_master/material_list.html",controller:"listCtrl",controllerAs:"list"}}}).state("master.mwork",{url:"/mwork",views:{master_right:{templateUrl:"view/v_master/my_works.html",controller:"mWorkCtrl",controllerAs:"mwork"}}}).state("master.newwork",{url:"/mwork/newwork",views:{master_right:{templateUrl:"view/v_master/addNewWork.html",controller:"newworkCtrl",controllerAs:"newwork"}}}).state("success",{url:"/success",views:{success_content:{templateUrl:"view/v_master/success_wrap.html"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("success.success_case",{url:"/success_case",views:{success_case:{templateUrl:"view/v_master/success.html",controller:"success_caseCtrl",controllerAs:"success_case"}}}).state("master.mteam",{url:"/mteam",views:{master_right:{templateUrl:"view/v_master/my_team.html",controller:"mTeamCtrl",controllerAs:"mteam"}}}).state("master.teamDetail",{url:"/teamDetail",views:{master_right:{templateUrl:"view/v_master/teamDetail.html",controller:"teamDetailCtrl",controllerAs:"teamDetail"}}}).state("master.teamDetail_list",{url:"/teamDetail_list",views:{master_right:{templateUrl:"view/v_master/teamDetail_list.html",controller:"teamDetail_listCtrl",controllerAs:"teamDetail_list"}}}).state("master.teamDetail_edit",{url:"/teamDetail_edit",views:{master_right:{templateUrl:"view/v_master/teamDetail_edit.html",controller:"teamDetail_editCtrl",controllerAs:"teamDetail_edit"}}}).state("master.setting",{url:"/setting",views:{master_right:{templateUrl:"view/v_master/safe_set.html",controller:"SettingCtrl",controllerAs:"setting"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.msginfo",{url:"/msginfo",views:{master_right:{templateUrl:"view/v_master/information_center.html",controller:"msginfoCtrl",controllerAs:"msginfo"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load(["css/c_master/reminde.css","css/c_master/dialog.css","css/c_master/dialog-theme.css"])}]}}).state("master.mwallet",{url:"/mwallet",views:{master_right:{templateUrl:"view/v_master/my_wallet.html",controller:"billCtrl",controllerAs:"mwallet"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("master.authentication",{url:"/setting/authentication",views:{master_right:{templateUrl:"view/v_master/authentication.html"}}}).state("master.authentication.authentication_1",{url:"/authentication_1",views:{authentication:{templateUrl:"view/v_master/authentication_1.html",controller:"auth1",controllerAs:"authentication_1"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.reset",{url:"/setting/reset",views:{master_right:{templateUrl:"view/v_master/reset.html"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.reset.reset_1",{url:"/reset_1",views:{reset:{templateUrl:"view/v_master/reset_1.html",controller:"reset1",controllerAs:"reset_1"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.reset.reset_2",{url:"/reset_2",views:{reset:{templateUrl:"view/v_master/reset_2.html",controller:"reset2",controllerAs:"reset_2"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.reset.reset_3",{url:"/reset_3",views:{reset:{templateUrl:"view/v_master/reset_3.html",controller:"reset3",controllerAs:"reset_3"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.reset.reset_4",{url:"/reset_4",views:{reset:{templateUrl:"view/v_master/reset_4.html",controller:"reset4",controllerAs:"reset_4"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.reset.reset_5",{url:"/reset_5",views:{reset:{templateUrl:"view/v_master/reset_5.html",controller:"reset5",controllerAs:"reset_5"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.reset.reset_6",{url:"/reset_6",views:{reset:{templateUrl:"view/v_master/reset_6.html",controller:"reset6",controllerAs:"reset_6"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.bind",{url:"/setting/bind",views:{master_right:{templateUrl:"view/v_master/bind.html"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.bind.bind_phone_1",{url:"/bind_phone_1",views:{bind_phone:{templateUrl:"view/v_master/bind_phone_1.html",controller:"bind_phone1",controllerAs:"bind_phone_1"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.bind.bind_phone_2",{url:"/bind_phone_2",views:{bind_phone:{templateUrl:"view/v_master/bind_phone_2.html",controller:"bind_phone2",controllerAs:"bind_phone_2"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.bind.bind_phone_3",{url:"/bind_phone_3",views:{bind_phone:{templateUrl:"view/v_master/bind_phone_3.html",controller:"bind_phone3",controllerAs:"bind_phone_3"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.email",{url:"/setting/email",views:{master_right:{templateUrl:"view/v_master/email.html"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.email.email_1",{url:"/email_1",views:{email:{templateUrl:"view/v_master/email_1.html",controller:"emailOne",controllerAs:"email_1"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.email.email_2",{url:"/email_2",views:{email:{templateUrl:"view/v_master/email_2.html",controller:"emailTwo",controllerAs:"email_2"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("master.email.email_3",{url:"/email_3",views:{email:{templateUrl:"view/v_master/email_3.html",controller:"emailThree",controllerAs:"email_3"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/safe.js")}]}}).state("withdraw",{url:"/withdraw",views:{withdraw_content:{templateUrl:"view/v_master/withdraw.html"}}}).state("withdraw.home",{url:"/home",views:{my_withdraw:{templateUrl:"view/v_master/withdraw_home.html",controller:"withdraw",controllerAs:"home"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("bank",{url:"/bank",views:{bank_content:{templateUrl:"view/v_master/bank.html"}}}).state("bank.home",{url:"/home",views:{my_bank:{templateUrl:"view/v_master/bank_home.html",controller:"bank",controllerAs:"home"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("bank.add_process1",{url:"/add_process1",views:{my_bank:{templateUrl:"view/v_master/add_process1.html",controller:"process1",controllerAs:"add_process1"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("bank.add_process2",{url:"/add_process2",views:{my_bank:{templateUrl:"view/v_master/add_process2.html",controller:"process2",controllerAs:"add_process2"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("bank.add_process3",{url:"/add_process3",views:{my_bank:{templateUrl:"view/v_master/add_process3.html",controller:"process3",controllerAs:"add_process3"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("bank.add_process4",{url:"/add_process4",views:{my_bank:{templateUrl:"view/v_master/add_process4.html",controller:"process4",controllerAs:"add_process4"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_master/interactive.js")}]}}).state("success_pay",{url:"/success_pay",views:{success_pay:{templateUrl:"view/v_pay/v_success_pay_wrap.html"}},resolve:{deps:["$ocLazyLoad",function(a){return a.load("js/j_center/interactive.js")}]}}).state("success_pay.pay_end",{url:"/pay_end",views:{pay_end:{templateUrl:"view/v_pay/v_success_pay_end.html",controller:"pay_endCtrl",controllerAs:"pay_end"}}}),b.when("","/").when("/center","/center/mhome").when("/master","/master/mhome").when("/success","/success/success_case").when("/withdraw","/withdraw/home").when("/order","/order/home").when("/budget_sheet","/budget_sheet/edit_sheet").when("/bank","/bank/home").when("/refund","/refund/home").when("/forget","/forget/public/process_1").when("/center/setting/bind","/center/setting/bind/bind_phone_1").when("/center/setting/email","/center/setting/email/email_1").when("/center/setting/authentication","/center/setting/authentication/authentication_1").when("/center/setting/reset","/center/setting/reset/reset_1").when("/master/setting/bind","/master/setting/bind/bind_phone_1").when("/master/setting/email","/master/setting/email/email_1").when("/master/setting/authentication","/master/setting/authentication/authentication_1").when("/master/setting/reset","/master/setting/reset/reset_1")}])});