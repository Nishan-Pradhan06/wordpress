/**
 * Icegram Message Type - Action_Bar
 **/
function Icegram_Message_Type_Action_Bar( data ) {
    Icegram_Message_Type.apply(this, arguments);
}

Icegram_Message_Type_Action_Bar.prototype = Object.create(Icegram_Message_Type.prototype);
Icegram_Message_Type_Action_Bar.prototype.constructor = Icegram_Message_Type_Action_Bar;

Icegram_Message_Type_Action_Bar.prototype.get_template_default = function () {
    return  '<div class="icegram action_bar_{{=id}}" >'+
                '<div class="ig_action_bar ig_container ig_{{=theme}} ig_no_hide" id="icegram_message_{{=id}}">'+
                    '<div class="ig_content ig_clear_fix">'+
                        '<div class="ig_close" id="ig_close_{{=id}}"><span></span></div>'+
                        '<div class="ig_form_container layout_left"></div>'+
                        '<div class="ig_data ig_clear_fix">'+
                            '<div class="ig_headline">{{=headline}}</div>'+
                            '<div class="ig_message">{{=message}}</div>'+
                        '</div>'+
                        '<div class="ig_button">{{=label}}</div>'+
                    '<div class="ig_form_container layout_right layout_bottom"></div>'+
                    '</div>'+
                '</div>'+
            '</div>';
};

Icegram_Message_Type_Action_Bar.prototype.post_render = function ( ) {
    //Calling parent post_render function
    Icegram_Message_Type.prototype.post_render.apply(this, arguments);
    
    if(this.data.use_theme_defaults == undefined || this.data.use_theme_defaults != 'yes'){
        if (this.data.bg_color != undefined && this.data.bg_color != '') {
            this.el.find('.ig_close').css('background-color', this.data.bg_color);
        }
    }
     if(this.data.position !== '21' && jQuery('#ig_body_pushdown').length == 0){
        jQuery('body').prepend('<div id="ig_body_pushdown"></div>');
    }
};

Icegram_Message_Type_Action_Bar.prototype.set_position = function ( ) {
    switch(this.data.position) {
        case "21":
            this.el.addClass('ig_bottom');
            break;
        case "01":
        default:
            this.el.addClass('ig_top');
            break;
    }

};

Icegram_Message_Type_Action_Bar.prototype.add_powered_by = function ( pb ) {
    this.el.addClass('ig_has_pwby') 
        .find('.ig_content').before('<div class="ig_powered_by" ><a href="'+pb.link+'" target="_blank"><img src="'+pb.logo+'" title="'+pb.text+'"/></a></div>');
};

Icegram_Message_Type_Action_Bar.prototype.on_click = function ( e ) {
    e.data = e.data || { self: this };
    // Clicked on close button
    if (jQuery(e.target).filter('.ig_show .ig_close, .ig_show .ig_close span').length) {
        e.data.self.hide();
        return;
    }else if(jQuery(e.target).filter('.ig_hide .ig_close, .ig_hide .ig_close span').length){
        e.data.self.show();
        return;
    }
    // Now let the parent handle the rest...
    Icegram_Message_Type.prototype.on_click.apply(this, arguments);
};

Icegram_Message_Type_Action_Bar.prototype.post_show = function ( ) {
    //TODO:: add one option for making header sticky
    if(this.data.position !== '21'){
        var abH = this.el.outerHeight() || 0;
        jQuery('#ig_body_pushdown').css('display', 'block').animate({'height': abH }, 500);
        jQuery('*', document.body)
                    .not('.ig_action_bar, .ig_popup, .ig_messenger, .ig_inline, .ig_overlay, .ig_sidebar, .ig_tab, .ig_interstitial ,#ig_body_pushdown ')
                    .each(function(){
                        var t = window.getComputedStyle(this, null);
                        if((t.position === 'fixed' || (t.position === 'absolute' && (this.parentNode.nodeName === 'BODY' || this.nodeName === 'HEADER') ) )
                            && !isNaN(parseInt(t.top, 10)) && this.getBoundingClientRect().top <= abH){
                            jQuery(this).data('ig_fx_top', t.top).animate({'top': parseInt(t.top, 10) + abH + 'px'}, 300);
                        }
                    });
    }
}

Icegram_Message_Type_Action_Bar.prototype.pre_hide = function ( ) {
    if(this.data.position !== '21'){
        jQuery('#ig_body_pushdown').animate({'height':  0}, 300).css('display', 'none');
        jQuery('*', document.body)
                .not('.ig_action_bar, .ig_popup, .ig_messenger, .ig_inline, .ig_overlay, .ig_sidebar, .ig_tab, .ig_interstitial ,#ig_body_pushdown ')
                .each(function(){
                    if(typeof jQuery(this).data('ig_fx_top') !== 'undefined'){
                        jQuery(this).animate({'top': jQuery(this).data('ig_fx_top')}, 200);
                    } 
                });
    }
}