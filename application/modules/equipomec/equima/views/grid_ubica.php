<script type="text/javascript">
	equima.window_grid_ubica = function(opt){
		Ext.create('Ext.window.Window',{
			title:'Nueva Ubicación',modal:true,
			height:300,width:400,
			buttons:[{
				text:'Guardar'
			},{
				text:'Cancelar'
			}]
		}).show();
	};

	equima.grid_ubica = Ext.create('Ext.grid.Panel',{
		region:'center',
		columns:[
			{text:'Fecha'},
			{text:'Obra/Frente'},
			{text:'Nro Guia'},
			{text:'Fecha Guia'},
			{text:'Responsable'}
		],
		tbar:[{
				xtype:'label',text:'Año:'
			},{
				xtype:'combobox'
			},'-',{
				text:'Agregar',handler:function(){
					equima.window_grid_ubica('n')
				}
			},{
				text:'Editar',handler:function(){
					equima.window_grid_ubica('e')
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