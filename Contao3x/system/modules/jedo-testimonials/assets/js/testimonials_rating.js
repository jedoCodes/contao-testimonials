var TRate = new Class({
	Implements: Options,
    options: {
	    stars: 5, // the number of displayed stars
	    rating:0.0 // this rating is showen onload
	},

    initialize: function(container,currStars,inputField,options){
        this.setOptions(options);
        
        this.container = container;
        this.currStars = currStars;
        this.inputField = inputField;
        
        this.currStars.setStyle('width',this.getCurrstarsWidthByRating());
        
        this.inputField.set('value',this.options.rating);
        
    	this.addBehavior();
    },

    addBehavior: function(){
		this.container.addEvents({
			
			'mousemove': function(event){
				this.currStars.setStyles({'width': this.getCurrstarsWidthByCoord(event.client.x)});
			}.bind(this),
			
			'click': function(event){ 
				var rating = this.getRatingByWidth(this.getCurrstarsWidthByCoord(event.client.x));
				this.options.rating = rating;
				this.inputField.set('value',rating);
			}.bind(this),
			
			'mouseleave': function(){
				this.currStars.setStyle('width',this.getCurrstarsWidthByRating()); 
			}.bind(this),
		});
    },

    getRatingByWidth: function(width){
    	return Math.round(((width)/this.container.getStyle('width').toInt()) * this.options.stars * 100)/100;
    },

    getCurrstarsWidthByCoord: function(xCoord){
		return xCoord - this.container.getPosition().x;
    },
    
    getCurrstarsWidthByRating: function(){
    	return this.container.getStyle('width').toInt() / this.options.stars * this.options.rating;
    }
	
});