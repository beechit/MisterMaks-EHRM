define(
	['jquery', 'emberjs', 'data-tables-twitterbootstrap'],
	function($, Ember) {
		return Ember.View.extend({
			didInsertElement: function() {
				var oTable = $('.dataTable');
				oTable = $(oTable).dataTable( {
						"sDom": "RC<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
						"sPaginationType": "bootstrap",
						"oLanguage": {
							"sLengthMenu": "_MENU_ records per page"
						},
						"aoColumns": [
							null,
							null,
							null,
							null,
							{"bSortable": false}
						]
					}
				).show();
				this.addFilter(oTable);
				this.$().html( $(oTable).show().parent());
			},
			addFilter: function(oTable) {
				$("tfoot input").each( function (i) {
					this.initVal = this.value;
				} );
				$("tfoot input").focus( function () {
					if ( this.className == "search_init" )
					{
						this.className = "";
						this.value = "";
					}
				} );
				$("tfoot input").blur( function (i) {
					if ( this.value == "" )
					{
						this.className = "search_init";
						this.value = this.initVal;
					}
				} );
				$("tfoot input, tfoot select").keyup( function () {
						/* Filter on the column (the index) of this element */
					oTable.fnFilter( this.value, oTable.oApi._fnVisibleToColumnIndex(
						oTable.fnSettings(), $("tfoot input, tfoot select").index(this) ) );
				} );
				$("tfoot input, tfoot select").change(function () {
					oTable.fnFilter( this.value, $("tfoot input, tfoot select").index(this) );
				} );
			}
		});
	}
);
