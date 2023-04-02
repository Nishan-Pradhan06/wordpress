/**
 * Icegram Message Type - Toast
 **/
function Icegram_Message_Type_Toast( data ) {
    var width ,sticky ,duration;
    this.width = 300;
    this.sticky  = false;
    this.duration    = 10000;
    Icegram_Message_Type.apply(this, arguments);
}
Icegram_Message_Type_Toast.prototype = Object.create(Icegram_Message_Type.prototype);
Icegram_Message_Type_Toast.prototype.constructor = Icegram_Message_Type_Toast;

Icegram_Message_Type_Toast.prototype.get_template_default = function () {
    return  '<li class="icegram ig_toast ig_container ig_{{=theme}} ig_cta" data="{{=id}}" id="icegram_message_{{=id}}">'+
                '<div class="ig_wrapper">'+
                    '<div class="ig_content">'+
                        '<div class="ig_base"></div>'+
                        '<div class="ig_line"></div>'+
                        '<img class="ig_icon" src="{{=icon}}"/>'+
                        '<div class="ig_headline">{{=headline}}</div>'+
                        '<div class="ig_message">{{=message}}</div>'+
                    '</div>'+
                '</div>'+
            '</li>';
};
Icegram_Message_Type_Toast.prototype.pre_render = function ( ) {
    if( this.data.position == "10" || this.data.position == "12" ) {
        this.data.position = '20';
    }
    if (!(jQuery('ul#' + this.data.position).length)) {
        var ul = jQuery('<ul id="' + this.data.position + '"></ul>').addClass('ig_toast_block').appendTo(this.root_container).hide();
        ul.width(this.width);
        if (this.data.position == "00") {
            ul.css({top: '0', left: '0'}).addClass('ig_left').addClass('ig_top');
        } else if (this.data.position == "01") {     
            ul.css({top: '0', left: '50%', margin: '5px 0 0 -' + (this.width / 2) + 'px'}).addClass('ig_center').addClass('ig_top');                                 
        } else if (this.data.position == "02") {                                      
            ul.css({top: '0', right: '0'}).addClass('ig_right').addClass('ig_top');
        } else if (this.data.position == "20") {                                      
            ul.css({bottom: '0', left: '0'}).addClass('ig_left').addClass('ig_bottom');
        } else if (this.data.position == "21") {                                      
            ul.css({bottom: '0', left: '50%', margin: '5px 0 0 -' + (this.width / 2) + 'px'}).addClass('ig_center').addClass('ig_bottom');
        } else if (this.data.position == "22") {                                      
            ul.css({bottom: '0', right: '0'}).addClass('ig_right').addClass('ig_bottom');
        } else if (this.data.position == "11") {                                      
            ul.css({top: '50%', left: '50%', margin: '-'+(this.width / 2) +'px 0 0 -' + (this.width / 2) + 'px'}).addClass('ig_center').addClass('ig_top');
        }
    }else {
        var ul = jQuery('ul#' + this.data.position);
    }
    this.root_container = ul;
}

Icegram_Message_Type_Toast.prototype.pre_show = function (  ) {
    !this.root_container.hasClass('active') && this.root_container.addClass('active').show();
}

Icegram_Message_Type_Toast.prototype.post_show = function ( ) {
    var self = this;
    !this.sticky && this.duration > 0 && (setTimeout(function() {
            self.hide();
            self.root_container.children().length || self.root_container.removeClass('active').hide();
    }, this.duration));

};
