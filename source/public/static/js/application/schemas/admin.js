/*=========================================================================================
 *@ListModules: Listado de todos los Modulos asociados al portal
 **//*===================================================================================*/
yOSON.AppSchema.modules = {
    'Auth': {
        controllers:{
            'Auth':{
                actions : {
                    'getLogin' : function(){
                        yOSON.AppCore.runModule('validation',{"form":"#frmLogin"});
                    },
                    'byDefault':function(){}
                },
                allActions:function(){}
            },
            byDefault : function(){},
            allActions: function(){}
        },
        byDefault : function(){},
        allControllers : function(){}
    },
    'Dashboard': {
        controllers:{
            'Dashboard':{
                actions : {
                    'sendOne' : function(){
                        yOSON.AppCore.runModule('validation',{"form":"#frmSmsSingle"});
                        yOSON.AppCore.runModule('countchar');
                    },
                    'sendOneAgend': function(){
                        yOSON.AppCore.runModule('validation',{"form":"#frmSmsSingleA"});
                        yOSON.AppCore.runModule('countchar');
                    },
                    'sendGroup': function(){
                        yOSON.AppCore.runModule('validation',{"form":"#frmSmsGroup"});
                        yOSON.AppCore.runModule('countchar');
                    },
                    'sendGroupAgend': function(){
                        yOSON.AppCore.runModule('validation',{"form":"#frmSmsGroupA"});
                        yOSON.AppCore.runModule('countchar');
                    },
                    'byDefault':function(){}
                },
                allActions:function(){}
            },
            'User':{
                actions : {
                    'editPerfil' : function(){
                        yOSON.AppCore.runModule('validation',{"form":"#frmPerfil"});
                    },
                    'byDefault':function(){}
                },
                allActions:function(){}
            },
            'Suply':{
                actions : {
                    'showUser' : function(){
                        yOSON.AppCore.runModule('dataTable',{"url":"/supply/user/list","table":"#tblAproUser"});
                        yOSON.AppCore.runModule('filtersDataTable');
                        yOSON.AppCore.runModule('actionDel');
                    },
                    'showGroup': function(){
                        yOSON.AppCore.runModule('dataTable',{"url":"/supply/group/list"});
                        yOSON.AppCore.runModule('actionDel');
                    },
                    'showUserOperation': function(){
                        yOSON.AppCore.runModule('validation',{"form":"#frmNewUser"});
                    },
                    'byDefault':function(){}
                },
                allActions:function(){}
            },
            'Report':{
                actions : {
                    'showDetail' : function(){
                        yOSON.AppCore.runModule('dataTable',{"url":"/report/detail/list","table":"#tblReporteDetalle"});
                        yOSON.AppCore.runModule('filtersDataTable');
                    },
                    'showConsolidated' : function(){
                        yOSON.AppCore.runModule('dataTable',{"url":"/report/consolidated/list"});
                        yOSON.AppCore.runModule('filtersDataTable');
                    },
                    'showConsolidatedDetail' : function(){
                        yOSON.AppCore.runModule('dataTable',{"url":"/report/consolidated/detaillist"});
                    },
                    'byDefault':function(){}
                },
                allActions:function(){}
            },
            byDefault : function(){},
            allActions: function(){}
        },
        byDefault : function(){},
        allControllers : function(){}
    },
    byDefault : function(){},
    allModules : function(oMCA){
        yOSON.AppCore.runModule('numeric');
        yOSON.AppCore.runModule('datepicker');
    }
};