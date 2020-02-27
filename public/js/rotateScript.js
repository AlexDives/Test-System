

/* DOM Utility Functions from PastryKit */
Element.prototype.hasClassName = function(a) {
  return new RegExp("(?:^|\\s+)" + a + "(?:\\s+|$)").test(this.className);
};

Element.prototype.addClassName = function(a) {
  if (!this.hasClassName(a)) {
    this.className = [this.className, a].join(" ");
  }
};

Element.prototype.removeClassName = function(b) {
  if (this.hasClassName(b)) {
    var a = this.className;
    this.className = a.replace(
      new RegExp("(?:^|\\s+)" + b + "(?:\\s+|$)", "g"),
      " "
    );
  }
};

Element.prototype.toggleClassName = function(a) {
  this[this.hasClassName(a) ? "removeClassName" : "addClassName"](a);
};

function rotateBlock() {
  var auth_block = document.getElementById("auth-block");
  if (auth_block !== null) {
    auth_block.toggleClassName("flip");
  }
}

/* /DOM Utility Functions from PastryKit */
/*var init = function() {
  document.getElementById("ButtonFormAuth").addEventListener(
    "click",
    function() {
      var auth_block = document.getElementById("auth-block");
      if (auth_block !== null) {
        auth_block.toggleClassName("flip");
      }
    },
    false
  );
};
window.addEventListener("DOMContentLoaded", init, false);*/
