<script type="text/javascript">
eqm_valoriza = {};
eqm_valoriza.anio = '';
eqm_valoriza.obra = '';
eqm_valoriza.valorizacion = '';
eqm_valoriza.id_maq_val = '';
/************************* stores *****************************/
eqm_valoriza.st_anios = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'eqmvaloriza/getAnios',
			reader:{
				type:'json',
				rootProperty:'data'
			}
		},
		autoLoad:true
});

eqm_valoriza.st_obras = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'eqmvaloriza/getObras',
			reader:{
				type:'json',	
				rootProperty:'data'
			}
		}
});

eqm_valoriza.st_valoriza = Ext.create("Ext.data.Store",{
	proxy:{
		type:'ajax',
		url:'eqmvaloriza/getValorizaciones',
		reader:{
			type:'json',rootProperty:'data'
		}
	}
});

eqm_valoriza.st_meses = Ext.create("Ext.data.Store",{
	proxy:{
		type:'ajax',
		url:'eqmvaloriza/getMeses',
		reader:{
			type:'json',rootProperty:'data'
		}
	}
});

eqm_valoriza.grid_maquina = Ext.create("Ext.data.Store",{
	proxy:{
		type:'ajax',
		url:'eqmvaloriza/getValMaquina',
		reader:{
			type:'json',rootProperty:'data'
		}
	}
});
	
eqm_valoriza.st_grid_valoriza = Ext.create("Ext.data.Store",{
	proxy:{
		type:'ajax',
		url:'eqmvaloriza/getMaquiValoriza',
		reader:{
			type:'json',rootProperty:'data'
		}
	}
});

eqm_valoriza.st_maquiequi = Ext.create("Ext.data.Store",{
	proxy:{
		type:'ajax',
		url:'eqmvaloriza/getMaquinariaEquipo',
		reader:{
			type:'json',rootProperty:'data'
		}
	}
});

eqm_valoriza.st_trabajador = Ext.create("Ext.data.Store",{
	proxy:{
		type:'ajax',
		url:'eqmvaloriza/getTrabajador',
		reader:{
			type:'json',rootProperty:'data'
		}
	}
});

eqm_valoriza.st_estado_maqequi = Ext.create("Ext.data.Store",{
	data:[
		{cod_estado:'O',desc_estado:'OPERATIVO'},
		{cod_estado:'F',desc_estado:'FALLAS'}
	]
});

eqm_valoriza.st_unidad_valoriza = Ext.create("Ext.data.Store",{
	data:[
		{cod_unidad:'D',desc_unidad:'DIAS'},
		{cod_unidad:'H',desc_unidad:'HORAS'}
	]
});

/********************** windows *********************************/

eqm_valoriza.window_nuevo = function(){
	Ext.create('Ext.window.Window',{
		title:'Nueva Valorizacion',modal:true,
		height:120,width:300,
		id:'eqm_valoriza_window_new',
		items:[{
			xtype:'form',bodyPadding:5,
			id:'eqm_val_win_form',
			url:'eqmvaloriza/regNuevaVal',
			layout:'anchor',
			defaults:{
				anchor:'100%'
			},
			defaultType:'textfield',
			items:[{
				fieldLabel:'Periodo:',
				xtype:'combobox',
				store:eqm_valoriza.st_meses,
				valueField:'mes_id',
				displayField:'mes_name',
				name:'periodo'
			}]
		}],
		buttons:[{
			text:'Guardar',handler:function(){
				frm = Ext.getCmp('eqm_val_win_form');
				frm.submit({
					params:{
						anio:eqm_valoriza.anio,
						obra:eqm_valoriza.obra
					},
					success:function(form,action){
						Ext.Msg.alert('Sistema',action.result.msg);
						eqm_valoriza.st_valoriza.reload({
							anio:eqm_valoriza.anio,
	    					obra:eqm_valoriza.obra
						});
						win = Ext.getCmp('eqm_valoriza_window_new');
						win.close();
					},
					failure:function(){
						win = Ext.getCmp('eqm_valoriza_window_new');
						win.close();
					}
				})
			}
		},{
			text:'Cancelar',handler:function(){
				win = Ext.getCmp('eqm_valoriza_window_new');
				win.close();
			}
		}]
	}).show();
};

eqm_valoriza.window_maquinaria = function(){
	Ext.create('Ext.window.Window',{
		title:'Ingresar Nueva maquinaria',
		modal:true,width:500,height:300,
		id:'eqm_valoriza_window_maquinaria',
		items:[{
			xtype:'form',bodyPadding:5,
			id:'eqm_valoriza_form_maquinaria',
			url:'eqmvaloriza/regNuevaMaquina',
			layout:'anchor',
			defaults:{
				anchor:'100%'
			},
			defaultType:'textfield',
			items:[{
				fieldLabel:'Maquina/Equipo:',
				xtype:'combobox',
				//queryMode:'local',
				store:eqm_valoriza.st_maquiequi,
				displayField: 'desc_maqequi',
    			valueField: 'cod_maqequi',
    			name:'cod_maqequi'
			},{
				fieldLabel:'Responsable:',
				xtype:'combobox',
				store:eqm_valoriza.st_trabajador,
				displayField: 'nomb_trabajador',
    			valueField: 'cod_trabajador',
    			name:'cod_trabajador'
			},{
				fieldLabel:'Estado:',
				xtype:'combobox',
				store:eqm_valoriza.st_estado_maqequi,
				displayField: 'desc_estado',
    			valueField: 'cod_estado',
    			name:'emaq_det_maquina'
			},{
				fieldLabel:'Rep/Mant:',
				xtype:'numberfield'
			},{
				fieldLabel:'Planilla:',
				xtype:'numberfield'
			},{
				fieldLabel:'Trabajo Reliza.:',
				xtype:'textarea',
				name:'real_det_maquina'
			},{
				fieldLabel:'Observaciones:',
				xtype:'textarea',
				name:'obs_det_maquina'
			}]
		}],
		buttons:[{
			text:'Guardar',handler:function(){
				frm = Ext.getCmp('eqm_valoriza_form_maquinaria');
				frm.submit({
					params:{
						anio:eqm_valoriza.anio,
						obra:eqm_valoriza.obra,
						val:eqm_valoriza.valorizacion
					},
					success:function(form,action){
						Ext.Msg.alert('Sistema',action.result.msg);
						eqm_valoriza.st_valoriza.reload({
							anio:eqm_valoriza.anio,
	    					obra:eqm_valoriza.obra
						});
						win = Ext.getCmp('eqm_valoriza_window_new');
						win.close();
					},
					failure:function(){
						win = Ext.getCmp('eqm_valoriza_window_new');
						win.close();
					}
				});
			}
		},{
			text:'Cancelar',handler:function(){
				win = Ext.getCmp('eqm_valoriza_window_maquinaria');
				win.close();
			}
		}]

	}).show();
};

eqm_valoriza.window_detalle = function(){ 
	Ext.create('Ext.window.Window',{
		title:'Ingresar Nueva Valorizacion',
			modal:true,width:300,height:450,
			id:'eqm_valoriza_window_detalle',
			items:[{
				xtype:'form',bodyPadding:5,
				id:'eqm_valoriza_frm_detalle',
				url:'eqmvaloriza/regNuevoDetalle',
				layout:'anchor',
				defaults:{
					anchor:'100%'
				},
				defaultType:'textfield',
				items:[{
					fieldLabel:'Fecha',
					xtype:'datefield',format:'d/m/Y',
					name:'fech_maquina_val'

				},{
					fieldLabel:'Nº Parte:',
					name:'nro_parte_maquina_val'
				},{
					fieldLabel:'Unidad',
					xtype:'combobox',
					store:eqm_valoriza.st_unidad_valoriza,
					valueField:'cod_unidad',
					displayField:'desc_unidad',
					name:'unid_maquina_val',
					listeners:{
						select:function(combo, record, eOpts){
							grid = Ext.getCmp('eqm_valoriza_grid_resumen');
							row = grid.getSelection()[0].getData();
							select = record.getData();
							Ext.Ajax.request({
								params:{
									anio:eqm_valoriza.anio,
									obra:eqm_valoriza.obra,
									maq:row.cod_maqequi,
									unid:select.cod_unidad
								},
								url:'eqmvaloriza/getPrecios',
								success:function(response, opts){
									var obj = Ext.decode(response.responseText).data;
         							txtprecio = Ext.getCmp('eqm_val_frm_det_precio');
         							txtPU = Ext.getCmp('eqm_val_frm_det_pu');
         							txtprecio.setValue(obj.precio_maq);
         							txtPU.setValue(obj.precio_combus);
								},
								failure:function(response, opts){
									Ext.Msg.alert('Error', 'Error en la conexion de datos');
								}
							});
						}
					}
				},{
					fieldLabel:'Precio:',
					xtype:'numberfield',readOnly:true,
					id:'eqm_val_frm_det_precio',
					name:'precio_maquina_val'
				},{
					fieldLabel:'Cantidad',enableKeyEvents:true,
					xtype:'numberfield',
					listeners:{
						keyup:function(ths, e, eOpts){
							p = Ext.getCmp('eqm_val_frm_det_precio');
							p = p.getValue();
							cant = ths.getValue();
							tot = Ext.getCmp('eqm_val_frm_tot_maq');
							tot.setValue(p*cant);	
						}
					},
					name:'cant_maquina_val'
				},{
					fieldLabel:'Total',readOnly:true,
					xtype:'numberfield',
					id:'eqm_val_frm_tot_maq',
					name:'tot_maquina_val'
				},{
					fieldLabel:'Combustible GLN:',
					xtype:'numberfield',enableKeyEvents:true,
					listeners:{
						keyup:function(ths, e, eOpts){
							p = Ext.getCmp('eqm_val_frm_det_pu');
							p = p.getValue();
							cant = ths.getValue();
							tot = Ext.getCmp('eqm_val_frm_tot_comb');
							tot.setValue(p*cant);
						}
					},
					name:'cant_combustible'
				},{
					fieldLabel:'P/U',readOnly:true,
					xtype:'numberfield',
					id:'eqm_val_frm_det_pu',
					name:'pu_combustible'
				},{
					fieldLabel:'Total',readOnly:true,
					xtype:'numberfield',
					id:'eqm_val_frm_tot_comb',
					name:'tot_combustible'
				},{
					fieldLabel:'Observaciones:',
					xtype:'textarea',
					name:'observ_maquina_val'
				}]
			}],
			buttons:[{
				text:'Guardar',handler:function(){

					grid = Ext.getCmp('eqm_valoriza_grid_resumen');
					row = grid.getSelection()[0].getData();
					frm = Ext.getCmp('eqm_valoriza_frm_detalle');
					frm.submit({
						params:{
							anio:eqm_valoriza.anio,
							obra:eqm_valoriza.obra,
							val:eqm_valoriza.valorizacion,
							iddet:row.id_det_valoriza
						},
						success:function(form,action){
							Ext.Msg.alert('Sistema',action.result.msg);
							eqm_valoriza.st_grid_valoriza.reload({
								params:{
									anio:eqm_valoriza.anio,
									obra:eqm_valoriza.obra,
									val:eqm_valoriza.valorizacion,
									maq:eqm_valoriza.id_det_valoriza
								}
							});
							win = Ext.getCmp('eqm_valoriza_window_detalle');
							win.close();
						},
						failure:function(form,action){
							win = Ext.getCmp('eqm_valoriza_window_detalle');
							win.close();
						}
					});
				}
			},{
				text:'Cancelar',handler:function(){
					win = Ext.getCmp('eqm_valoriza_window_detalle');
					win.close();
				}
			}]
	}).show();
}

/********************** formulario *****************************/
eqm_valoriza.grid_resumen = Ext.create('Ext.grid.Panel',{
	region:'north',split:true,height:300,forceFit:true,
		id:'eqm_valoriza_grid_resumen',
		title:'Maquinaria / Equipo',
		columns:[
			{text:'Fech Ini.',dataIndex:'desde'},
			{text:'Fech Fin.',dataIndex:'hasta'},
			{text:'Responsable',dataIndex:'nomb_trabajador'},
			{text:'Codigo',dataIndex:'cod_maqequi'},
			{text:'Maquinaria',dataIndex:'desc_maqequi'},
			{text:'Val.Actual',dataIndex:'val_actual',xtype:'numbercolumn',align:'right'},
			{text:'Combustible',dataIndex:'val_combus',xtype:'numbercolumn',align:'right'},
			{text:'Planilla',dataIndex:'',xtype:'numbercolumn',align:'right'},
			{text:'Rep/Mat',dataIndex:'',xtype:'numbercolumn',align:'right'},
			{text:'Rendimiento',dataIndex:'',xtype:'numbercolumn',align:'right'},
			{text:'Est.',dataIndex:'desc_estado_maquina'}
		],
		store:eqm_valoriza.grid_maquina,
		tbar:[{
			text:'Nuevo',handler:function(){
				eqm_valoriza.window_maquinaria();
			}
		},{
			text:'Editar'
		},{
			text:'Anular'
		},{
			text:'Imprimir Valorización',handler:function(){
				$.post('eqmvaloriza/printValoriza',{
					a:eqm_valoriza.anio,
					o:eqm_valoriza.obra,
					n:eqm_valoriza.valorizacion,
					i:eqm_valoriza.id_maq_val
				}, function(retData) {
  					window.open(retData,'_bank');
				}); 
			}
		},{
			text:'Impirmir Resumen',handler:function(){
				$.post('eqmvaloriza/printResumen',{
					a:eqm_valoriza.anio,
					o:eqm_valoriza.obra,
					v:eqm_valoriza.valorizacion
				}, function(retData) {
  					window.open(retData,'_bank');
				}); 
			}
		}],
		listeners:{
			select:function( ths, record, index, eOpts){
				row = record.getData();
				eqm_valoriza.id_maq_val = row.id_det_valoriza;
				eqm_valoriza.st_grid_valoriza.reload({
					params:{
						anio:row.anio_eje,
						obra:row.cod_obra,
						val:row.nro_valoriza,
						maq:row.id_det_valoriza
					}
				});
			}
		}
});

eqm_valoriza.grid_valoriza = Ext.create('Ext.grid.Panel',{
	region:'center',forceFit:true,
		//id:'equima_grid_main',
		title:'Costos',
		columns:[
			{text:'Fecha',dataIndex:'fech_maquina_val'},
			{text:'Nº Parte',dataIndex:'nro_parte_maquina_val'},
			{text:'Unidad',dataIndex:'unid_maquina_val'},
			{text:'Precio',dataIndex:'precio_maquina_val',xtype:'numbercolumn',align:'right'},
			{text:'Cantidad',dataIndex:'cant_maquina_val',xtype:'numbercolumn',align:'right'},
			{text:'Parcial',dataIndex:'tot_maquina_val',xtype:'numbercolumn',align:'right'},
			//{text:'Tip. Comb.',dataIndex:''},
			{text:'Cantidad',dataIndex:'cant_combustible',xtype:'numbercolumn',align:'right'},
			{text:'P/U',dataIndex:'pu_combustible',xtype:'numbercolumn',align:'right'},
			{text:'Total',dataIndex:'tot_combustible',xtype:'numbercolumn',align:'right'}
		],
		store:eqm_valoriza.st_grid_valoriza,
		tbar:[{
			text:'Agregar',handler:function(){
				eqm_valoriza.window_detalle();
			}
		},{
			text:'Editar'
		},{
			text:'Eliminar'
		}]
});

eqm_valoriza.panel_main = Ext.create('Ext.panel.Panel',{
	region:'center', layout:'border',
	dockedItems:[{
		xtype:'toolbar',dock:'top',
		items:[{
			xtype:'label',text:'Año:'
		},{
			xtype:'combo',width:80,
			store:eqm_valoriza.st_anios,
			displayField: 'anio_eje',
	    	valueField: 'anio_eje',
	    	queryMode:'local',
	    	listeners:{
	    		select:function(combo, record, eOpts){
	    			eqm_valoriza.st_obras.reload({
	    				params:{
	    					anio:record.data.anio_eje
	    				}
	    			});
	    			eqm_valoriza.anio = record.data.anio_eje;
	    		}
	    	}
		},'-',{
			xtype:'label',text:'Obra:'
		},{
			xtype:'combo',width:550,
			store:eqm_valoriza.st_obras,
			displayField: 'desc_obra',
	    	valueField: 'cod_obra',
	    	queryMode:'local',
	    	listeners:{
	    		select:function(combo, record, eOpts){
	    			eqm_valoriza.obra = record.data.cod_obra
	    			eqm_valoriza.st_valoriza.reload({
	    				params:{
	    					anio:record.data.anio_eje,
	    					obra:record.data.cod_obra
	    				}
	    			});
	    		}
	    	}
		}],
	}],
	items:[{
		xtype:'grid',width:200,region:'west',title:'Valorizaciones',split:true,
		columns:[
			{text:'Nro',dataIndex:'nro_valoriza'},
			{text:'Periodo',dataIndex:'mes_id'}
		],
		store:eqm_valoriza.st_valoriza,
		dockedItems:[{
			xtype:'toolbar',dock:'top',
			items:[{
				text:'Nuevo',handler:function(){
					eqm_valoriza.window_nuevo();
				}
			},{
				text:'Anular'
			}]
		},{
			xtype:'toolbar',dock:'top',
			items:[{
				xtype:'label',text:'Nro:'
			},{
				xtype:'textfield',width:60
			},{
				xtype:'button',text:'Buscar'
			}]
		}],
		listeners:{
			select:function( ths, record, index, eOpts ){
				eqm_valoriza.valorizacion = record.data.nro_valoriza;
				eqm_valoriza.grid_maquina.reload({
					params:{
						anio:eqm_valoriza.anio,
						obra:eqm_valoriza.obra,
						val:record.data.nro_valoriza
					}
				})
			}
		}
	},{
		xtype:'panel',region:'center',layout:'border',
		items:[
			eqm_valoriza.grid_resumen,
			eqm_valoriza.grid_valoriza
		]
	}]
});

tab = Ext.getCmp('tab-appEquiValo');
tab.add(
	eqm_valoriza.panel_main
);
</script>