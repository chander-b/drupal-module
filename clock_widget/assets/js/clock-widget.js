(function ($, Drupal) {
  Drupal.behaviors.clockWidget = {
    attach: function (context, settings) {
      // Function to update the clock
      function updateClock() {
        // Fetch the current time from the World Clock API
        $.ajax({
          url: 'https://worldclockapi.com/api/json/' + settings.timezone + '/now',
          method: 'GET',
          dataType: 'json',
          success: function(data, status) {
            if(!data) {
              return;
            }
            // Extract the current time from the API response
            var api_date = new Date(data.currentFileTime/10000);
            var now = new Date( api_date.toLocaleString('en-US', {
              timeZone: 'UTC'
            }));
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            // Display the current time in the clock element
            $('.' + settings.timezone + '-clock-widget').html(hours + ':' + minutes + ':' + seconds);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching time:', status, error);
          }
        });
      }

      // Update the clock every second
      setInterval(updateClock, 1000);

      // Initial call to update clock
      updateClock();
    }
  };
})(jQuery, Drupal);
