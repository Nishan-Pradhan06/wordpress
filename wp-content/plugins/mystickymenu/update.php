<?php if (!defined('ABSPATH')) { exit; } ?>
<style>
    body {
        background: #ffffff !important;
    }
</style>
<div class="myStickymenu-updates-form">
    <div class="updates-form-form-left">
        <div class="updates-form-form-left-text">premio</div> 
        <img src="<?php echo MYSTICKYMENU_URL ?>/images/wcupdate_email.svg" style="width: 230px;margin: 60px 0px 20px 0px;" />
		<p>Grow your WordPress websites with our plugins</p>
    </div>
    <div class="updates-form-form-right">
        <div class="update-title">Be first</div>
        <p>Be among the first to know about our latest features & what we're working on. Plus insider offers & flash sales</p>
        <div class="updates-form">
            <div class="update-form-input">
                <div class="mail-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="2" y="4" width="20" height="16">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M22 6C22 4.9 21.1 4 20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6ZM20 6L12 11L4 6H20ZM12 13L4 8V18H20V8L12 13Z" fill="white"/>
                        </mask>
                        <g mask="url(#mask0)">
                            <rect width="24" height="24" fill="#94A3B8"/>
                        </g>
                    </svg>
                </div>
                <input id="myStickymenu_update_email" autocomplete="off" value="<?php echo get_option( 'admin_email' ) ?>" placeholder="Email address">
                <button href="javascript:;" class="button button-primary form-submit-btn yes">Sign Up</button>
            </div>
           <!-- <div class="update-form-skip-button">
                <button href="javascript:;" class="button button-secondary form-cancel-btn no">Skip</button>
            </div> -->
        </div>
		
		<div class="update-notice-latter">
			<span><a href="javascript:;" class="skip-dolatter form-cancel-btn no">No, I will do it later</a></span>
		</div>
		
        <div class="update-notice">
            You can remove yourself from the list whenever you want, no strings attached
        </div>
        <input type="hidden" id="myStickymenu_update_nonce" value="<?php echo wp_create_nonce("myStickymenu_update_nonce") ?>">
    </div>
</div>
<style>

@font-face {
   font-family: 'Lato';
   src: url('<?php echo MYSTICKYMENU_URL."fonts/Lato-Regular.ttf";?>');
}

#wpwrap{
	background: url('<?php echo MYSTICKYMENU_URL;?>images/update-bg.jpg');
    background-position: bottom center;
    background-size: cover;		
}
.myStickymenu-updates-form {
    width: 768px;
    padding: 0 30px 0 0;
    box-shadow: 0px 20px 25px rgb(0 0 0 / 10%), 0px 10px 10px rgb(0 0 0 / 4%);
    display: flex;
    margin: 100px auto 0;
    font-family: Lato, sans-serif;
	background:#ffffff;
}
.update-title{
    font-style: normal;
    font-weight: 700;
    font-size: 30px;
    align-items: center;
    color: #334155;
    position: relative;
    line-height: 36px;
	font-family:Lato;
}
.updates-form-form-left {
    padding: 25px 0px 50px 0px;
	background: linear-gradient(180deg, #3C139A 25.79%, #2A0D76 72.72%);
	border-radius: 14.42px 0px 0px 14.419px;
	text-align:center;
	width: 70%;
}
.updates-form-form-left p{
	font-size: 16px;
    line-height: 1.5;
    margin: 0;
    color: #fff;
    padding: 0px 36px;
	font-family:Lato;
}

.updates-form-form-left span{
	color: #fff;
	margin-left: 0px;
    margin-top: 0px;
    position: absolute;
    font-size: 20px;
    line-height: 0.5;
	font-family:Lato;	
}

.updates-form-form-left-text{
	color: #fff;
    font-size: 20px;
    font-weight: 600;
    text-align: left;
    padding-left: 30px;
    font-family:Lato;
}

.updates-form-form-right{
	margin-left: 16px;
	padding: 70px 47px 70px 45px;
}

.updates-form-form-right p {
    font-size: 16px;
	font-style: normal;
    font-weight: normal;
    line-height: 150%;
    position: relative;    
    color: #475569;
    margin: 32px 0px 20px 0px;
	font-family:Lato;
}
/*.updates-form-form-right p:after {
    content: "";
    border: 1px solid #3C85F7;
    position: absolute;
    bottom: 5px;
    left: 0;
    width: 90px;
}*/
.updates-form {
   /* display: flex; */
}
.update-form-input {
    position: relative;
}
.update-form-input input {
    width: 385px;
    background: #F4F4F5;
    border: 1px solid #F4F4F5;
    box-sizing: border-box;
    border-radius: 8px;
    height: 42px;
    line-height: 40px;
    padding: 0 50px 0 40px;
    font-size: 13px;
    box-sizing: border-box;
    color: #334155;
}
.update-form-input .form-submit-btn {
    background: #5A00F0;
    border-radius: 8px;
    border: none;
    color: #fff;
    font-style: normal;
    font-weight: 500;
    font-size: 13px;
    line-height: 150%;
    height: 36px;
    padding: 0 10px;
    position: absolute;
    right: 3px;
    top: 3px;
	width: 100px;
	font-family:Lato;
}

.update-form-input #myStickymenu_update_email:hover{
	border-bottom: 1px solid #5A00F0;
	border-radius: 8px 8px 0px 0px;	
}

.update-form-input #myStickymenu_update_email:focus-visible{
	border :1px solid #5A00F0 !important;
	outline: 1px !important;
	border-radius: 8px;	
}

.update-form-input .form-submit-btn:hover{
	background: #5A00F0;
}

.updates-form .form-cancel-btn.no {
    margin: 0 0 0 3px;
    background: transparent;
    border: none;
    color: #64748B;
    font-size: 13px;
    line-height: 40px;
    padding: 0 0 0 5px;
}
.updates-form .form-cancel-btn.no:hover {
    color: #334155;
}
.mail-icon {
    position: absolute;
    top: 8px;
    left: 10px;
}
.update-notice {
    margin: 50px 22px 0 0px;
    font-size: 16px;
    line-height: 150%;
    color: #28375A;
	text-align:center;
	font-family:Lato;
}

.update-notice-latter{
	margin: 20px 0px;
    width: 100%;
    text-align: center;
	color:#28375A;
}

.update-notice-latter span a{
	text-decoration: underline;
    cursor: pointer;
	color:#28375A;
}


.update-title:after{
	content: '';
    background: #605DEC;
    width: 19%;
    height: 2px;
    position: absolute;
    bottom: -16px;
	left: 0;
}



</style>