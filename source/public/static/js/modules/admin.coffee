#-----------------------------------------------------------------------------------------------
 # @Module: DataTable
 # @Description: Modulo DataTable
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "dataTable", ((Sb) ->
	init: (oParams) ->
		dataUrl= oParams.url
		opts=
			"bJQueryUI": false
			"bAutoWidth": false
			"sPaginationType": "full_numbers"
			"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>'
			"oLanguage":
				"sSearch": "Busqueda"
				"sLengthMenu": "<span>Mostrar registros por pagina</span> _MENU_"
				"sZeroRecords": "No hay resultados"
				"sInfo": "Mostrar _START_ a _END_ de _TOTAL_ registros"
				"sInfoEmpty": "Mostrar 0 a 0 de 0 records"
				"sInfoFiltered": "( _MAX_ registros en total)"
				"oPaginate":
					"sLast": "Última"
					"sFirst": "Primera"
					"sNext": ">"
					"sPrevious": "<"
			"bServerSide": true
			"sAjaxSource": dataUrl
		json= $.extend opts,yOSON.datable[oParams.table]
		window.instDataTable= $('#datatable').dataTable json
), ["libs/plugins/jqDataTable.js","data/datatable.js"]
#-----------------------------------------------------------------------------------------------
 # @Module: ActionDel
 # @Description: Modulo para eliminar registros
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "actionDel", ((Sb) ->
	st=
		del: ".ico-delete"
		table: "#datatable"
	dom= {}
	catchDom= ()->
		dom.table= $(st.table)
	bindEvents= (qus)->
		$this= null
		url= ""
		id= ""
		table= ""
		answer= if typeof qus isnt "undefined" then qus else "¿Esta seguro que desea eliminar el item seleccionado?"
		dom.table.on "click",st.del,(e)->
			e.preventDefault()
			$this= $(this)
			if confirm(answer)
				url= $this.attr "href"
				id= $this.attr "data-id"
				table= $this.attr("data-table");
				parent= $this.parents "tr"
				hash= utils.loader parent,true,1
				$.ajax
					"url": url
					"data":
						"id": id
						"table": table
					"dataType": "JSON"
					"success": (json)->
						utils.loader $("#"+hash),false,1
						if json.msj is "ok"
							instDataTable.fnDraw()
	init: (oParams) ->
		catchDom()
		bindEvents(oParams.title)
), ["libs/plugins/jqDataTable.js"]
#-----------------------------------------------------------------------------------------------
 # @Module: Validate Form
 # @Description: Validacion de formularios
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "filtersDataTable", ((Sb) ->
	st=
		filters: ".coltbl-filter"
		btnFilter: ".btnTblFilter"
	dom= {}
	catchDom= ()->
		dom.filters= $(st.filters)
		dom.btnFilter= $(st.btnFilter)
	bindEvents= ()->
		$this= null
		dataInpt= {}
		dom.filters.each ()->
			$this= $(this)
			$this.on "keyup",()->
				if typeof dataInpt[this.id] is "undefined" or dataInpt[this.id] isnt this.value
					dataInpt[this.id]= this.value
					window.instDataTable.fnFilter this.value,$(this).attr("data-column")
	init: (oParams) ->
		catchDom()
		bindEvents()
), ["libs/plugins/jqDataTable.js"]
#-----------------------------------------------------------------------------------------------
 # @Module: Datepicker
 # @Description: Implementar datepicker
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "datepicker", ((Sb) ->
	st=
		dateTimepicker: ".datetimepicker"
		birthdaypicker: ".birthdaypicker"
	dom= {}
	catchDom= ()->
		dom.birthdaypicker= $(st.birthdaypicker)
		dom.dateTimepicker= $(st.stDateTimepicker)
	bindEvents= ()->
		$(".datetimepicker").datetimepicker
			dateFormat: "yy-mm-dd"
			timeFormat: "HH:mm"
			minDate: 0
			timeText: "Tiempo"
			hourText: "Hora"
			minuteText: "Minutos"
			currentText: "Hoy"
			closeText: "Ok"
		$(".birthdaypicker").datepicker
			yearRange: "-80:c"
			maxDate: 0
			changeMonth: true
			changeYear: true
			dateFormat: "dd/mm/yy"
	init: (oParams) ->
		catchDom()
		bindEvents()
), ["libs/plugins/jqUI.js","libs/plugins/jqDatepicker.js","libs/plugins/jqTimepicker.js"]
#-----------------------------------------------------------------------------------------------
 # @Module: Validate Form
 # @Description: Validacion de formularios
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "validation", ((Sb) ->
	init: (oParams) ->
		forms= oParams.form.split(",")
		$.each forms,(index,value)->
			settings= {}
			value= $.trim value
			for prop of yOSON.require[value]
				settings[prop]= yOSON.require[value][prop]
			$(value).validate settings
), ["data/require.js","libs/plugins/jqValidate.js"]
#-----------------------------------------------------------------------------------------------
 # @Module: countChar
 # @Description: modulo para validar cantidad de caracteres
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "countChar", ((Sb) ->
	st=
		count: ".countChar"
		inpt: "#message"
	dom= {}
	catchDom= ()->
		dom.inpt= $(st.inpt)
		dom.count= $(st.count)
	bindEvents= ()->
		dom.inpt.on "keyup",()->
			console.log this.val
	init: (oParams) ->
		catchDom()
		bindEvents()
)
#-----------------------------------------------------------------------------------------------
 # @Module: Numeric
 # @Description: Modulo para buscar mentor
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "numeric", ((Sb) ->
	st=
		inpt: ".numeric"
		dec: ".decimal"
		edad: ".inptEdad"
	dom= {}
	catchDom= ()->
		dom.inpt= $(st.inpt)
		dom.dec= $(st.dec)
		dom.edad= $(st.edad)
	bindEvents= ()->
		dom.inpt.numeric
			decimal: false
			negative: false
		dom.dec.numeric
			negative: false
		if dom.edad.length > 0
			utils.vLength dom.edad,2
	init: (oParams) ->
		catchDom()
		bindEvents()
), ["libs/plugins/jqNumeric.js"]