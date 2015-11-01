<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
	<title>Sistema Integrado VandV</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="../ext-6/build/classic/theme-neptune/resources/theme-neptune-all.css">
	<link rel="stylesheet" type="text/css" href="../ext-6/build/packages/charts/classic/neptune/resources/charts-all.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('tools/css/icons.css')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('tools/css/app.css')?>">
	<script type="text/javascript" src="../ext-6/build/ext-all-debug.js"></script>
	<script type="text/javascript" src="<?php echo base_url('tools/jquery/jquery-2.1.4.js')?>"></script>
	<script type="text/javascript" src="../ext-6/build/classic/theme-neptune/theme-neptune.js"></script>
	<script type="text/javascript" src="../ext-6/build/packages/charts/classic/charts.js"></script>
	<script type ="text/javascript">
		Ext.onReady(function(){
			Ext.create('Ext.container.Viewport',{
				layout:'border',
				renderTo:Ext.getBody(),
				items:[{
					xtype:'tabpanel',
					title:'Sistema Integrado V&V',
					id:'tab-main',
					region:'center',
					items:{
						title:'Nuevo'
					}
				},{
					xtype:'treepanel',
					title:'Menu de Sistema',split:true,collapsible:true,
					region:'west',width:200,
					rootVisible:false,
					store: Ext.create('Ext.data.TreeStore',{
						proxy:{
							type:'ajax',
							url:'menu/menu'
						}
					}),
					listeners:{
			            itemclick:function(t, record, item, index, e, eOpts){
			                tab = Ext.getCmp('tab-main');
			                if(record.data.leaf == true){
			                	//console.log(record);
			                    if (Ext.ComponentManager.get('tab-'+record.data.name) == undefined){
			                        tab.add({
			                            xtype:'panel',
			                            title:record.data.text,
			                            id:'tab-'+record.data.name,
			                            closable:true,
			                            layout:'border',
			                            listeners:{
			                                'render':function(t,eOpts){
			                                    $.post(record.data.controller,function(data){
			                                        $('#appLoader').html(data);
			                                    });
			                                }
			                            }
			                        });    
			                    }
			                    tab.setActiveTab('tab-'+record.data.name);
			                }
			            }
			        },
			        tbar:[{
			        	text:'Opciones de Usuario',plain:true,
			        	xtype: 'button',icon:null,
            			glyph:73,iconAlign:'left',
			        	menu:[{
			        		text:'Datos del Usuario'
			        	},'-',{
			        		text:'Cerrar session'
			        	}]
			        }]
				}]
			});
		});
	</script>
</head>
<body>
	<div id="appLoader">
	</div>
</body>
</html>