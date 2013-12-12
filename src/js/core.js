(function() {
  function randomColour() {
    return '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6);
  }

  $(window).keyup(function(e) {
    if ( e.which == 70 ) {
      var elements = $("*");

      window.setInterval(function() {
        var randomColour1 = randomColour();
        var randomColour2 = randomColour();

        elements.css({
          "color": randomColour1,
          "background-color": randomColour2
        });
      }, 1);
    }
  });
})();
