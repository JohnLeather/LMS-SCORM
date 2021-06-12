// --------------------------------------------------------------------------------------------
// Copyright 2021 John Leather - www.sphericalgames.co.uk
// Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated 
// documentation files (the "Software"), to deal in the Software without restriction, including without limitation 
// the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
// and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all copies or substantial 
// portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED 
// TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
// THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF 
// CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS 
// IN THE SOFTWARE.
// --------------------------------------------------------------------------------------------
'use strict';

function loginLMSUserLogin() {
	this.construct();
}
// -------------------------------------------------------------------------
// 
// -------------------------------------------------------------------------
loginLMSUserLogin.prototype = {
    construct : function() {
        this.passwordField = document.getElementById("password-field");
        this.usernameField = document.getElementById("username-field");
        this.loginButton = document.getElementById("login-button");
        
        this.usernameField.addEventListener("input", this.validateFields.bind(this));
        this.passwordField.addEventListener("input", this.validateFields.bind(this));
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    validateFields : function(event) {
        var disabled = this.usernameField.value == "" || this.passwordField.value == "";
            
        this.loginButton.disabled = disabled;
        this.loginButton.className = disabled ? "right-arrow-icon disabled" : "right-arrow-icon";
    }
};

window.onload = function() {
    new loginLMSUserLogin();
}