<script type="text/javascript">
	equima.window_grid_valoriza = function(opt){
		Ext.create('Ext.window.Window',{
			title:'Nueva Valorización',modal:true,
			height:300,width:400,
			buttons:[{
				text:'Guardar'
			},{
				text:'Cancelar'
			}]
		}).show();
	};

	equima.grid_valoriza = Ext.create('Ext.grid.Panel',{
		region:'center',
		columns:[
			{text:'Fecha'},
			{text:'Obra/Frente'},
			{text:'Nro Valorizacion'},
			{text:'Fecha'},
			{text:'Responsable'},
			{text:'Monto'}
		],
		tbar:[{
				xtype:'label',text:'Año:'
			},{
				xtype:'combobox'
			},'-',{
				text:'Agregar',handler:function(){
					equima.window_grid_valoriza('n');
				}
			},{
				text:'Editar',handler:function(){
					equima.window_grid_valoriza('e');
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
	});
</script>