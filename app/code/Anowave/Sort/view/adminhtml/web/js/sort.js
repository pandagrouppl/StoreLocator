define(['jquery','jquery/ui'], function($)
{
	var SortProducts = (function()
	{
		return {
			bind: function()
			{
				var table = $('[id=catalog_category_products_table]'), tbody = $('[id=catalog_category_products_table] tbody'), limit = $('select[id=catalog_category_products_page-limit]').val();

				/* Augment rows */
				if (tbody.length && 10000 == parseInt(limit))
				{
					try 
					{
						var getGrid = function()
						{
							return catalog_category_productsJsObject;
						};
						
						var sortable = tbody.sortable(
						{
							forcePlaceholderSizeType: false,
							handle: 'td',
							axis: 'y',
							helper: function(event, ui) 
							{
								ui.children().each(function() 
								{
									$(this).width($(this).width()).addClass('highlight');
								});
								
								return ui;
							},
							sort: (function(grid, pixels, jump)
							{
								return function( event, ui ) 
								{
									var top = grid.offset().top, bottom = top + grid.height();
									
									if(ui.offset.top >= top &&  ui.offset.top <= top + pixels )
									{
										grid.scrollTop(grid.scrollTop() - jump)
									}
									
									if(ui.offset.top + ui.helper.height() <= bottom &&  ui.offset.top + ui.helper.height() >= bottom - pixels )
									{
										grid.scrollTop(grid.scrollTop() + jump)
									}
								}
							})(table.parent(), 50, 5),
							stop: function(event, ui)
							{
								ui.item.children().removeClass('highlight');
								
								for (var row = 0; row < getGrid().rows.length; row++) 
								{
					                if(row % 2==0)
					                {
					                    Element.addClassName(this.rows[row], 'even');
					                }
					                else 
					                {
					                	Element.removeClassName(this.rows[row], 'even');
					                }
								}

								var products = {};
								
								sortable.children().each(function(index)
								{
									var element = $(this).find('input.input-text').get(0);
									
									element.value 					= 1 + index;
									element.checkboxElement.checked = true;
									
									/* Update JS Object */
									getGrid().setCheckboxChecked(element.checkboxElement, true);

									var id = $(this).find(':checkbox:checked').val(), key = index + 1;

									products[id.toString()] = key.toString();
								});

								(function(item, products)
								{
									/* Serialize */
									$(':hidden[name=category_products]').val(JSON.stringify(products));
									
									getGrid().reloadParams = 
									{
										'selected_products[]' : products
									};
									
									var check = function()
									{
										item.find(':checkbox:not(:checked)').trigger('click');
									};

									setTimeout(check,1);

								})(ui.item, products);

								return true;
							}
						});

						tbody.find('>tr').each(function()
						{
							$(this).find('td:first').addClass('sort-drag-icon').find('label').css('cursor','move').attr('title','Drag vertically to change order');
						});
					}
					catch (error)
					{
						alert(error);
					}
				}
			}
		}
	})();
	
	window.SortProducts = SortProducts;

	return SortProducts;
});