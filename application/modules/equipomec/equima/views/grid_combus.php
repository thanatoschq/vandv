<script type="text/javascript">
	equima.window_grid_combus = function(opt){
		Ext.create('Ext.window.Window',{
			title:'Nuevo registro de Combustible',modal:true,
			height:300,width:400,
			buttons:[{
				text:'Guardar'
			},{
				text:'Cancelar'
			}]
		}).show();
	}

	equima.grid_combus = Ext.create('Ext.grid.Panel',{
		region:'center',
		columns:[
			{text:'Obra/Frente'},
			{text:'Nro. Vale'},
			{text:'Fecha'},
			{text:'Vale'},
			{text:'Tipo Combustible'},
			{text:'Orden'},
			{text:'Nro Orden'}
		],
		tbar:[{
				xtype:'label',text:'Año:'
			},{
				xtype:'combobox'
			},'-',{
				text:'Agregar',handler:function(){
					equima.window_grid_combus('n');
				}
			},{
				text:'Editar',handler:function(){
					equima.window_grid_combus('e');
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