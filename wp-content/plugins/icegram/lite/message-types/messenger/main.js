/**
 * Icegram Message Type - Messenger
 **/
function Icegram_Message_Type_Messenger( data ) {
    Icegram_Message_Type.apply(this, arguments);
}
Icegram_Message_Type_Messenger.prototype = Object.create(Icegram_Message_Type.prototype);
Icegram_Message_Type_Messenger.prototype.constructor = Icegram_Message_Type_Messenger;

Icegram_Message_Type_Messenger.prototype.get_template_default = function () {
    return '<div class="icegram ig_messenger ig_{{=theme}}  ig_container ig_cta" id="icegram_message_{{=id}}">' +
            '<div class="ig_content">' +
                '<div class="ig_close" id="ig_close_{{=id}}"></div>' +
                '<div class="ig_data">' +
                    '<div class="ig_headline">{{=headline}}</div>' +
                    '<div class="ig_body">' +
                        '<img class="ig_icon" src="{{=icon}}"/>' +
                        '<div class="ig_message">{{=message}}</div>' +
                    '</div>' +
                    '<div class="ig_footer"></div>' +
                '</div>' +
            '</div>' +
        '</div>';
};

Icegram_Message_Type_Messenger.prototype.set_position = function ( ) {
    switch(this.data.position) {
        case "20":
            this.el.addClass('ig_left ig_bottom');
            break;
        case "22":
        default: 
            this.el.addClass('ig_right ig_bottom');
            break;
    }

};

Icegram_Message_Type_Messenger.prototype.add_powered_by = function ( pb ) {
    // this.el.addClass('ig_has_pwby');
    this.el.addClass('ig_has_pwby')
           .find('.ig_content')
           .after('<div class="ig_powered_by"><a href="'+pb.link+'" target="_blank">'+pb.text+'</a></div>');      
};
