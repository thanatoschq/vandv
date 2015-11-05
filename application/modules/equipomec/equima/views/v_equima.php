<script type="text/javascript">
	/********************* generamos el name espace ***************************/
	equima = {};
	equima.cod_maqequi = '';
	equima.st_anios = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'equima/getAnios',
			reader:{
				type:'json',
				rootProperty:'data'
			}
		},
		autoLoad:true
	});
	
</script>

<!--  cargamos los componentes -->
<?php echo $this->load->view('grid_combus')?>
<?php echo $this->load->view('grid_ubica')?>

<script type="text/javascript">
	/*************************** stores **********************/
	equima.st_cmb_tipmaq = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'equima/getTipoMaq',
			reader:{
				type:'json',
				rootProperty:'data'
			}
		}
	});

	equima.st_cmb_propiet = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'equima/getPropiet',
			reader:{
				type:'json',
				rootProperty:'data'
			}
		}
	});

	equima.st_grid_maquina = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'equima/getMaquina',
			reader:{
				type:'json',
				rootProperty:'data'
			}
		},
		autoLoad:true
	});

	equima.st_view_files = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'equima/getMaqImages',
			reader:{
				type:'json',
				rootProperty:'data'
			}
		}
	});
	/*************************** window **********************/	
	equima.window_new = function(opt,rec){
		Ext.create('Ext.window.Window',{
			title:'Nueva Maquinaria',modal:true,
			height:500,width:420,
			id:'win_maqequi_new',
			items:[{
				xtype:'form',bodyPadding:5,
				url:'equima/regMaqEqui/'+opt,
				layout:'anchor',
				id:'frm_maqequi',
				defaults:{
					anchor:'100%'
				},
				defaultType:'textfield',
				items:[{
					fieldLabel:'Tipo:',
					xtype:'combobox',store:equima.st_cmb_tipmaq,
					displayField: 'descripcion',
    				valueField: 'id_maqequi',
    				listeners:{
    					select:function(combo, record, eOpts){
    						txt = Ext.getCmp('txt_maqequi_wn_cod');
    						txt.setValue(record.data.abrev+'000');
    					}
    				},
    				name:'id_maqequi'
				},{
					fieldLabel:'Codigo:',id:'txt_maqequi_wn_cod',
					name:'cod_maqequi'
				},{
					fieldLabel:'Propietario:',
					xtype:'combobox',store:equima.st_cmb_propiet,
					displayField: 'abrv',
    				valueField: 'id_propietario',
    				name:'id_propietario'
				},{
					fieldLabel:'Descripcion:',
					xtype:'textarea',
					name:'desc_maqequi'
				},{
					fieldLabel:'Placa:',
					name:'placa_maqequi'
				},{
					fieldLabel:'Marca:',
					name:'marca_maqequi'
				},{
					fieldLabel:'Modelo:',
					name:'modelo_maqequi'
				},{
					fieldLabel:'Serie:',
					name:'serie_maqequi'
				},{
					fieldLabel:'Motor:',
					name:'motor_maqequi'
				},{
					fieldLabel:'Color:',
					name:'color_maqequi'
				},{
					fieldLabel:'Valor:',xtype:'numberfield',
					name:'valor_maqequi'
				},{
					fieldLabel:'Horas de Mant.:',
					xtype:'numberfield',
					name:'horas_maqequi'
				},{
					fieldLabel:'Observaciones',
					xtype:'textarea',
					name:'obs_maqequi'
				}]
			}],
			buttons:[{
				text:'Guardar',handler:function(){
					frm = Ext.getCmp('frm_maqequi');
					frm.submit({
						params:{
							opt:opt
						},
						success:function(form, action){
							Ext.Msg.alert('Sistema',action.result.msg);
							equima.st_grid_maquina.reload();
							win = Ext.getCmp('win_maqequi_new');
							win.close();
						},
						failure:function(form, action){
							Ext.Msg.alert('Error',action.result.msg);
						}
					});
				}
			},{
				text:'Cancelar',handler:function(){
					win = Ext.getCmp('win_maqequi_new');
					win.close();
				}
			}]
		}).show();

		if(opt == 'e'){
			equima.st_cmb_propiet.load();
			equima.st_cmb_tipmaq.load();
			frm = Ext.getCmp('frm_maqequi');
			frm.loadRecord(rec[0]);
		}
	}

	equima.window_upload = function(){
		Ext.create('Ext.window.Window',{
			title:'Nueva Maquinaria',modal:true,
			height:150,width:350,
			id:'equima_window_upload',
			items:[{
				xtype:'form',region:'center',
				bodyPadding:5,id:'equima_window_frm_upload',
				url:'equima/uploadImagen',
				defaults:{
					anchor:'100%'
				},
				defaultType:'textfield',
				items:[{
					fieldLabel:'Archivo:',
					xtype:'filefield',
					name:'file'
				},{
					fieldLabel:'Descripcion:',
					name:'desc'
				}]
			}],
			buttons:[{
				text:'Adjuntar',handler:function(){
					frm = Ext.getCmp('equima_window_frm_upload');
					frm.submit({
						params:{
							cod_maqequi:equima.cod_maqequi
						},
						waitMsg: 'Subiendo la foto...',
                    	success: function(form, action) {
                        	rpta = action.result.sucess;
                        	if(rpta == "true"){
                        		Ext.Msg.alert('Sistema',action.result.msg);
                        	}else{
                        		Ext.Msg.alert('Sistema',action.result.msg);
                        	}
                        	equima.st_view_files.reload();
                    		win = Ext.getCmp('equima_window_upload');
							win.close();    	
                    	},
                    	failure:function(form,action){
                    		Ext.Msg.alert('Sistema','Error en la conexion del sistema');
                    	}	
					});
				}
			},{
				text:'Cancelar',handler:function(){
					win = Ext.getCmp('equima_window_upload');
					win.close();
				}
			}]
		}).show();
	};

	/*************************** main ************************/
	equima.grid = Ext.create('Ext.grid.Panel',{
		region:'north',split:true,height:300,
		//forceFit:true,
		id:'equima_grid_main',
		columns:[
			{text:'Codigo',dataIndex:'cod_maqequi',width:'50'},
			{text:'Tipo',dataIndex:'descripcion'},
			{text:'Descripcion',dataIndex:'desc_maqequi',width:500},
			{text:'Placa',dataIndex:'placa_maqequi'},
			{text:'Marca',dataIndex:'marca_maqequi'},
			{text:'Modelo',dataIndex:'modelo_maqequi',width:200},
			{text:'Serie',dataIndex:'serie_maqequi',width:300}
		],
		tbar:[{
			text:'Nuevo',handler:function(){
				equima.window_new('n','');
			}
		},{
			text:'Editar',handler:function(){
				rec = Ext.getCmp('equima_grid_main').getSelection();
				//console.log(rec[0].data);
				if (rec[0]){
					equima.window_new('e',rec);
				}else{
					Ext.Msg.alert('Error','Seleccione un registro a enviar!');
				}
			}
		},{
			text:'Inactivar'
		},'->',{
			xtype:'label',text:'Buscar:'
		},{
			xtype:'combobox'
		},{
			xtype:'textfield'
		},{
			text:'Buscar',handler:function(){

			}
		}],
		store:equima.st_grid_maquina,
		listeners:{
			select:function(ths, record, index, eOpts ){
				/*equima.mant.st_grid.reload({
					params:{
						anio_eje:'<?php echo $anio_eje;?>',
						cod_maqequi:record.data.cod_maqequi
					}
				});*/

				equima.st_view_files.reload({
					params:{
						cod_maqequi:record.data.cod_maqequi
					}
				});
				equima.cod_maqequi = record.data.cod_maqequi;
				frm = Ext.getCmp('maqequi_form_main');
				frm.loadRecord(record);
			}
		}
	});

	equima.form = Ext.create('Ext.tab.Panel',{
		region:'center',
		items:[{
			title:'Detalle',layout:'border',
			items:[{
				xtype:'form',id:'maqequi_form_main',
				region:'center',layout:'absolute',
				items:[{
					xtype:'label',text:'Codigo:',x:5,y:5
				},{
					xtype:'textfield',x:90,y:5,name:'cod_maqequi',width:80
				},{
					xtype:'label',text:'Propietario:',x:220,y:5
				},{
					xtype:'textfield',x:300,y:5,width:100,name:'abrv'
				},{
					xtype:'label',text:'Tipo:',x:5,y:35
				},{
					xtype:'textfield',x:90,y:35,name:'descripcion',width:100
				},{
					xtype:'label',text:'Descripcion:',x:220,y:35
				},{
					xtype:'textfield',x:300,y:35,name:'desc_maqequi',width:300
				},{
					xtype:'label',text:'N° de Serie:',x:5,y:65
				},{
					xtype:'textfield',x:90,y:65,name:'serie_maqequi',width:150
				},{
					xtype:'label',text:'Placa:', x:250,y:65
				},{
					xtype:'textfield',x:300,y:65,name:'placa_maqequi',width:100
				},{
					xtype:'label',text:'Marca:',x:5,y:95
				},{
					xtype:'textfield',x:90,y:95,name:'marca_maqequi',width:120
				},{
					xtype:'label', text:'Modelo:',x:220,y:95
				},{
					xtype:'textfield',x:300,y:95,name:'modelo_maqequi',width:120
				},{
					xtype:'label', text:'N° Motor:',x:5,y:125
				},{
					xtype:'textfield',x:90, y:125, name:'motor_maqequi',width:100
				},{
					xtype:'label', text:'Color:', x:220, y:125
				},{
					xtype:'textfield',x:300,y:125,name:'color_maqequi', width:150
				},{
					xtype:'label',text:'Tiempo de Reparación:',x:5,y:155
				},{
					xtype:'textfield',x:150,y:155,name:'horas_maqequi',width:80
				},{
					xtype:'label',text:'horas',x:235,y:155
				},{
					xtype:'label',text:'Observaciones:',x:5,y:185
				},{
					xtype:'textarea',x:150,y:185,name:'obs_maqequi',width:300
				}]
			},{
				title:'Imagenes',region:'east',width:500,split:true,layout:'border',
				items:[{
					xtype:'dataview',region:'center',
					store:equima.st_view_files,
					scrollable:true,
					tpl: [
						'<tpl for=".">',
	        				'<div class="dataview-multisort-item" class="thumb-wrap">',
	          					'<img src="{path_img}" widht:"100" height="100"/>',
	          				'<br/><span>{desc_img}</span>',
	        				'</div>',
	    				'</tpl>'
					],
					itemSelector: 'div.thumb-wrap',
	    			emptyText: 'Sin Imagenes...',
	    			listeners:{
	    				itemclick:function( ths, record, item, index, e, eOpts ){
	    					console.log(item);
	    				}
	    			}
				}],
				collapsible:true,
				tbar:[{
					text:'Agregar',handler:function(){
						equima.window_upload();
					}
				},{
					text:'Eliminar'
				}]
			}]
		}/*,{
			title:'Mantenimiento',layout:'border',
			items:[equima.mant.grid]
		}*/,{
			title:'Ubicaciones',layout:'border',
			items:[equima.grid_ubica]
		}/*,{
			title:'Valorizaciones',layout:'border',
			items:[equima.grid_valoriza]
		}*/,{
			title:'Combustible',layout:'border',
			items:[equima.grid_combus]
		}]
	});

	tab = Ext.getCmp('tab-appEquima');
	tab.add(equima.grid,equima.form);
</script>