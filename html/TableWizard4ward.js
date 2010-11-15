/**
 * TableWizard4ward
 * @copyright  4ward.media 2010
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
 * @package    table4ward
 * @license    LGPL 
 */
var TableWizard4ward = new Class({
	
	/**
	 * Init a table wizard and create all div-container for the textareas
	 */
    initialize: function(table){
    	this.table = table;
    	
    	// set all textareas hidden and display divs with formatting
    	this.table.getElements("td.tcontainer").each(function(tcontainer){
    		var textarea = tcontainer.getElement('textarea');
    		textarea.setStyle('display','none');
    		var divEl = new Element('div',{
    			'html': this.nl2br(textarea.get('value')),
    			'class': 'tdivcontainer',
    			'styles': {
    				'height': ((Browser.Engine.presto) ? '65px' : textarea.getStyle('height')),
    				'width': textarea.getStyle('width'),
    				'border': '1px solid #CCCCCC',
    				'overflow': 'auto',
    				'padding': '2px',
    				'margin': '1px 0px'
    			}
    		}).inject(tcontainer);
    	
    		
    	}.bind(this));
    	// add lightbox-like overlay
    	if($('table4wardOverlay') == null) {
    		this.overlay = new Element('div',{
    			'id':'table4wardOverlay',
    			'styles': {
    				'position':'absolute',
    				'left':'0px',
    				'top':'0px',
    				'height':document.body.getScrollSize().y,
    				'width':document.body.getScrollSize().x,
    				'background':'#000000',
    				'opacity':'0.7',
    				'display':'none'
    			},
	    		'events': {
    				// save and close on click outside the tinyMCE 
	    			'click': this.tinyMCEsave.bind(this)
	    		}
    		}).inject(document.body,'bottom');
    	}

    	// add hidden field with the RTE (only if theres not already one)
    	if($('mb_tableWizard4ward') == null) {
	    	this.mbDiv = new Element('div',{
	    		'id': 'mb_tableWizard4ward',
	    		'styles': {
	    			'position':'absolute',
	    			'left':'-3000px'
	    		}
	    	}).inject(document.body,'bottom');
	    	this.mbTextarea = new Element('textarea',{'id':'table4wardRTE'}).inject(this.mbDiv);
    	} else {
    		this.mbDiv = $('mb_tableWizard4ward');
    	}
    	
    	// init tinyMCE
    	TableWizard4ward.tinyMCEInit('table4wardRTE');
    	
    	
    	// attach click-event-handler to table
    	this.table.addEvent('click', this.tableClickHandler.bind(this));
    	this.table.addEvent('dblclick', this.tableDoubleclickHandler.bind(this));
	},
	
	/**
	 * Handle the single clicks within the table
	 * and call showTextarea on a div-click
	 */
	tableClickHandler: function(e){
		var target = $(e.target);
		if(target.get('tag') == 'div' && target.hasClass('tdivcontainer')) this.showTextarea(target);
		else if(target.getParent('div.tdivcontainer')) this.showTextarea(target.getParent('div.tdivcontainer'));
	},
	
	/**
	 * Hande the doubleclicks within the table
	 * and open the Mediabox
	 */
	tableDoubleclickHandler: function(e){
		var target = $(e.target);
		if(target.get('tag') == 'textarea'){ // dblclick also fires single-click, so the div sould be replaced with an textarea
			tinyMCE.activeEditor.setContent(this.nl2br(target.get('value')));
			this.edittingField = target;
			
			// register this to tinyMCE for the save/cancle callbacks 
			tinyMCE.activeEditor.tableWizard4ward = this;
			
			tinyMCE.activeEditor.focus();
			
			// set RTE position
			// @todo check if rte overlaps the visible area
			this.mbDiv.setStyle('left',e.event.clientX-289+document.body.getScroll().x);
			this.mbDiv.setStyle('top',e.event.clientY-155+document.body.getScroll().y);
			
			$('table4wardOverlay').setStyle('display','block');
		}
		
	},
	
	/**
	 * Show the textarea
	 */
	showTextarea: function(divTarget){
		divTarget.setStyle('display','none');
		var textareaTarget = divTarget.getParent('td').getElement('textarea');
		textareaTarget.setStyle('display','block');
		textareaTarget.addEvent('blur',this.hideTextareaHandler.bind(this));
		textareaTarget.focus();
	},

	/**
	 * Handle textarea-blur events and show the div 
	 */
	hideTextareaHandler: function(e){
		this.showDiv($(e.target));
	},
	
	/**
	 * Show the div and update its content with the textarea-value
	 */
	showDiv: function(textareaTarget){
		textareaTarget.setStyle('display','none');
		var divTarget = textareaTarget.getParent('td').getElement('div.tdivcontainer');
		divTarget.set('html',this.nl2br(textareaTarget.get('value')));
		divTarget.setStyle('display','block');
	},
	
	nl2br: function(str){
		return str.replace(/\n/g,"<br />");
	},
	
	br2nl: function(str){
		return str.replace(/<br \/>/g,"\n");
	},
	
	
	tinyMCEsave: function(){
		$('mb_tableWizard4ward').setStyle('left','-3000px');
		this.edittingField.set('value',this.br2nl(tinyMCE.activeEditor.getContent().replace(/\n/g,"")));
		this.edittingField.getParent('td').getElement('div.tdivcontainer').set('html',tinyMCE.activeEditor.getContent());
		this.overlay.setStyle('display','none');
		return false;
	},
	
	tinyMCEcancle: function(){
		$('mb_tableWizard4ward').setStyle('left','-3000px');
		this.overlay.setStyle('display','none');
		return false;
	}
});




/**
 * Table wizard
 * @param object
 * @param string
 * @param string
 */
TableWizard4ward.tableWizard = function(el, command, id)
	{
		var table = $(id);
		var tbody = table.getFirst();
		var rows = tbody.getChildren();
		var parentTd = $(el).getParent();
		var parentTr = parentTd.getParent();
		var cols = parentTr.getChildren();
		var index = 0;

		for (var i=0; i<cols.length; i++)
		{
			if (cols[i] == parentTd)
			{
				break;
			}

			index++;
		}
		
		if(Backend != null) Backend.getScrollOffset();

		switch (command)
		{
		case 'rnew':
			var tr = new Element('tr');
			var childs = parentTr.getChildren();

			for (var i=0; i<childs.length; i++)
			{
				var next = childs[i].clone(true).injectInside(tr);
				if(next.hasClass('tcontainer')){
					next.getElement('textarea').set('value','');
					next.getElement('div.tdivcontainer').empty();
				}
			}

			tr.injectAfter(parentTr);
			break;
			
		case 'rcopy':
			var tr = new Element('tr');
			var childs = parentTr.getChildren();
			
			for (var i=0; i<childs.length; i++)
			{
				var next = childs[i].clone(true).injectInside(tr);
				next.getFirst().value = childs[i].getFirst().value;
			}
			
			tr.injectAfter(parentTr);
			break;

		case 'rup':
			parentTr.getPrevious().getPrevious() ? parentTr.injectBefore(parentTr.getPrevious()) : parentTr.injectInside(tbody);
			break;

		case 'rdown':
			parentTr.getNext() ? parentTr.injectAfter(parentTr.getNext()) : parentTr.injectBefore(tbody.getFirst().getNext());
			break;

		case 'rdelete':
			(rows.length > 2) ? parentTr.destroy() : null;
			break;
		case 'cnew':
			for (var i=0; i<rows.length; i++)
			{
				var current = rows[i].getChildren()[index];
				var next = current.clone(true).injectAfter(current);
				if(next.hasClass('tcontainer')){
					next.getElement('textarea').set('value','');
					next.getElement('div.tdivcontainer').empty();
				}
			}
			break;
		case 'ccopy':
			for (var i=0; i<rows.length; i++)
			{
				var current = rows[i].getChildren()[index];
				var next = current.clone(true).injectAfter(current);
				next.getFirst().value = current.getFirst().value;
			}
			break;

		case 'cmovel':
			if (index > 0)
			{
				for (var i=0; i<rows.length; i++)
				{
					var current = rows[i].getChildren()[index];
					current.injectBefore(current.getPrevious());
				}
			}
			else
			{
				for (var i=0; i<rows.length; i++)
				{
					var current = rows[i].getChildren()[index];
					current.injectBefore(rows[i].getLast());
				}
			}
			break;

		case 'cmover':
			if (index < (cols.length - 2))
			{
				for (var i=0; i<rows.length; i++)
				{
					var current = rows[i].getChildren()[index];
					current.injectAfter(current.getNext());
				}
			}
			else
			{
				for (var i=0; i<rows.length; i++)
				{
					var current = rows[i].getChildren()[index];
					current.injectBefore(rows[i].getFirst());
				}
			}
			break;

		case 'cdelete':
			if (cols.length > 2)
			{
				for (var i=0; i<rows.length; i++)
				{
					rows[i].getChildren()[index].destroy();
				}
			}
			break;
	}

	rows = tbody.getChildren();

	for (var i=0; i<rows.length; i++)
	{
		var childs = rows[i].getChildren();

		for (var j=0; j<childs.length; j++)
		{
			var first = childs[j].getFirst();

			if (first && first.type == 'textarea')
			{
				first.name = first.name.replace(/\[[0-9]+\][[0-9]+\]/ig, '[' + (i-1) + '][' + j + ']')
			}
		}
	}

	TableWizard4ward.tableWizardResize();
};


/**
* Resize table wizard fields on focus
* @param float
*/
TableWizard4ward.tableWizardResize = function(factor)
{
	var size = Cookie.read('BE_CELL_SIZE');

	if (!$defined(size) && !$defined(factor))
	{
		return;
	}

	if ($defined(factor))
	{
		var size = '';

		$$('.tl_tablewizard textarea').each(function(el)
		{
			el.setStyle('width', (el.getStyle('width').toInt() * factor).round().limit(142, 284));
			el.setStyle('height', (el.getStyle('height').toInt() * factor).round().limit(66, 132));

			if (size == '')
			{
				size = el.getStyle('width') + '|' + el.getStyle('height');
			}
		});

		Cookie.write('BE_CELL_SIZE', size);
	}
	else if ($defined(size))
	{
		var chunks = size.split('|');

		$$('.tl_tablewizard textarea').each(function(el)
		{
			el.setStyle('width', chunks[0]);
			el.setStyle('height', chunks[1]);
		});
	}
};
	


/**
 * Init for each table with class tl_tablewizard
 */
window.addEvent('domready',function(){
	$$("table.tl_tablewizard").each(function(elem){
		var tblwiz4ward = new TableWizard4ward(elem);
	});
});