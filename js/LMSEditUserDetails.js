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

if (!String.prototype.trim) {
    String.prototype.trim = function () {
        return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
    };
}



if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (vMember, nStartFrom) {
        if (this == null) {
            throw new TypeError("Array.prototype.indexOf() - can't convert `" + this + "` to object");
        }
        
        var nIdx    = isFinite(nStartFrom) ? Math.floor(nStartFrom) : 0;
        var oThis   = this instanceof Object ? this : new Object(this);
        var nLen    = isFinite(oThis.length) ? Math.floor(oThis.length) : 0;
        
        if (nIdx >= nLen) {
            return -1;
        }
        
        if (nIdx < 0) {
            nIdx = Math.max(nLen + nIdx, 0);
        }
        
        if (vMember === undefined) {
            do {
                if (nIdx in oThis && oThis[nIdx] === undefined) {
                    return nIdx;
                }
            } while (++nIdx < nLen);
        }
        else {
            do {
                if (oThis[nIdx] === vMember) {
                    return nIdx;
                }
            } while (++nIdx < nLen);
        }
        
        return -1;
    };
}



adminLMSUserDetails.prototype = {
    // -------------------------------------------------------------------------
    // Initialise
    // -------------------------------------------------------------------------
    
    construct : function() {
        this.submitButton                       = document.getElementById("submit");
        this.firstName                          = document.getElementById("first-name-field");
        this.lastName                           = document.getElementById("last-name-field");
        this.username                           = document.getElementById("username-field");

        this.passwordToggleIcon                 = document.getElementById("toggleCurrentPassword");
        this.currentPassword                    = document.getElementById("current-password-field");

        this.newPasswordToggleIcon              = document.getElementById("toggleNewPassword");
        this.newPassword                        = document.getElementById("password-field");
        
        this.confirmPasswordToggleIcon          = document.getElementById("toggleConfirmPassword");
        this.confirmPassword                    = document.getElementById("confirm-password-field");
        
        
        if (this.username) {
            this.usernameValue                  = this.username.value.trim();
        }

        if (this.newPassword) {
            if (this.currentPassword) {
                this.passwordToggleIcon.addEventListener('click', this.eventTogglePassword.bind(this, this.passwordToggleIcon, this.currentPassword));
            }
            this.newPasswordToggleIcon.addEventListener('click', this.eventTogglePassword.bind(this, this.newPasswordToggleIcon, this.newPassword));
            this.confirmPasswordToggleIcon.addEventListener('click', this.eventTogglePassword.bind(this, this.confirmPasswordToggleIcon, this.confirmPassword));

            this.decidePlaceholderText();
        }

        //
        // first and last name changed...
        //
        if (this.firstName) {
            this.firstNameValue                 = this.firstName.value.trim();
            this.lastNameValue                  = this.lastName.value.trim();
        
            this.firstName.addEventListener("input", this.eventListenerTextField.bind(this));
            this.lastName.addEventListener("input", this.eventListenerTextField.bind(this));

            let focusFirstLastUserInputFields = this.eventListenerFocusFirstLastUserInputFields.bind(this);
            let blurFirstLastUserInputFields = this.eventListenerBlurFirstLastUserInputFields.bind(this);

            this.firstName.addEventListener("focus", focusFirstLastUserInputFields);
            this.lastName.addEventListener("focus", focusFirstLastUserInputFields);
            
            this.firstName.addEventListener("blur", blurFirstLastUserInputFields);
            this.lastName.addEventListener("blur", blurFirstLastUserInputFields);
        
            if (this.firstNameValue == "") {
                this.firstName.style.border = "red 1px solid";
            }
            if (this.lastNameValue == "") {
                this.lastName.style.border = "red 1px solid";
            }

            if (this.username) {
                this.usernameValue = this.username.value.trim();

                this.username.addEventListener("input", this.eventListenerTextField.bind(this));
                this.username.addEventListener("focus", focusFirstLastUserInputFields);
                this.username.addEventListener("blur", blurFirstLastUserInputFields);
                if (this.usernameValue == "") {
                    this.username.style.border = "red 1px solid";
                }
            }
        }

        if (this.newPassword) {
            this.passwordIsPerfect                  = false;
            this.confirmPassword                    = document.getElementById("confirm-password-field");
            this.passwordHint                       = document.getElementById("password-hint");
            this.confirmPasswordHint                = document.getElementById("confirm-password-hint");
            this.confirmPasswordInfo                = document.getElementById("confirm-password-info");
            
            this.pwLowerCase    = 4;
            this.pwUpperCase    = 3;
            this.pwSymbol       = 2;
            this.pwDigit        = 1;
            this.pwLength       = 0;
            
            this.passwordRequireUpperCase            = passwordRequireUpperCase;
            this.passwordRequireLowerCase            = passwordRequireLowerCase;
            this.passwordRequireSymbols              = passwordRequireSymbols;
            this.passwordRequireNumbers              = passwordRequireNumbers;
            this.passwordMinimumLength               = passwordMinimumLength;
            
            
            
            this.passwordBubbles                    = [];
            this.passwordBar                        = [];
            this.passwordRequired                   = [];
            this.totalPasswordBubbles               = totalPasswordBubbles;
            for (var i = 0; i < totalPasswordBubbles; i++) {
                this.passwordBubbles[i] = document.getElementById("password-hint-bubble-" + (i + 1));
                this.passwordBar[i] = document.getElementById("password-hint-bar-" + (i + 1));
                this.passwordRequired[i] = document.getElementById("password-required-" + (i + 1));
                
                if (i >= 5) {
                    this.passwordBubbles[i].style.display = "none";
                }
            }
            
            this.setRequiredWords();
            
            this.newPassword.oninput                = this.eventListenerForEditingPassword.bind(this);
            this.newPassword.onfocus                = this.eventListenerForFocusPassword.bind(this);
            this.newPassword.onclick                = this.eventListenerForFocusPassword.bind(this);
            this.newPassword.onblur                 = this.eventListenerForBlurPassword.bind(this);
            
            this.confirmPassword.oninput            = this.eventListenerForEditingConfirmPassword.bind(this);
            this.confirmPassword.onfocus            = this.eventListenerForFocusConfirmPassword.bind(this);
            this.confirmPassword.onclick            = this.eventListenerForFocusConfirmPassword.bind(this);
            this.confirmPassword.onblur             = this.eventListenerForBlurConfirmPassword.bind(this);

            if (passwordFieldRequired && this.currentPassword) {
                this.currentPassword.oninput            = this.eventListenerForEditingCurrentPassword.bind(this);
                this.currentPassword.onfocus            = this.eventListenerForFocusCurrentPassword.bind(this);
                this.currentPassword.onclick            = this.eventListenerForFocusCurrentPassword.bind(this);
                this.currentPassword.onblur             = this.eventListenerForBlurCurrentPassword.bind(this);
            }
        }
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerFocusFirstLastUserInputFields : function(event) {
        var element = event.target;
        element.style.border = "#777777 1px solid";
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerBlurFirstLastUserInputFields : function(event) {
        var element = event.target;
        if (element.value == "") {
            element.style.border = "red 1px solid";
        }
        else {
            element.style.border = "#777777 1px solid";
        }
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventTogglePassword : function (icon, passwordField, e) {
        passwordField.setAttribute("type", passwordField.getAttribute('type') === 'password' ? 'text' : 'password');
        icon.classList.toggle('fa-eye-slash');
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerTextField : function(e) {
        var returnValue = true;
        e = e || window.event;
        var key = (typeof e.which == "number") ? e.which : e.keyCode;
        
        if (key != 13 && key != 0 && key != 8 && key != 9) {
            if ((e.ctrlKey == false) && (e.altKey == false)) {
                returnValue =  (' ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-.\''.indexOf(String.fromCharCode(key)) != -1);
            }
        }
        if (!returnValue) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        this.handleSubmitButton();

        return false;
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    decidePlaceholderText : function() {
        //
        // this requires all 3 fields to be filled in when URL is /admin/set-admin-password
        //
        if (passwordFieldRequired) {
            this.newPassword.placeholder            = "Required";
            this.confirmPassword.placeholder        = "Required";
            if (this.currentPassword) {
                this.currentPassword.placeholder    = "Required";
                this.currentPassword.style.border   = this.currentPassword.value.trim() == "" ? "solid 1px red" : "solid 1px #777777";
            }
            this.newPassword.style.border           = this.newPassword.value.trim() == "" || !this.passwordIsPerfect ? "solid 1px red" : "solid 1px #777777";
            this.confirmPassword.style.border       = this.confirmPassword.value.trim() == "" || this.newPassword.value != this.confirmPassword.value ? "solid 1px red" : "solid 1px #777777";
        }
        //
        // This requires just 2 fields to be filled in and these fields are optional when the URL is /admin/edit-user/page/*/order/name/in/asc/id/*
        //
        else {
            let atLeastOnePasswordFieldContainsText = this.newPassword.value != "" || this.confirmPassword.value != "";
            if (atLeastOnePasswordFieldContainsText) {
                this.newPassword.placeholder = "Required";
                this.confirmPassword.placeholder = "Required";
            }
            else {
                this.newPassword.placeholder = "Optional";
                this.confirmPassword.placeholder = "Optional";
            }
            this.newPassword.style.border = (this.newPassword.value == "" && this.confirmPassword.value != "") ? "solid 1px red" : "solid 1px #777777";
        }
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerForEditingOtherField : function(e) {
        this.handleSubmitButton();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerForEditingPassword : function(e) {
        this.handlePassword();
        this.handleSubmitButton();
        this.decidePlaceholderText();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerForFocusPassword : function() {
        this.passwordFocus(true);
        this.decidePlaceholderText();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerForBlurPassword : function() {
        this.passwordFocus(false);
        this.decidePlaceholderText();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerForFocusConfirmPassword : function() {
        this.confirmPasswordFocus(true);
        this.decidePlaceholderText();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerForBlurConfirmPassword : function() {
        this.confirmPasswordFocus(false);
        this.decidePlaceholderText();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerForEditingConfirmPassword : function(e) {
        this.confirmPasswordFocus(true);
        this.handleConfirmPassword();
        this.handleSubmitButton();
        this.decidePlaceholderText();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerForFocusCurrentPassword : function() {
        this.decidePlaceholderText();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerForBlurCurrentPassword : function() {
        this.decidePlaceholderText();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    eventListenerForEditingCurrentPassword : function(e) {
        this.handleSubmitButton();
        this.decidePlaceholderText();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    passwordFocus : function(show) {
        this.updateStats();
        this.passwordHint.style.display = show ? "block" : "none";
        
        this.newPassword.style.border      = (!show && this.newPassword.value.trim().length > 0 && !this.passwordIsPerfect) ? "solid 1px red" : "solid 1px #777777";
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    confirmPasswordFocus : function(show) {
        this.handleConfirmPassword();
        if (this.newPassword.value.trim() == "" && this.confirmPassword.value.trim() == "") {
            show = false;
        }
        this.confirmPasswordHint.style.display = show ? "block" : "none";
        this.confirmPassword.style.border      = (!show && this.newPassword.value != this.confirmPassword.value && this.newPassword.value.trim().length > 0) ? "solid 1px red" : "solid 1px #777777";
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    handleConfirmPassword : function() {
        this.confirmPasswordInfo.innerHTML = this.newPassword.value == this.confirmPassword.value ? "Password matches" : "Password is different";
        this.confirmPasswordHint.style.backgroundColor = this.newPassword.value == this.confirmPassword.value ? "#ddffdd" : "#ffdddd";
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    setRequiredWords : function() {
        this.passwordRequired[this.pwUpperCase].innerHTML       = "At least " + this.passwordRequireUpperCase + " uppercase letter" + this.plural(this.passwordRequireUpperCase);
        this.passwordRequired[this.pwLowerCase].innerHTML       = "At least " + this.passwordRequireLowerCase + " lowercase letter" + this.plural(this.passwordRequireLowerCase);
        this.passwordRequired[this.pwSymbol].innerHTML          = "At least " + this.passwordRequireSymbols   + " symbol" + this.plural(this.passwordRequireSymbols);
        this.passwordRequired[this.pwDigit].innerHTML           = "At least " + this.passwordRequireNumbers + " digit" + this.plural(this.passwordRequireNumbers);
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    plural : function(items) {
        return items > 1 ? "s" : "";
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    handlePassword : function() {
        this.updateStats();
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    updateStats : function() {
        var passwordStr = this.newPassword.value;
        
        var pwCharLowerCase    = 0;
        var pwCharUpperCase    = 0;
        var pwCharSymbol       = 0;
        var pwCharDigit        = 0;
        var pwCharLength       = 0;
        
        for (var i = 0; i < passwordStr.length; i++) {
            var c = passwordStr.charAt(i);
            if (c >= 'a' && c <='z') {
                pwCharLowerCase++;
            }
            else if (c >= 'A' && c <='Z') {
                pwCharUpperCase++;
            }
            else if (c >= '0' && c <='9') {
                pwCharDigit++;
            }
            else {
                pwCharSymbol++;
            }
            pwCharLength++;
        }
        var outOf = 500;
        if (this.passwordRequireLowerCase == 0) {
            outOf -= 100;
        }
        if (this.passwordRequireSymbols == 0) {
            outOf -= 100;
        }
        if (this.passwordRequireUpperCase == 0) {
            outOf -= 100;
        }
        if (this.passwordRequireNumbers == 0) {
            outOf -= 100;
        }
        //
        // Lower case...
        //
        var lcPercentage = this.passwordRequireLowerCase == 0 ? 100 : ((pwCharLowerCase / this.passwordRequireLowerCase) * 100);
        if (lcPercentage > 100) {
            lcPercentage = 100;
        }
        this.passwordBar[this.pwLowerCase].style.width = lcPercentage + "%";
        this.passwordBubbles[this.pwLowerCase].style.display = (lcPercentage >= 100) ? "none" : "block";
        //
        // upper case...
        //
        var ucPercentage = this.passwordRequireUpperCase == 0 ? 100 : ((pwCharUpperCase / this.passwordRequireUpperCase) * 100);
        if (ucPercentage > 100) {
            ucPercentage = 100;
        }
        this.passwordBar[this.pwUpperCase].style.width = ucPercentage + "%";
        this.passwordBubbles[this.pwUpperCase].style.display = (ucPercentage >= 100) ? "none" : "block";
        //
        // symbol
        //
        var symbolPercentage = this.passwordRequireSymbols == 0 ? 100 : ((pwCharSymbol / this.passwordRequireSymbols) * 100);
        if (symbolPercentage > 100) {
            symbolPercentage = 100;
        }
        this.passwordBar[this.pwSymbol].style.width = symbolPercentage + "%";
        this.passwordBubbles[this.pwSymbol].style.display = (symbolPercentage >= 100) ? "none" : "block";
        //
        // digit
        //
        var digitPercentage = this.passwordRequireNumbers == 0 ? 100 : ((pwCharDigit / this.passwordRequireNumbers) * 100);
        if (digitPercentage > 100) {
            digitPercentage = 100;
        }
        this.passwordBar[this.pwDigit].style.width = digitPercentage + "%";
        this.passwordBubbles[this.pwDigit].style.display = (digitPercentage >= 100) ? "none" : "block";
        //
        // length
        //
        var lenPercentage = ((pwCharLength / this.passwordMinimumLength) * 100);
        if (lenPercentage > 100) {
            lenPercentage = 100;
        }
        this.passwordBar[this.pwLength].style.width = lenPercentage + "%";
        this.passwordBubbles[this.pwLength].style.display = (lenPercentage >= 100) ? "none" : "block";
        
        
        var percentage = ((lcPercentage + ucPercentage + symbolPercentage + digitPercentage + lenPercentage) - (500 - outOf)) / outOf;
        
        var suggestionText = [{strength : 0, message : "Password requirements"}, {strength : 0.25, message : "Very weak"}, {strength : 0.5, message : "Weak"}, {strength : 0.85, message : "Better"}, {strength : 0.999, message : "Strong"}, {strength : 1, message : "Very strong"}]
        
        var recomendation = "";
        
        for (var i = 0; i < suggestionText.length; i++) {
            if (percentage <= suggestionText[i].strength) {
                recomendation = suggestionText[i].message;
                break;
            }
        }
        this.passwordRequired[this.pwLength].innerHTML           = lenPercentage < 50 ? "Password too short" : "Password still short";
        
        document.getElementById("password-info").innerHTML = recomendation;
        
        this.passwordHint.style.backgroundColor = percentage == 1.00 ? "#ddffdd" : "#ffffff";
        
        this.passwordIsPerfect = percentage == 1.00;
        
        this.confirmPassword.style.border      = (this.newPassword.value != this.confirmPassword.value && this.newPassword.value.trim().length > 0) ? "solid 1px red" : "solid 1px #777777";
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    handleSubmitButton : function() {
        var canSubmit = false;
        var passwordsIsEmpty = true;

        if (this.newPassword) {
            
            var password                    = this.newPassword.value.trim();
            var confirmPassword             = this.confirmPassword.value.trim();
            var currentPasswordValid        = (passwordFieldRequired == false || !this.currentPassword || (passwordFieldRequired == true && this.currentPassword.value.trim() != ""));
        
            passwordsIsEmpty                = password == "" && confirmPassword == "";

            var inputFieldOK                = !passwordsIsEmpty && password == confirmPassword;
        
            canSubmit                       = inputFieldOK && this.passwordIsPerfect && currentPasswordValid;
        }
        if (this.lastName) {
            if (this.lastName.value != this.lastNameValue || this.firstName.value != this.firstNameValue || (this.username && this.username.value != this.usernameValue)) {
                if (this.newPassword) {
                    
                    //console.log(passwordFieldRequired);
                    canSubmit                       = (!passwordFieldRequired && passwordsIsEmpty) || (password == confirmPassword && password != "");
                    
                    if (canSubmit && !this.passwordIsPerfect && !passwordsIsEmpty) {
                        canSubmit                   = false;
                    }
                }
                else {
                    canSubmit = true;
                }
            }

            
            var isFirstAndLastNameFieldValid    = this.firstName.value.trim() != "" && this.lastName.value.trim() != "";
            if (isFirstAndLastNameFieldValid == false) {
                canSubmit                       = false;
            }
        }

        if (this.username) {
            var isUsernameFieldValid        = this.username.value.trim() != "";
            if (isUsernameFieldValid == false) {
                canSubmit                   = false;
            }
        }

        this.submitButton.disabled          = !canSubmit;
        this.submitButton.className         = canSubmit ? "right-arrow-icon gapButton" : "right-arrow-icon disabled gapButton";
    }
};

window.addEventListener("load", function() {
    new adminLMSUserDetails();
});

function adminLMSUserDetails() {
    this.construct();
}