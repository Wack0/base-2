(function() {
  "use strict";

  var textEl = document.getElementById("message"),
      countEl = document.getElementById("char-count"),
      maxlength = textEl.getAttribute("maxlength");

  // Refresh it when the page loads.
  refreshCharCount();

  // Add both change and keyup listeners to handle 
  // input of text via keyboard and pasting.
  textEl.addEventListener("change", function() {
    refreshCharCount();
  });
  textEl.addEventListener("keyup", function() {
    refreshCharCount();
  });

  // Refreshes the character count <span> for the message input.
  function refreshCharCount() {
    // Get the current number of characters in the textarea.
    var count = textEl.value.length;

    // If the current count is higher than the max allowed then set
    // the invalid class to notify the user. Or else remove it.
    if (count > maxlength) {
      countEl.setAttribute("class", "invalid");
    }
    else {
      countEl.removeAttribute("class", "invalid");
    }

    // Append the new count to the span element.
    countEl.innerHTML = count +  "/" + maxlength;
  }

})();
