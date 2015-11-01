<script type="text/javascript">
	equima.mant = {};
	/*************************** stores ****************************************/
	equima.mant.st_obras = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'equima/getObras',
			reader:{
				type:'json',
				rootProperty:'data'
			}
		},
	});

	equima.mant.st_frente = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'equima/getFrente',
			reader:{
				type:'json',
				rootProperty:'data'
			}
		},
	});

	equima.mant.st_tip_doc = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'equima/getTipoDoc',
			reader:{
				type:'json',
				rootProperty:'data'
			}
		},
	});

	equima.mant.st_grid = Ext.create("Ext.data.Store",{
		proxy:{
			type:'ajax',
			url:'equima/getMantenim',
			reader:{
				type:'json',
				rootProperty:'data'
			}
		},
		//autoLoad:true
	});

	/***************************** principal **********************************/
	equima.mant.window_grid = function(opt){
		Ext.create('Ext.window.Window',{
			title:'Nuevo registro de Mantenimiento',modal:true,
			height:380,width:400,
			id:'win_maqequi_mant_new',
			items:[{
				xtype:'form',bodyPadding:5,
				url:'equima/regMantEqui/',
				layout:'anchor',
				id:'frm_mant_maqequi',
				defaults:{
					anchor:'100%'
				},
				defaultType:'textfield',
				items:[{
					xtype:'hiddenfield',
					name:'id_em_mantenimiento'
				},{
					fieldLabel:'Año:',xtype:'combobox',
					queryMode:'local',
					displayField: 'anio_eje',
    				valueField: 'anio_eje',
					store:equima.st_anios,
					id:'cmb_win_frm_mant',
					listeners:{
						select:function(combo, record, eOpts ){
							equima.mant.st_obras.reload({
								params:{
									anio:record.data.anio_eje
								}
							});
						}
					},
					name:'anio_eje'
				},{
					fieldLabel:'Obra:',xtype:'combobox',
					store:equima.mant.st_obras,
					queryMode:'local',
					displayField: 'desc_obra',
    				valueField: 'cod_obra',
					id:'cmb_win_frm_obra',
					listeners:{
						select:function(combo, record, eOpts){
							equima.mant.st_frente.reload({
								params:{
									anio:record.data.anio_eje,
									obra:record.data.cod_obra
								}
							});
						}
					},
					name:'cod_obra'
				},{
					fieldLabel:'Frente',xtype:'combobox',
					queryMode:'local',
					store:equima.mant.st_frente,
					displayField: 'desc_frente',
    				valueField: 'id_frente',
					id:'cmb_win_frm_frente',
					name:'id_frente'
				},{
					fieldLabel:'Orden',
					name:'nord_mant'
				},{
					fieldLabel:'Tipo. Comp.:',xtype:'combobox',
					store:equima.mant.st_tip_doc,
					displayField: 'desc_tipdoc',
    				valueField: 'id_tipdoc',
    				name:'id_tipdoc'
				},{
					fieldLabel:'Nro.:',
					name:'doc_mant'
				},{
					fieldLabel:'Fecha:',xtype:'datefield',
					name:'fdoc_mant',format:'d/m/Y'
				},{
					fieldLabel:'Monto:',xtype:'numberfield',
					name:'mdoc_mant'
				},{
					fieldLabel:'Obs.:',xtype:'textarea',
					name:'obs_mant'
				}]
			}],
			buttons:[{
				text:'Guardar',handler:function(){
					grid = Ext.getCmp('equima_grid_main');
					rec = grid.getSelection()[0].data;
									
					frm = Ext.getCmp('frm_mant_maqequi');
					frm.submit({
						params:{
							opt:opt,
							cod_maqequi:rec.cod_maqequi,

						},
						success:function(form, action){
							Ext.Msg.alert('Sistema',action.result.msg);
							equima.mant.st_grid.reload();
							win = Ext.getCmp('win_maqequi_mant_new');
							win.close();
						},
						failure:function(form, action){
							Ext.Msg.alert('Error',action.result.msg);
							win = Ext.getCmp('win_maqequi_mant_new');
							win.close();
						}
					})
				}
			},{
				text:'Cancelar',handler:function(){
					win = Ext.getCmp('win_maqequi_mant_new');
					win.close();
				}
			}]
		}).show();
	}

	equima.mant.grid = Ext.create('Ext.grid.Panel',{
		region:'center',forceFit:true,
		id:'grid_equima_mant',
		columns:[
			{text:'Obra',dataIndex:'desc_obra'},
			{text:'Frente',dataIndex:'desc_frente'},
			{text:'N° Orden',dataIndex:'nord_mant'},
			{text:'Tipo',dataIndex:'desc_tipdoc'},
			{text:'N° Compro.',dataIndex:'doc_mant'},
			{text:'Fecha Compro.',dataIndex:'fdoc_mant'},
			{text:'Costo',dataIndex:'mdoc_mant'}
		],
		store:equima.mant.st_grid,
		tbar:[{
				xtype:'label',text:'Año:'
			},{
				xtype:'combobox',width:80,
				queryMode:'local',
				displayField: 'anio_eje',
    			valueField: 'anio_eje',
				store:equima.st_anios,
				id:'cmb_grid_frm_anio',
				listeners:{
					change:function(ths, newValue, oldValue, eOpts){
						rec = Ext.getCmp('equima_grid_main').getSelection();
						if(rec[0]){
							equima.mant.st_grid.reload({
								params:{
									anio_eje:newValue,
									cod_maqequi:rec[0].data.cod_maqequi
								}
							});	
						}
					}
				}
			},'-',{
				text:'Agregar',handler:function(){
					equima.mant.window_grid('n');
				}
			},{
				text:'Editar',handler:function(){
					equima.mant.window_grid('e');
				}
			},{
				text:'Anular'
			},'->',{
				xtype:'label',text:'Buscar:'
			},{
				xtype:'combobox'
			},{
				xtype:'textfield'
			},{
				text:'Buscar'
		}],
		listeners:{
			select:function(ths, record, index, eOpts ){

			}
		}

	});

	equima.mant.cmb_anio = Ext.getCmp('cmb_grid_frm_anio');
	equima.mant.cmb_anio.setValue('<?php echo $anio_eje;?>');
</script>